<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Working Paper {{ $workingPaper->job_reference }}</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Helvetica Neue', Arial, sans-serif;
            color: #333;
            line-height: 1.6;
            padding: 40px;
            font-size: 11pt;
        }

        .header {
            border-bottom: 3px solid #2563eb;
            padding-bottom: 20px;
            margin-bottom: 30px;
        }

        .header h1 {
            font-size: 24pt;
            color: #1e3a8a;
            margin-bottom: 10px;
            font-weight: 600;
        }

        .metadata {
            display: table;
            width: 100%;
            margin-top: 15px;
        }

        .metadata-row {
            display: table-row;
        }

        .metadata-label {
            display: table-cell;
            font-weight: 600;
            color: #4b5563;
            padding: 6px 0;
            width: 150px;
        }

        .metadata-value {
            display: table-cell;
            padding: 6px 0;
            color: #1f2937;
        }

        .section-title {
            font-size: 16pt;
            color: #1e3a8a;
            margin-top: 30px;
            margin-bottom: 15px;
            font-weight: 600;
            border-bottom: 2px solid #e5e7eb;
            padding-bottom: 8px;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 10px;
            background: white;
        }

        thead {
            background-color: #f3f4f6;
        }

        th {
            padding: 12px 16px;
            text-align: left;
            font-size: 10pt;
            font-weight: 600;
            color: #374151;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            border-bottom: 2px solid #d1d5db;
        }

        th.text-right {
            text-align: right;
        }

        td {
            padding: 12px 16px;
            border-bottom: 1px solid #e5e7eb;
            font-size: 10pt;
        }

        td.text-right {
            text-align: right;
            font-family: 'Courier New', monospace;
        }

        tbody tr:hover {
            background-color: #f9fafb;
        }

        tbody tr:last-child td {
            border-bottom: 2px solid #d1d5db;
        }

        .total-row {
            background-color: #eff6ff !important;
            font-weight: 600;
        }

        .total-row td {
            padding: 14px 16px;
            border-top: 2px solid #2563eb;
            border-bottom: 3px double #2563eb;
        }

        .footer {
            margin-top: 50px;
            padding-top: 20px;
            border-top: 1px solid #e5e7eb;
            font-size: 9pt;
            color: #6b7280;
            text-align: center;
        }

        @media print {
            body {
                padding: 20px;
            }

            tbody tr:hover {
                background-color: transparent;
            }
        }
    </style>
</head>
<body>
    <div class="header">
        <h1>{{ $workingPaper->client_name }}</h1>
        <div class="metadata">
            <div class="metadata-row">
                <div class="metadata-label">Service:</div>
                <div class="metadata-value">{{ $workingPaper->service }}</div>
            </div>
            <div class="metadata-row">
                <div class="metadata-label">Period:</div>
                <div class="metadata-value">{{ $workingPaper->period }}</div>
            </div>
            <div class="metadata-row">
                <div class="metadata-label">Job Reference:</div>
                <div class="metadata-value">{{ $workingPaper->job_reference }}</div>
            </div>
        </div>
    </div>

    @if($workingPaper->expenses->count())
        <h2 class="section-title">Expenses</h2>

        <table>
            <thead>
                <tr>
                    <th>{{ __('Description') }}</th>
                    <th class="text-right">{{ __('Amount') }}</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($workingPaper->expenses as $expense)
                    <tr>
                        <td>{{ $expense->description }}</td>
                        <td class="text-right">
                            {{ number_format($expense->amount, 2) }}
                        </td>
                    </tr>
                @endforeach
            </tbody>
            <tfoot>
                <tr class="total-row">
                    <td><strong>{{ __('Total Expenses') }}</strong></td>
                    <td class="text-right">
                        <strong>{{ number_format($workingPaper->expenses->sum('amount'), 2) }}</strong>
                    </td>
                </tr>
            </tfoot>
        </table>
    @endif

    <div class="footer">
        Generated on {{ date('F d, Y') }} | Working Paper Reference: {{ $workingPaper->job_reference }}
    </div>
</body>
</html>
