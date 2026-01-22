<?php

namespace App\Http\Controllers;

use App\Models\WorkingPaper;
use App\Services\Odoo\OdooService;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\Storage;

class WorkingPaperPdfController extends Controller
{
    public function download(WorkingPaper $workingPaper)
    {
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
}
