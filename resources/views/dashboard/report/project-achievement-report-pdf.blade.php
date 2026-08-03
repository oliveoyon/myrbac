<!DOCTYPE html>
<html lang="bn">

<head>
    <meta charset="UTF-8">
    <style>
        body {
            margin: 0;
            color: #111827;
            font-family: solaimanlipi, bangla, Arial, sans-serif;
            font-size: 11pt;
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

        .project-name {
            margin: 20px 0 3px;
            color: #111827;
            font-size: 13pt;
            font-weight: bold;
            line-height: 1.45;
            text-align: center;
        }

        .project-implementer {
            margin: 0 28px 12px;
            color: #334155;
            font-size: 10.5pt;
            line-height: 1.5;
            text-align: center;
        }

        .report-title {
            margin: 10px 0 14px;
            padding: 7px 10px;
            border-top: 1px solid #cbd5e1;
            border-bottom: 1px solid #cbd5e1;
            background: #f8fafc;
            color: #111827;
            font-size: 14pt;
            font-weight: bold;
            text-align: center;
        }

        .report-meta-box {
            margin: 0 0 14px;
            padding: 8px 10px;
            border: 1px solid #d7dee3;
            background: #fbfcfd;
        }

        .meta-table {
            width: 100%;
            border-collapse: collapse;
            margin: 0;
        }

        .meta-table td {
            border: 0;
            padding: 5px 0;
            font-size: 11pt;
        }

        .meta-label {
            width: 220px;
            font-weight: bold;
        }

        .meta-separator {
            width: 14px;
            text-align: center;
        }

        .report-table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 10px;
        }

        .report-table th,
        .report-table td {
            border: 1px solid #cbd5e1;
            padding: 8px;
            vertical-align: middle;
        }

        .report-table th {
            background: #eef2f7;
            color: #111827;
            font-weight: bold;
            text-align: center;
        }

        .serial {
            width: 42px;
            text-align: center;
            font-weight: bold;
        }

        .count {
            width: 95px;
            text-align: center;
            font-weight: bold;
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

    <div class="project-name">
        এ্যাকসেস টু জাস্টিস ফর উইমেন: স্ট্রেনদেনিং কমিউনিটি ডিসপিউট রিজল্যুশন এন্ড ইমপ্রুভিং কেস ম্যানেজমেন্ট
    </div>
    <div class="project-implementer">
        (আইন ও বিচার বিভাগ, আইন, বিচার ও সংসদ বিষয়ক মন্ত্রণালয় এবং জিআইজেড বাংলাদেশ কর্তৃক বাস্তবায়িত যৌথ প্রকল্প)
    </div>

    <div class="report-title">প্রকল্প কার্যক্রমের অগ্রগতির মাসিক প্রতিবেদন</div>

    <div class="report-meta-box">
        <table class="meta-table">
            <tr>
                <td class="meta-label">জেলা</td>
                <td class="meta-separator">:</td>
                <td>{{ $pdfMeta['district'] ?? '' }}</td>
            </tr>
            <tr>
                <td class="meta-label">মাস</td>
                <td class="meta-separator">:</td>
                <td>{{ $pdfMeta['month'] ?? '' }}</td>
            </tr>
            <tr>
                <td class="meta-label">জেলা পর্যায়ে বাস্তবায়নকারী সংস্থার নাম</td>
                <td class="meta-separator">:</td>
                <td>{{ $pdfMeta['pngo'] ?? '' }}</td>
            </tr>
        </table>
    </div>

    @php
        $printRows = collect($rows)->filter(fn ($row) => (int) ($row['count'] ?? 0) > 0)->values();
    @endphp

    <table class="report-table">
        <thead>
            <tr>
                <th>ক্রমিক</th>
                <th>কার্যক্রম সমূহ</th>
                <th>সংখ্যা</th>
            </tr>
        </thead>
        <tbody>
            @forelse ($printRows as $row)
                <tr>
                    <td class="serial">{{ $loop->iteration }}</td>
                    <td>{{ $row['activity'] }}</td>
                    <td class="count">{{ $row['count'] }} {{ $row['unit'] }}</td>
                </tr>
            @empty
                <tr>
                    <td colspan="3" style="text-align: center;">No data found.</td>
                </tr>
            @endforelse
            @if ($printRows->isNotEmpty())
                <tr class="total-row">
                    <td colspan="2">মোট</td>
                    <td class="count">{{ $printRows->sum('count') }} জন</td>
                </tr>
            @endif
        </tbody>
    </table>
</body>

</html>
