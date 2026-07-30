<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <style>
        body {
            margin: 0;
            color: #111827;
            font-family: solaimanlipi, bangla, Arial, sans-serif;
            font-size: 10pt;
        }

        .report-header {
            text-align: center;
            margin-bottom: 10px;
        }

        .report-header img {
            width: 100%;
            max-height: 78px;
            object-fit: contain;
        }

        h1 {
            margin: 8px 0 3px;
            font-size: 16pt;
            text-align: center;
        }

        .printed-date {
            margin: 0 0 12px;
            color: #475569;
            text-align: center;
            font-size: 9pt;
        }

        .filter-line {
            margin: -4px 0 12px;
            color: #334155;
            text-align: center;
            font-size: 9pt;
        }

        table {
            width: 100%;
            border-collapse: collapse;
        }

        th,
        td {
            border: 1px solid #cbd5e1;
            padding: 7px 8px;
            text-align: center;
            vertical-align: middle;
        }

        th {
            background: #eef2f7;
            color: #111827;
            font-weight: bold;
        }

        td:first-child,
        th:first-child {
            text-align: left;
        }

        .total-row td {
            background: #f1f5f9;
            color: #111827;
            font-weight: bold;
        }
    </style>
</head>
<body>
    <div class="report-header">
        @if (file_exists(public_path('reportHeader.png')))
            <img src="{{ public_path('reportHeader.png') }}" alt="Report Header">
        @endif
    </div>

    <h1>{{ $title }}</h1>
    <p class="printed-date">Printed on {{ now()->format('j M, Y') }}</p>
    @if (!empty($filters['from_date']) || !empty($filters['to_date']))
        <p class="filter-line">
            Interview Date:
            {{ !empty($filters['from_date']) ? \Carbon\Carbon::parse($filters['from_date'])->format('j M, Y') : 'Start' }}
            to
            {{ !empty($filters['to_date']) ? \Carbon\Carbon::parse($filters['to_date'])->format('j M, Y') : 'Today' }}
        </p>
    @endif

    <table>
        <thead>
            <tr>
                <th>{{ $nameColumn }}</th>
                <th>Male</th>
                <th>Female</th>
                <th>Transgender</th>
                <th>Total</th>
                <th>Under 18</th>
                <th>Disability</th>
            </tr>
        </thead>
        <tbody>
            @forelse ($rows as $row)
                <tr>
                    <td>{{ $row[$nameKey] ?? '-' }}</td>
                    <td>{{ $row['male'] ?? 0 }}</td>
                    <td>{{ $row['female'] ?? 0 }}</td>
                    <td>{{ $row['transgender'] ?? 0 }}</td>
                    <td><strong>{{ $row['total'] ?? 0 }}</strong></td>
                    <td>{{ $row['under_18'] ?? 0 }}</td>
                    <td>{{ $row['disability'] ?? 0 }}</td>
                </tr>
            @empty
                <tr>
                    <td colspan="7" style="text-align: center;">No data found.</td>
                </tr>
            @endforelse
            @if (count($rows))
                <tr class="total-row">
                    <td>Total</td>
                    <td>{{ collect($rows)->sum('male') }}</td>
                    <td>{{ collect($rows)->sum('female') }}</td>
                    <td>{{ collect($rows)->sum('transgender') }}</td>
                    <td><strong>{{ collect($rows)->sum('total') }}</strong></td>
                    <td>{{ collect($rows)->sum('under_18') }}</td>
                    <td>{{ collect($rows)->sum('disability') }}</td>
                </tr>
            @endif
        </tbody>
    </table>
</body>
</html>
