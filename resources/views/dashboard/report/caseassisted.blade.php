<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Case Assistance Data</title>
    <style>
        table {
            width: 100%;
            border-collapse: collapse;
        }
        table, th, td {
            border: 1px solid black;
        }
        th, td {
            padding: 8px;
            text-align: center;
        }
    </style>
</head>
<body>
    <h1>Case Assistance Data</h1>
    <h2>District: {{ $districtName }}</h2>
    <h2>District: {{ $pngoName }}</h2>
    <table>
        <thead>
            <tr>
                <th>Sl No</th>
                <th>Nature of Support</th>
                <th>Male</th>
                <th>Female</th>
                <th>Transgender Person</th>
                <th>Total</th>
                <th>Under 18</th>
                <th>Disability</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($data as $d)
            <tr>
                <td>{{ $loop->iteration }}</td>
                <td>Person Assisted in {{ $d->institute }}</td>
                <td>{{ $d->male }}</td>
                <td>{{ $d->female }}</td>
                <td>{{ $d->transgender }}</td>
                <td>{{ $d->total }}</td>
                <td>{{ $d->under_18 }}</td>
                <td>{{ $d->disability ?? 0 }}</td>
            </tr>
            @endforeach
            <tr>
                <td>Sub Total</td>
                <td></td>
                <td>{{ $data->sum('male') }}</td>
                <td>{{ $data->sum('female') }}</td>
                <td>{{ $data->sum('transgender') }}</td>
                <td>{{ $data->sum('total') }}</td>
                <td>{{ $data->sum('under_18') }}</td>
                <td>{{ $data->sum('disability') }}</td>
            </tr>
        </tbody>
    </table>
</body>
</html>
