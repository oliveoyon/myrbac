<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <style>
        body {
            margin: 0;
            color: #111827;
            font-family: solaimanlipi, bangla, Arial, sans-serif;
            font-size: 8.5pt;
        }

        .report-header {
            text-align: center;
            margin-bottom: 7px;
        }

        .report-header img {
            width: 100%;
            max-height: 62px;
            object-fit: contain;
        }

        h1 {
            margin: 5px 0 2px;
            font-size: 14pt;
            text-align: center;
        }

        .printed-date {
            margin: 0 0 8px;
            color: #475569;
            text-align: center;
            font-size: 8pt;
        }

        .scope-table,
        .report-table {
            width: 100%;
            border-collapse: collapse;
        }

        .scope-table {
            margin-bottom: 8px;
        }

        .scope-table td,
        .report-table th,
        .report-table td {
            border: 1px solid #cbd5e1;
            padding: 4px 5px;
            vertical-align: top;
            line-height: 1.28;
        }

        .scope-table .label,
        .report-table th {
            background: #eef2f7;
            color: #111827;
            font-weight: bold;
        }

        .scope-table .label {
            width: 68px;
        }

        .report-table th {
            text-align: left;
        }

        .sl {
            width: 22px;
            text-align: center;
        }

        ul {
            margin: 0;
            padding-left: 12px;
        }

        li {
            margin: 0 0 2px;
            padding: 0;
        }
    </style>
</head>
<body>
    <div class="report-header">
        @if (file_exists(public_path('reportHeader.png')))
            <img src="{{ public_path('reportHeader.png') }}" alt="Report Header">
        @endif
    </div>

    <h1>LSID Register Report</h1>
    <p class="printed-date">Printed on {{ now()->format('j M, Y') }}</p>

    <table class="scope-table">
        <tr>
            <td class="label">District</td>
            <td>{{ $reportDistrictName ?: '-' }}</td>
            <td class="label">PNGO</td>
            <td>{{ $reportPngoName ?: 'All PNGO' }}</td>
        </tr>
        @if (! empty($appliedFilters))
            <tr>
                <td class="label">Filters</td>
                <td colspan="3">
                    @foreach ($appliedFilters as $label => $value)
                        <strong>{{ $label }}:</strong> {{ $value }}@if (! $loop->last), @endif
                    @endforeach
                </td>
            </tr>
        @endif
    </table>

    <table class="report-table">
        <thead>
            <tr>
                <th class="sl">SL</th>
                <th>Date</th>
                <th>Service Given By</th>
                <th>Name</th>
                <th>Mobile</th>
                <th>Sex</th>
                <th>Other Info</th>
                <th>Receiver Type</th>
                <th>Intervention</th>
                <th>Service Provided</th>
            </tr>
        </thead>
        <tbody>
            @forelse ($registers as $register)
                <tr>
                    <td class="sl">{{ $loop->iteration }}</td>
                    <td>{{ optional($register->service_date)->format('j M, Y') }}</td>
                    <td>{{ $register->creator->name ?? '-' }}</td>
                    <td>{{ $register->receiver_name }}</td>
                    <td>{{ $register->mobile_number ?: '-' }}</td>
                    <td>{{ $sexOptions[$register->sex] ?? $register->sex }}</td>
                    <td>
                        <ul>
                            @forelse (($register->other_information ?? []) as $item)
                                <li>{{ $otherInformationOptions[$item] ?? $item }}</li>
                            @empty
                                <li>-</li>
                            @endforelse
                        </ul>
                    </td>
                    <td>
                        <ul>
                            @forelse (($register->receiver_types ?? []) as $type)
                                <li>{{ $receiverTypeOptions[$type] ?? $type }}</li>
                            @empty
                                <li>-</li>
                            @endforelse
                            @if ($register->receiver_type_other)
                                <li>{{ $register->receiver_type_other }}</li>
                            @endif
                        </ul>
                    </td>
                    <td>
                        <ul>
                            @forelse (($register->interventions_taken ?? []) as $intervention)
                                <li>{{ $interventionOptions[$intervention] ?? $intervention }}</li>
                            @empty
                                <li>-</li>
                            @endforelse
                        </ul>
                    </td>
                    <td>
                        <ul>
                            @forelse (($register->service_types ?? []) as $type)
                                <li>{{ $serviceTypeOptions[$type] ?? $type }}</li>
                            @empty
                                <li>-</li>
                            @endforelse
                            @if ($register->service_type_other)
                                <li>{{ $register->service_type_other }}</li>
                            @endif
                        </ul>
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="10" style="text-align: center;">No LSID register entries found.</td>
                </tr>
            @endforelse
        </tbody>
    </table>
</body>
</html>
