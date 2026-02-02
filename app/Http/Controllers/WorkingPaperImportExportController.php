<?php
/**
 * WorkingPaperImportExportController
 *
 * This controller handles the bulk data operations for Working Papers,
 * allowing users to export the database to CSV and import records via
 * a column-mapping interface.
 *
 * @category  Controllers
 * @package   App\Http\Controllers
 * @author    Name <email@email.com>
 * @copyright 2026 Name
 * @license   https://opensource.org/licenses/MIT MIT License
 * @version   GIT: 1.2.0
 * @link      http://url.com
 */

namespace App\Http\Controllers;

use App\Models\WorkingPaper;
use App\Models\Client;
use Illuminate\Validation\Rule;
use Illuminate\Http\RedirectResponse;
use Symfony\Component\HttpFoundation\StreamedResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\ValidationException;
use Illuminate\View\View;

/**
 * Class WorkingPaperImportExportController
 *
 * Provides functionality for porting data. Uses chunked streaming for
 * memory-efficient exports and a two-step preview/execute workflow for imports.
 */
class WorkingPaperImportExportController extends Controller
{
    /**
     * Export all working papers to a CSV file.
     *
     * Uses a streamed response to handle large datasets without exhausting
     * server memory by processing a records in chunks.
     *
     * @return \Symfony\Component\HttpFoundation\StreamedResponse
     */
    public function export(): StreamedResponse
    {
        $filename = 'working_papers_' . now()->format('Ymd_His') . '.csv';

        return response()->streamDownload(function () {
            $handle = fopen('php://output', 'w');

            // CSV Header
            fputcsv($handle, [
                'job_reference',
                'client_name',
                'service',
                'period',
                'status',
                'created_at'
            ]);

            // Process in chunks to maintain performance
            WorkingPaper::with('client')
                ->orderBy('created_at')
                ->chunk(500, function ($papers) use ($handle) {
                foreach ($papers as $wp) {
                    fputcsv($handle, [
                        $wp->job_reference,
                        optional($wp->client)->name,
                        $wp->service,
                        $wp->period,
                        $wp->status,
                        $wp->created_at,
                    ]);
                }
            });

            fclose($handle);
        }, $filename, [
            'Content-Type' => 'text/csv',
        ]);
    }

    /**
     * Show the initial CSV upload form.
     *
     * @return \Illuminate\View\View
     */
    public function showImportForm(): View
    {
        return view('working-papers.import');
    }

    public function showPreview(): View
    {
        abort_unless(session()->has('import.path'), 404);

        $path = session('import.path');
        $fullPath = Storage::disk('local')->path($path);

        $handle = fopen($fullPath, 'r');
        $headers = fgetcsv($handle);
        fclose($handle);

        $workingPaperFields = ['service', 'period', 'status'];
        $clientFields = ['name', 'email', 'phone', 'company', 'tax_number', 'address', 'notes'];

        return  view('working-papers.map', compact('headers', 'path', 'workingPaperFields', 'clientFields'));
    }

    /**
     * Process the uploaded CSV and show the column mapping interface.
     *
     * Stores the file temporarily and reads the header row to allow
     * the user to match CSV columns to database fields.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function storePreview(Request $request): RedirectResponse
    {
        $request->validate([
            'csv' => ['required', 'file', 'mimes:csv,txt'],
        ]);

        $path = $request->file('csv')->store('imports', 'local');

        session([
            'import' => [
                'path' => $path,
            ],
        ]);

        return redirect()->route('working-paper.import.preview.show');
    }

    /**
     * Execute the import based on user-defined mapping.
     * Uses a Database Transaction to ensure atomicity.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function execute(Request $request): RedirectResponse
    {
        if (! $request->has('mapping')) {
            abort(400, 'Invalid import state.');
        }

        $request->validate([
            'path'    => ['required', 'string'],
            'mapping' => ['required', 'array'],
        ]);

        $requiredFields = [
            'client.name',
            'working_paper.service',
            'working_paper.period',
        ];

        $mappedFields = array_values(array_filter($request->mapping));
        $missing = array_diff($requiredFields, $mappedFields);

        if (! empty($missing)) {
            return back()
                ->withErrors([
                    'import_error' => 'Missing required mappings: ' . implode(', ', $missing),
                ])
                ->withInput();
        }

        $fullPath = Storage::disk('local')->path($request->path);
        $handle = fopen($fullPath, 'r');

        fgetcsv($handle); // skip header
        $rowNumber = 1;

        DB::beginTransaction();

        try {
            while (($row = fgetcsv($handle)) !== false) {
                $rowNumber++;
                $clientData = [];
                $workingPaperData = [];

                foreach ($request->mapping as $columnIndex => $target) {
                    if (! $target) continue;
                    
                    [$type, $field] = explode('.', $target);

                    $value = $row[$columnIndex] ?? null;

                    if ($type === 'client') {
                        $clientData[$field] = $value;
                    }

                    if ($type === 'working_paper') {
                        $workingPaperData[$field] = $value;
                    }
                }

                $workingPaperValidator = Validator::make($workingPaperData, [
                    'service'     => ['required', 'string', 'max:256'],
                    'period'      => ['required', 'integer', 'between:1990,' . date('Y')],
                    'status'      => ['nullable', Rule::in(['draft', 'finalised'])],
                ]);

                $clientValidator = Validator::make($clientData, [
                    'name'       => ['required', 'string', 'max:256'],
                    'email'      => ['nullable', 'email'],
                    'phone'      => ['nullable', 'string', 'max:20'],
                    'company'    => ['nullable', 'string', 'max:150'],
                    'tax_number' => ['nullable', 'string', 'max:50'],
                    'address'    => ['nullable', 'string', 'max:500'],
                    'notes'      => ['nullable', 'string'],
                ]);

                if ($workingPaperValidator->fails()) {
                    throw ValidationException::withMessages([
                        'import_error' => "Row {$rowNumber}: " . $workingPaperValidator->errors()->first(),
                    ]);
                }

                if ($clientValidator->fails()) {
                    throw ValidationException::withMessages([
                        'import_error' => "Row {$rowNumber}: " . $clientValidator->errors()->first(),
                    ]);
                }

                // Create or resolve client safely
                if (! empty($clientData['email'])) {
                    $client = Client::firstOrCreate(
                        ['email' => $clientData['email']],
                        $clientData
                    );
                } else {
                    $client = Client::firstOrCreate(
                        ['name' => $clientData['name']],
                        $clientData
                    );
                }

                WorkingPaper::create([
                    ...$workingPaperData,
                    'user_id' => auth()->id(),
                    'client_id' => $client->id,
                    'status'  => $workingPaperData['status'] ?? 'draft',
                ]);
            }

            DB::commit();
        } catch (\Throwable $e) {
            DB::rollBack();

            fclose($handle);
            Storage::delete($request->path);

            return back()
                ->withErrors([
                    'import_error' => $e->getMessage(),
                ])
                ->withInput();
        }

        fclose($handle);
        Storage::delete($request->path);

        return redirect()
            ->route('working-papers.index')
            ->with('success', 'CSV import completed');
    }
}
