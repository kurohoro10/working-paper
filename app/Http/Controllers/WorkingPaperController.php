<?php

namespace App\Http\Controllers;

use App\Models\WorkingPaper;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use App\Models\AuditLog;

/**
 * Class WorkingPaperController
 *
 * Handles internal actions on working papers
 * such as finalisation and state changes.
 */
class WorkingPaperController extends Controller
{
    /**
     * Display a list of working papers (internal users).
     */
    public function index()
    {
        $workingPapers = WorkingPaper::latest()->paginate(10);

        return view('working-papers.index', compact('workingPapers'));
    }

    /**
     * Show create form.
     */
    public function create()
    {
        return view('working-papers.create');
    }

    /**
     * Store a new working paper draft.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'client_name'   => 'required|string',
            'service'       => 'required|string',
            'period'        => 'required|string',
        ]);

        $workingPaper = WorkingPaper::create([
            ...$validated,
            'user_id' => auth()->id(),
            'status'  => 'draft',
        ]);

        return redirect()->route(
            'working-papers.show',
            $workingPaper
        );
    }

    /**
     * Display a single working paper.
     */
    public function show(WorkingPaper $workingPaper)
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
     * - Locks the record
     * - Generates an immutable PDF snapshot
     * - Stores audit log
     *
     * @param \App\Models\WorkingPaper $workingPaper
     * @return \Illuminate\Http\RedirectResponse
     */
    public function finalise(WorkingPaper $workingPaper)
    {
        $this->authorize('finalise', $workingPaper);

        // Generate PDF snapshot
        $pdf = Pdf::loadView('pdf.working-paper', [
            'workingPaper' => $workingPaper
        ]);

        // Ensure directory exists
        Storage::makeDirectory('snapshots');
        $path = 'snapshots/working-paper-' . $workingPaper->id . '.pdf';
        Storage::put($path, $pdf->output());

        // Update working paper state
        $workingPaper->update([
            'status'            => 'finalised',
            'finalised_at'      => now(),
            'snapshot_pdf_path' => $path,
        ]);

        // $workingPaper->refresh();

        // Audit log
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
     * Delete a working paper (Admin only).
     */
    public function destroy(WorkingPaper $workingPaper)
    {
        $this->authorize('delete', $workingPaper);

        if ($workingPaper->status === 'finalised') {
            abort(403, 'Finalised working papers cannot be deleted.');
        }

        // Log BEFORE delete (important for polymorphic  relations)
        $workingPaper->auditLogs()->create([
            'action'  => 'deleted',
            'user_id' => auth()->id(),
            'meta'    => [
                'job_reference' => $workingPaper->job_reference,
                'client_name'   => $workingPaper->client_name,
            ],
        ]);

        // Optional: delete snapshot PDF if exists
        if ($workingPaper->snapshot_pdf_path) {
            Storage::delete($workingPaper->snapshot_pdf_path);
        }

        $workingPaper->delete();

        return redirect()
            ->route('working-papers.index')
            ->with('success', 'Working paper deleted successfully.');
    }
}
