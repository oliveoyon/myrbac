<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Mpdf\Mpdf;

class PDFController extends Controller
{
    public function generatePDF()
    {
        try {
            $mpdf = new Mpdf();

            $bladeViewPath = 'dashboard.report.formtest';
            $html = view($bladeViewPath)->render();

            $mpdf->WriteHTML($html);

            // $pdfFilePath = public_path('hello.pdf');
            // $mpdf->Output($pdfFilePath, 'F');

            $mpdf->Output('sample.pdf', 'I'); // 'D' forces download; use 'I' for inline view

        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()]);
        }
    }
}
