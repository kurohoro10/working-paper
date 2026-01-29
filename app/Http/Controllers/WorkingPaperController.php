<?php
/**
 * WorkingPaperController
 *
 * this controller manages the internal lifecycle of working papears, including
 * creation, finalisation, and administrative management.
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

use App\Models\Client;
use App\Models\User;
use App\Models\WorkingPaper;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

/**
 * Class WorkingPaperController
 *
 * Orchestrates internal workflows for audit working papers. It handles
 * the transition from draft to finalised states and manages document
 * snapshot generation.
 */
class WorkingPaperController extends Controller
{
    /**
     * Display a paginated list of all working papers.
     *
     * @return \Illuminate\View\View
     */
    public function index(): View
    {
        $workingPapers = WorkingPaper::latest()->paginate(10);

        return view('working-papers.index', compact('workingPapers'));
    }

    /**
     * Show the form for creating a new working paper.
     *
     * @return \Illuminate\View\View
     */
    public function create(): View
    {
        $this->authorize('create', User::class);

        $clients = Client::orderBy('name')->get(['id', 'name', 'email']);

        return view('working-papers.create', compact('clients'));
    }

    /**
     * Store a new working paper draft.
     * * Note: Share_token and share_token_expires_at are handled automatically
     * by the WorkingPaper model's "booted" method upon creation.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(Request $request, Client $client): RedirectResponse
    {
        $this->authorize('create', User::class);

        $currentYear = date('Y');

        $validated = $request->validate([
            'client_id'        => ['nullable', 'exists:clients,id'],
            'client_name'      => ['required_without:client_id', 'string', 'max:255'],
            'email'            => ['nullable', 'email'],
            'service'          => ['required', 'string', 'max:150'],
            'period'           => ['required', 'integer', "between:1990,{$currentYear}"],
        ]);

        // 1. Handle Client Selection/Creation
        if ($request->filled('client_id')) {
            // User picked a client from the list
            $client = Client::findOrFail($request->client_id);

        } else {
            // User typed a new name. Check if the email already belongs to someone else.
            if ($request->filled('email')) {
                $existingClient = Client::where('email', $validated['email'])->first();

                if ($existingClient) {
                    // If the email exists, we link to that cilent instead of crashing or duplicating
                    $client = $existingClient;
                } else {
                    // Truly a new client
                    $client = Client::create([
                        'name'  => $validated['client_name'],
                        'email' => $validated['email'],
                    ]);
                }
            } else {
                // No email provided, Just create by name
                $client = Client::create(['name' => $validated['client_name']]);
            }
        }

        // 2. Create the working paper
        $workingPaper = WorkingPaper::create([
            'client_id' => $client->id,
            'service'   => $validated['service'],
            'period'    => $validated['period'],
            'user_id'   => auth()->id(),
            'status'    => 'draft',
        ]);

        return redirect()
            ->route('working-papers.show', $workingPaper)
            ->with('success', 'Working paper created successfully.');
    }

    /**
     * Display a single working paper and its associated audit trail.
     *
     * @param \App\Models\WorkingPaper $workingPaper
     * @return \Illuminate\View\View
     */
    public function show(Request $request, WorkingPaper $workingPaper): View
    {
        $editingExpense = null;

        $auditLogs = $workingPaper->auditLogs()
            ->with('user')
            ->latest()
            ->get();

        if ($request->filled('expense')) {
            $editingExpense = $workingPaper->expenses()
                ->where('id', $request->expense)
                ->firstOrFail();

            $this->authorize('update', $editingExpense);
        }

        return view('working-papers.show', compact('workingPaper', 'auditLogs', 'editingExpense'));
    }

    /**
     * Show edit form for admin-only fields.
     *
     * @param \App\Models\WorkingPaper $workingPaper
     * @return Illuminate\View\View
     */
    public function edit(WorkingPaper $workingPaper): View
    {
        $this->authorize('update', $workingPaper);

        return view('working-papers.edit', compact('workingPaper'));
    }

    public function update(Request $request, WorkingPaper $workingPaper): RedirectResponse
    {
        $this->authorize('update', $workingPaper);

        $currentYear = date('Y');

        $validated = $request->validate([
            'service'     => ['required', 'string', 'max:255'],
            'period'           => ['required', 'integer', "between:1990,{$currentYear}"],
        ]);

        $workingPaper->update($validated);

        // Audit trail
        $workingPaper->auditLogs()->create([
            'action'  => 'updated_details',
            'user_id' => auth()->id(),
            'meta'    => $validated,
        ]);

        return redirect()
            ->route('working-papers.show', $workingPaper)
            ->with('success', 'Client and service updated successfully');
    }

    /**
     * Finalise a working paper.
     *
     * Transition the papear to a read-only state, generate an immutable
     * PDF snapshot, and record the action in the audit log.
     *
     * @param \App\Models\WorkingPaper $workingPaper
     * @return \Illuminate\Http\RedirectResponse
     * @throws \Illuminate\Auth\Access\AuthorizationException
     */
    public function finalise(WorkingPaper $workingPaper):RedirectResponse
    {
        $this->authorize('finalise', $workingPaper);

        // Generate PDF snapshot using the snapshot view
        $pdf = Pdf::loadView('pdf.working-paper', [
            'workingPaper' => $workingPaper
        ]);

        // Ensure storage directory exists and save file
        Storage::makeDirectory('snapshots');
        $path = 'snapshots/working-paper-' . $workingPaper->id . '.pdf';
        Storage::put($path, $pdf->output());

        // Update state and lock the record
        $workingPaper->update([
            'status'            => 'finalised',
            'finalised_at'      => now(),
            'snapshot_pdf_path' => $path,
        ]);

        // Record the finalisation in audit logs
        $workingPaper->auditLogs()->create([
            'action'  => 'finalised',
            'user_id' => auth()->id(),
            'meta'    => [
                'job_reference' => $workingPaper->job_reference ?? 'N/A',
                'client_name'   => $workingPaper->client_name ?? 'N/A',
            ],
        ]);

        return back()->with('success', 'Working paper finalised.');
    }

    /**
     * Delete a working paper draft.
     *
     * Restricts deletion of finalised papers to preserve data integrity.
     * Logs the deletion event before removing the record.
     *
     * @param \App\Models\WorkingPaper $workingPaper
     * @return \Illuminate\Http\RedirectResponse
     * @throws \Illuminate\Auth\Access\AuthorizationException
     */
    public function destroy(WorkingPaper $workingPaper): RedirectResponse
    {
        $this->authorize('delete', $workingPaper);

        if ($workingPaper->status === 'finalised') {
            abort(403, 'Finalised working papers cannot be deleted.');
        }

        // Log the deletion prior to record removal for audit persistence
        $workingPaper->auditLogs()->create([
            'action'  => 'deleted',
            'user_id' => auth()->id(),
            'meta'    => [
                'job_reference' => $workingPaper->job_reference,
                'client_name'   => $workingPaper->client_name,
            ],
        ]);

        // Clean up physical file assets if they exist
        if ($workingPaper->snapshot_pdf_path) {
            Storage::delete($workingPaper->snapshot_pdf_path);
        }

        $workingPaper->delete();

        return redirect()
            ->route('working-papers.index')
            ->with('success', 'Working paper deleted successfully.');
    }
}
