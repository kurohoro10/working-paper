<?php
namespace App\Services\Odoo;

use App\Models\WorkingPaper;
use Illuminate\Support\Facades\Http;

class OdooService
{
    public function sendWorkingPaper(
        WorkingPaper $workingPaper,
        string $pdfPath
    ): void
    {
        Http::withToken(config('services.odoo.token'))
            ->attach(
                'file',
                file_get_contents($pdfPath),
                'EndureGo_Working_Paper_' . $workingPaper->job_reference . '.pdf'
            )
            ->post(
                config('services.odoo.endpoint') . '/working-papers',
                [
                    'job_reference' => $workingPaper->job_reference,
                    'client_name'   => $workingPaper->client_name,
                    'period'        => $workingPaper->period,
                    'expenses'      => $workingPaper->expenses->map(fn ($e) => [
                        'description' => $e->description,
                        'amount'      => $e->amount,
                    ])->toArray(),
                ]
            );
    }
}
