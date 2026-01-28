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
        return view('working-papers.create');
    }

    /**
     * Store a new working paper draft.
     * * Note: Share_token and share_token_expires_at are handled automatically
     * by the WorkingPaper model's "booted" method upon creation.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'client_name'   => 'required|string',
            'service'       => 'required|string',
            'period'        => 'required|string',
        ]);

        $workingPaper = WorkingPaper::create([
            ...$validated,
            'user_id'                => auth()->id(),
            'status'                 => 'draft',
        ]);

        return redirect()->route('working-papers.show', $workingPaper);
    }

    /**
     * Display a single working paper and its associated audit trail.
     *
     * @param \App\Models\WorkingPaper $workingPaper
     * @return \Illuminate\View\View
     */
    public function show(WorkingPaper $workingPaper): View
    {
        $auditLogs = $workingPaper->auditLogs()
            ->with('user')
            ->latest()
            ->get();

        return view('working-papers.show', compact('workingPaper', 'auditLogs'));
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
