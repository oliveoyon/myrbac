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

            $html = '<h1>Laravel mPDF Example</h1>
                     <p>This is a sample PDF generated using mPDF in Laravel.</p>';

            $mpdf->WriteHTML($html);
            $mpdf->Output('sample.pdf', 'D'); // 'D' forces download; use 'I' for inline view

        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()]);
        }
    }
}
