<?php

require_once __DIR__ . '/../../../main.php';
require 'vendor/autoload.php';

use Dompdf\Dompdf;

class ReportController extends Controller
{
    public function generateStaffReport()
    {
            $tableHtml = $_POST['table_html'];

            $html = "<h2>Report</h2>" . $tableHtml;

            $dompdf = new Dompdf();
            $dompdf->loadHtml($html);
            $dompdf->setPaper('A4', 'landscape');
            $dompdf->render();
            $dompdf->stream("user_report.pdf", ["Attachment" => false]);
            exit;
        
    }
}
