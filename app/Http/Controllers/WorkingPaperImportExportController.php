<?php
/**
 * WorkingPaperImportExportController
 *
 * This controller handles the importing and exporting of data.
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
use Illuminate\Http\RedirectResponse;
use Symfony\Component\HttpFoundation\StreamedResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\View\View;

class WorkingPaperImportExportController extends Controller
{
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

            WorkingPaper::orderBy('created_at')->chunk(500, function ($papers) use ($handle) {
                foreach ($papers as $wp) {
                    fputcsv($handle, [
                        $wp->job_reference,
                        $wp->client_name,
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

    public function showImportForm(): View
    {
        return view('working-papers.import');
    }

    public function preview(Request $request): View
    {
        $request->validate([
            'csv' => ['required', 'file', 'mimes:csv,txt'],
        ]);

        // Store file explicitly on local disk
        $path = $request->file('csv')->store('imports', 'local');
        $fullPath = Storage::disk('local')->path($path);

        if (!file_exists($fullPath)) {
            abort(500, 'Uploadded CSV file not found on disk.');
        }

        $handle  = fopen($fullPath, 'r');
        $headers = fgetcsv($handle);
        fclose($handle);

        $fields = [
            'client_name',
            'service',
            'period',
            'status',
        ];

        return view('working-papers.map', compact(
            'headers',
            'fields',
            'path'
        ));
    }

    public function execute(Request $request): RedirectResponse
    {
        $request->validate([
            'path'    => ['required', 'string'],
            'mapping' => ['required', 'array'],
        ]);

        $handle = fopen(Storage::disk('local')->path($request->path), "r");

        $headers = fgetcsv($handle);

        while(($row = fgetcsv($handle)) !== false) {
            $data = [];

            foreach ($request->mapping as $csvIndex => $field) {
                if ($field && isset($row[$csvIndex])) {
                    $data[$field] = $row[$csvIndex];
                }
            }

            if (!empty($data)) {
                WorkingPaper::create([
                    ...$data,
                    'user_id' => auth()->id(),
                    'status'  => $data['status'] ?? 'draft',
                ]);
            }
        }

        fclose($handle);

        Storage::delete($request->path);

        return redirect()
            ->route('working-papers.index')
            ->with('success', 'CSV import completed');
    }
}
