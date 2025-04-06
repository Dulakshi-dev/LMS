<?php
require_once __DIR__ . '/../../../main.php';

class ReservationController extends Controller
{
    private $reservationModel;

    public function __construct()
    {
        require_once Config::getModelPath('member', 'reservationmodel.php');
        $this->reservationModel = new ReservationModel();
    }

    public function reserveBook()
    {
        if ($this->isPost()) {
            $book_id = $this->getPost('book_id');
            $availability = $this->getPost('availability');
            $member_id = $_SESSION["member"]["id"];

            if ($availability === "Available") {
                $result = ReservationModel::reserveBook($book_id, $member_id);

                if ($result["success"]) {
                    $this->jsonResponse(["message" => "Book Reserved"]);
                } else {
                    $this->jsonResponse(["message" => $result["message"]], false);
                }
            } else {
                $waitlistResult = ReservationModel::addToWaitlist($book_id, $member_id);

                if ($waitlistResult["success"]) {
                    $this->jsonResponse(["message" => "Added to waiting list. We will notify when book is available"]);
                } else {
                    $this->jsonResponse(["message" => $waitlistResult["message"]], false);
                }
            }
        } else {
            $this->jsonResponse(["message" => "Invalid Request"], false);
        }
    }

    public function loadReservedBooks()
    {
        if ($this->isPost()) {
            $id = $_SESSION["member"]["id"];
            $reservationsData = ReservationModel::getReservedBooks($id);
            $reservations = $reservationsData['results'] ?? [];

            $this->jsonResponse(["reservations" => $reservations]);
        } else {
            $this->jsonResponse(["message" => "Invalid request."], false);
        }
    }

    public function cancelReservation()
    {
        if ($this->isPost()) {
            $reservation_id = $this->getPost('reservation_id');
            ReservationModel::cancelReservation($reservation_id);

            $this->jsonResponse(["message" => "Reservation cancelled."]);
        } else {
            $this->jsonResponse(["message" => "Invalid request."], false);
        }
    }
}
