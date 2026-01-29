<?php
namespace App\Services;

use App\Models\WorkingPaper;
use App\Models\Client;
use Illuminate\Support\Facades\Log;

class DataExchangeService
{
    public function exportToCsv(array $data, string $filename)
    {
        $handle = fopen('php://output', 'w');

        // Setting headers for browser download
        header('Content-Type: text/csv');
        header('Content-Disposition: attachment; filename="' . $filename . '"');

        // Add headers (keys of the first array item)
        if (! empty($data)) {
            fputcsv($handle, array_keys($data[0]));
        }

        foreach ($data as $row) {
            fputcsv($handle, $row);
        }

        fclose($handle);
        exit;
    }

    public function validateImportData(array $rows, string $type)
    {

    }
}
