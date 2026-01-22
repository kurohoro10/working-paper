<?php

namespace App\Http\Controllers;

use App\Models\WorkingPaper;
use App\Services\Odoo\OdooService;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\Storage;

/**
 * Class WorkingPaperPdfController
 *
 * Handles PDF generation and downloads
 * for working papers.
 */
class WorkingPaperPdfController extends Controller
{
    /**
     * Download the working paper PDF.
     *
     * uses snapshot if finalised,
     * otherwise geneerates on the fly.
     *
     * @param \App\Models\WorkingPaper $workingPaper
     * @return \Symfony\Component\HttpFoundation\BinaryFileResponse
     */
    public function download(WorkingPaper $workingPaper)
    {
        if ($workingPaper->snapshot_pdf_path && Storage::exists($workingPaper->snapshot_pdf_path)) {
            return Storage::download(
                $workingPaper->snapshot_pdf_path,
                'EndureGo_Working_paper_' . $workingPaper->job_reference . '.pdf'
            );
        }

        return Pdf::loadView('pdf.working-paper', [
            'workingPaper' => $workingPaper
        ])->download(
            'EndureGo_Working_Paper_' . $workingPaper->job_reference . '.pdf'
        );
    }

    public function exportToOdoo(
        WorkingPaper $workingPaper,
        OdooService $odoo
    ) {
        // Ensure directory exists
        Storage::makeDirectory('pdfs');

        $pdf = Pdf::loadView('pdf.working-paper', [
            'workingPaper' => $workingPaper
        ]);

        $pdfPath = storage_path(
            'app/pdfs/EndureGo_Working_Paper_' . $workingPaper->job_reference . '.pdf'
        );

        // save PDF to disk
        file_put_contents($pdfPath, $pdf->output());

        // Send to Odoo
        $odoo->sendWorkingPaper($workingPaper, $pdfPath);

        return back()->with('success', 'Sent to Odoo');
    }

    /**
     * Finalise a working paper:
     * - Authorise
     * - Generate snapshot PDF
     * - Lock record
     * - Write audit log
     */
    public function finalise(WorkingPaper $workingPaper)
    {
        $this->authorize('finalise', $workingPaper);

        $pdf = Pdf::loadView('pdf.working-paper', [
            'workingPaper' => $workingPaper
        ]);

        $path = 'snapshots/working-paper-' . $workingPaper->id . '.pdf';

        Storage::put($path, $pdf->output());

        $workingPaper->update([
            'status' => 'finalised',
            'finalised_at' => now(),
            'snapshot_pdf_path' => $path,
        ]);

        $workingPaper->auditLogs()->create([
            'action' => 'finalised',
            'user_id' => auth()->id(),
        ]);

        return back()->with('success', 'Working paper finalised.');
    }
}
