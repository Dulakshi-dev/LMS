<?php
require_once __DIR__ . '/../../main.php';

class ReservationController
{
    private $reservationModel;

    public function __construct()
    {
        require_once Config::getModelPath('reservationmodel.php');
        $this->reservationModel = new ReservationModel();
    }


    public function getAllReservations()
    {
        $resultsPerPage = 10;

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $page = isset($_POST['page']) ? (int)$_POST['page'] : 1; 
            $memberid = $_POST['memberid'] ?? null;
            $bookid = $_POST['bookid'] ?? null;
            $title = $_POST['title'] ?? null;

            if (!empty($memberid) || !empty($bookid) || !empty($title)) {
                $reservationsData = ReservationModel::searchReservations($memberid, $bookid, $title, $page, $resultsPerPage);
             }else {
                $reservationsData = ReservationModel::getAllReservations($page, $resultsPerPage);
            }

            $reservations = $reservationsData['results'] ?? [];
            $total = $reservationsData['total'] ?? 0;
            $totalPages = ceil($total / $resultsPerPage);
        
            echo json_encode([
                "success" => true,
                "reservations" => $reservations,
                "total" => $total,
                "totalPages" => $totalPages,
                "currentPage" => $page
            ]);

        }else{
            echo json_encode(["success" => false, "message" => "Invalid request."]);

        }
    }
}