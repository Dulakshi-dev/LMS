<?php

// ReservationController handles operations related to book reservations in the library system
class ReservationController extends Controller
{
    private $reservationModel;

    public function __construct()
    {
        // Load the Reservation model to access reservation-related database operations
        require_once Config::getModelPath('staff', 'reservationmodel.php');
        $this->reservationModel = new ReservationModel();
    }

    // Retrieve all reservations, with optional filtering and pagination
    public function getAllReservations()
    {
        $resultsPerPage = 10;

        if ($this->isPost()) {
            // Get pagination and filter parameters from POST request
            $page = $this->getPost('page', 1);
            $memberid = $this->getPost('memberid', null);
            $bookid = $this->getPost('bookid', null);
            $title = $this->getPost('title', null);
            $status = $this->getPost('status', null);

            // If any filter is provided, perform search; otherwise get all reservations
            if (!empty($memberid) || !empty($bookid) || !empty($title) || $status !== null) {
                $reservationsData = ReservationModel::searchReservations($memberid, $bookid, $title, $status, $page, $resultsPerPage);
            } else {
                $reservationsData = ReservationModel::getAllReservations($page, $resultsPerPage);
            }

            // Extract results and calculate pagination
            $reservations = $reservationsData['results'] ?? [];
            $total = $reservationsData['total'] ?? 0;
            $totalPages = ceil($total / $resultsPerPage);

            // Return JSON response with reservations data
            $this->jsonResponse([
                "reservations" => $reservations,
                "total" => $total,
                "totalPages" => $totalPages,
                "currentPage" => $page
            ]);
        } else {
            // Log and respond if request is not a POST
            Logger::warning("Invalid request method for getAllReservations.");
            $this->jsonResponse(["message" => "Invalid request."], false);
        }
    }
}
