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
        $page = isset($_GET['page']) ? (int)$_GET['page'] : 1; 
        $member_id = $_SESSION["member"]["member_id"];

        $data = ReservationModel::getAllReservations($member_id, $page);

        $totalBooks = $data['total']; 
        $booksResult = $data['results']; 

        $books = [];
        while ($row = $booksResult->fetch_assoc()) {
            $books[] = $row;
        }

        $resultsPerPage = 10;
        $totalPages = ceil($totalBooks / $resultsPerPage); 

        require_once Config::getViewPath("staff", 'reservation-management.php');
    }
}