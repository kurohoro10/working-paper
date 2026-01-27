<?php
/**
 * WorkingPaperPdfController
 *
 * This controller handles the downloading and exporting of PDF.
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
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Storage;
use Symfony\Component\HttpFoundation\StreamedResponse;

/**
 * Class WorkingPaperPdfController
 *
 * Handles PDF generation and downloads for working papers.
 */
class WorkingPaperPdfController extends Controller
{
    /**
     * Download the working paper PDF.
     *
     * uses snapshot if finalised,
     * otherwise generates on the fly.
     *
     * @param \App\Models\WorkingPaper $workingPaper
     * @return \Symfony\Component\HttpFoundation\StreamedResponse | Illuminate\Http\Response
     */
    public function download(WorkingPaper $workingPaper): StreamedResponse|Response
    {
        // 1. Check for existing snapshot (Finalised state)
        if ($workingPaper->snapshot_pdf_path && Storage::exists($workingPaper->snapshot_pdf_path)) {
            return Storage::download(
                $workingPaper->snapshot_pdf_path,
                'EndureGo_Working_paper_' . $workingPaper->job_reference . '.pdf'
            );
        }

        // 2. Fallback: Dynamic generation (Draft state)
        return Pdf::loadView('pdf.working-paper', [
            'workingPaper' => $workingPaper
        ])->download(
            'EndureGo_Working_Paper_' . $workingPaper->job_reference . '.pdf'
        );
    }
}
