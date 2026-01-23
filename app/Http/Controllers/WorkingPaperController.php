<?php

namespace App\Http\Controllers;

use App\Models\WorkingPaper;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

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
            'job_reference' => 'required|string|unique:working_papers',
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
        return view('working-papers.show', compact('workingPaper'));
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
        $pdf = Pdf::loadView('pdf.working-paper'. [
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

        // Audit log
        $workingPaper->auditLogs()->create([
            'action'  => 'finalised',
            'user_id' => auth()->id(),
        ]);

        return back()->with('success', 'Working paper finalised.');
    }
}
