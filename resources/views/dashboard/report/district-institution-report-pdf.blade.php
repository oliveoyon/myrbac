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

        .printed-date,
        .filter-line {
            margin: 0 0 8px;
            color: #475569;
            text-align: center;
            font-size: 9pt;
        }

        .filter-line span {
            display: inline-block;
            margin: 0 4px 4px;
            padding: 3px 7px;
            border: 1px solid #d7dee3;
            border-radius: 10px;
            background: #f8fafc;
            color: #334155;
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

    @if (!empty($appliedFilters))
        <p class="filter-line">
            @foreach ($appliedFilters as $label => $value)
                <span>{{ $label }}: {{ $value }}</span>
            @endforeach
        </p>
    @endif

    <table>
        <thead>
            <tr>
                <th>District</th>
                <th>Court</th>
                <th>Police Station</th>
                <th>Prison</th>
                <th>Total</th>
            </tr>
        </thead>
        <tbody>
            @forelse ($rows as $row)
                <tr>
                    <td>{{ $row['district_name'] ?? '-' }}</td>
                    <td>{{ $row['court'] ?? 0 }}</td>
                    <td>{{ $row['police_station'] ?? 0 }}</td>
                    <td>{{ $row['prison'] ?? 0 }}</td>
                    <td><strong>{{ $row['total'] ?? 0 }}</strong></td>
                </tr>
            @empty
                <tr>
                    <td colspan="5" style="text-align: center;">No data found.</td>
                </tr>
            @endforelse
            @if (count($rows))
                <tr class="total-row">
                    <td>Total</td>
                    <td>{{ collect($rows)->sum('court') }}</td>
                    <td>{{ collect($rows)->sum('police_station') }}</td>
                    <td>{{ collect($rows)->sum('prison') }}</td>
                    <td><strong>{{ collect($rows)->sum('total') }}</strong></td>
                </tr>
            @endif
        </tbody>
    </table>
</body>
</html>
