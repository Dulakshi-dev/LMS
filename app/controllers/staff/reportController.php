<?php

require_once __DIR__ . '/../../../main.php';
require_once __DIR__ . '/../../../vendor/autoload.php';
require_once Config::getModelPath('staff', 'librarysetupmodel.php');

use Dompdf\Dompdf;

class ReportController extends Controller
{
    private $librarySetupModel;

    public function __construct()
    {
        require_once Config::getModelPath('staff', 'librarysetupmodel.php');
        $this->librarySetupModel = new LibrarySetupModel();
    }

    public function generateReport()
    {



        $reportTitle = $_POST['title'] ?? 'Library Report';
        $fileName = $_POST['filename'] ?? 'report.pdf';
        $tableHtml = $_POST['table_html'] ?? '<p>No data available</p>';

        $logoPath = $this->getLogoBase64();

        $currentDate = date('Y-m-d');
        
        // Build full HTML
        $html = "
        <html>
        <head>
            <style>
                h2 { text-align: center; margin-bottom: 0; }
                .report-date { text-align: center; margin-top: 5px; margin-bottom: 20px; font-size: 14px; }
                .footer { text-align: center; margin-top: 30px; font-style: italic; font-size: 12px; }

                table { width: 100%; border-collapse: collapse; }
                th, td { border: 1px solid #000; padding: 3px; text-align: left; }
                th { background-color: #f2f2f2; }
            </style>
        </head>
        <body>
            <table width='100%' style='background-color:rgb(104, 104, 104); color: white;'>
                <tr>
                    <td style='width: 20%; text-align: left; border: none;'>
                        <img src='$logoPath' height='60' alt='Logo'>
                    </td>
                    <td style='width: 60%; text-align: center; border: none;'>
                        <h2 style='margin: 0; color: white;'>$reportTitle</h2>
                    </td>
                    <td style='width: 20%; text-align: right; font-size: 15px; border: none;'>
                        <p style='text-align: center;'>Date<br> $currentDate</p>
                        
                    </td>
                </tr>
            </table>
        <div style='margin-top: 10px'> $tableHtml</div>
           
            <div class='footer'>Generated by Library Management System</div>
        </body>
        </html>
    ";

        $dompdf = new Dompdf();
        $dompdf->loadHtml($html);
        $dompdf->setPaper('A4', 'landscape');
        $dompdf->render();
        $dompdf->stream($fileName, ["Attachment" => false]);
        exit;
    }


    private function getLogoBase64()
    {
        $libraryData = LibrarySetupModel::getLibraryInfo();
        $logo = $libraryData['logo'];
    
        $path = Config::getLogoPath() . $logo;
    
        if (file_exists($path)) {
            $imageData = file_get_contents($path);
            $mimeType = mime_content_type($path); // e.g., image/png or image/jpeg
            $base64 = 'data:' . $mimeType . ';base64,' . base64_encode($imageData);
            return $base64;
        }
    
        // fallback image (1x1 transparent PNG)
        return 'data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAAAEAAAABCAYAAAAfFcSJAAAADUlEQVQIHWP4//8/AwAI/AL+KDVaWQAAAABJRU5ErkJggg==';
    }
    
}
