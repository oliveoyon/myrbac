<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Invoice</title>
    <style>
        body {
            font-family: Arial, sans-serif
            margin: 0;
            padding: 0;
            min-height: 100%;
        }

        .container {
            width: 100%;
            margin: 0 auto;
            padding-bottom: 50px; /* Extra space to prevent content overlapping footer */
        }

        .header {
            margin-bottom: 30px;
        }

        .school_info {
            text-align: center;
            width: 100%;
        }

        h1,
        h2 {
            margin: 0;
        }

        p {
            margin: 5px 0;
        }

        h1 {
            font-size: 22px;
        }

        h2 {
            font-size: 20px;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }

        th,
        td {
            padding: 8px;
            text-align: left;
            border: 1px solid #ddd;
        }

        th {
            background-color: #f2f2f2;
        }

        .amount-in-words {
            text-align: center;
            clear: both;
            /* Clear the float to prevent issues */
            margin-top: 20px;
        }

        .thank-you {
            margin-top: 20px;
        }

        /* Add space between columns in the no-border-table */
        .info-column:not(:last-child) {
            margin-right: 4%;
            /* Adjust the margin as needed */
        }
    </style>
</head>

<body>
    <div class="container">
        <!--mpdf
        <htmlpageheader name="myheader">
            <div class="header">
                <div class="school_info">
                    <h1>GIZ Bangladesh</h1>
                    <p>Dhaka, Phone: 01712105580, 01258457854</p>
                    <p>Email: arifur@gmail.com, Web: {{url('/')}}</p>
                </div>
            </div>
        </htmlpageheader>

        <sethtmlpageheader name="myheader" value="on" show-this-page="1" />
        mpdf-->

        <!--mpdf
        <htmlpagefooter name="myfooter">
            <div style="text-align: center; font-size: 10px; padding: 10px 0; border-top: 1px solid #000;">
                <p>Page {PAGENO} of {nbpg}</p>
                <p>Powered by GIZ | © {{ date('Y') }} GIZ</p>
            </div>
        </htmlpagefooter>


    <sethtmlpagefooter name="myfooter" value="on" />
    mpdf-->

        <h2 style="text-align: center; margin-top: 0px; padding-top: 0px; font-family: SolaimanLipi">{!! $title !!}</h2>
        <p style="font-size:10px; font-style:italic">Report Printed on: {{ date('F j, Y') }}</p>
        <div style="clear: both;"></div> <!-- Clear the float -->

        {!! $data !!}

    </div>
    
</body>

</html>
