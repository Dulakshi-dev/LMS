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
        if (!$this->isPost()) {
            Logger::warning('Invalid request method for reserveBook', ['method' => $_SERVER['REQUEST_METHOD']]);
            $this->jsonResponse(["message" => "Invalid Request"], false);
            return;
        }

        $book_id = $this->getPost('book_id');
        $availability = $this->getPost('availability');
        $member_id = $_SESSION["member"]["id"];

        if ($availability === "Available") {
            $result = ReservationModel::reserveBook($book_id, $member_id);

            if ($result["success"]) {
                Logger::info('Book reserved successfully', [
                    'member_id' => $member_id,
                    'book_id' => $book_id
                ]);
                $this->jsonResponse(["message" => "Book Reserved"]);
            } else {
                Logger::warning('Failed to reserve book', [
                    'member_id' => $member_id,
                    'book_id' => $book_id,
                    'error' => $result["message"]
                ]);
                $this->jsonResponse(["message" => $result["message"]], false);
            }
        } else {
            $waitlistResult = ReservationModel::addToWaitlist($book_id, $member_id);

            if ($waitlistResult["success"]) {
                Logger::info('Added to waiting list', [
                    'member_id' => $member_id,
                    'book_id' => $book_id
                ]);
                $this->jsonResponse(["message" => "Added to waiting list. We will notify when book is available"]);
            } else {
                Logger::warning('Failed to add to waiting list', [
                    'member_id' => $member_id,
                    'book_id' => $book_id,
                    'error' => $waitlistResult["message"]
                ]);
                $this->jsonResponse(["message" => $waitlistResult["message"]], false);
            }
        }
    }

    public function loadReservedBooks()
    {
        if (!$this->isPost()) {
            Logger::warning('Invalid request method for loadReservedBooks', ['method' => $_SERVER['REQUEST_METHOD']]);
            $this->jsonResponse(["message" => "Invalid request."], false);
            return;
        }

        $id = $_SESSION["member"]["id"];

        $reservationsData = ReservationModel::getReservedBooks($id);
        $reservations = $reservationsData['results'] ?? [];

        $this->jsonResponse(["reservations" => $reservations]);
    }

    public function cancelReservation()
    {
        if (!$this->isPost()) {
            Logger::warning('Invalid request method for cancelReservation', ['method' => $_SERVER['REQUEST_METHOD']]);
            $this->jsonResponse(["message" => "Invalid request."], false);
            return;
        }

        $reservation_id = $this->getPost('reservation_id');
        $result = ReservationModel::cancelReservation($reservation_id);

        if ($result) {
            Logger::info('Reservation cancelled successfully', ['reservation_id' => $reservation_id]);
            $this->jsonResponse(["message" => "Reservation cancelled."]);
        } else {
            Logger::warning('Failed to cancel reservation', ['reservation_id' => $reservation_id]);
            $this->jsonResponse(["message" => "Failed to cancel reservation."], false);
        }
    }
}
