<?php

namespace App\Http\Controllers;

use App\Models\WorkingPaper;
use Barryvdh\DomPDF\Facade\Pdf;
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
