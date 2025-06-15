<?php

class ReservationController extends Controller
{
    private $reservationModel;

    public function __construct()
    {
        require_once Config::getModelPath('staff', 'reservationmodel.php');
        $this->reservationModel = new ReservationModel();
    }

    public function getAllReservations()
    {
        $resultsPerPage = 10;

        if ($this->isPost()) {
            $page = $this->getPost('page', 1);
            $memberid = $this->getPost('memberid', null);
            $bookid = $this->getPost('bookid', null);
            $title = $this->getPost('title', null);
            $status = $this->getPost('status', null);

            if (!empty($memberid) || !empty($bookid) || !empty($title) || $status !== null) {
                $reservationsData = ReservationModel::searchReservations($memberid, $bookid, $title, $status, $page, $resultsPerPage);
            } else {
                $reservationsData = ReservationModel::getAllReservations($page, $resultsPerPage);
            }

            $reservations = $reservationsData['results'] ?? [];
            $total = $reservationsData['total'] ?? 0;
            $totalPages = ceil($total / $resultsPerPage);

            $this->jsonResponse([
                "reservations" => $reservations,
                "total" => $total,
                "totalPages" => $totalPages,
                "currentPage" => $page
            ]);
        } else {
            Logger::warning("Invalid request method for getAllReservations.");
            $this->jsonResponse(["message" => "Invalid request."], false);
        }
    }
}
