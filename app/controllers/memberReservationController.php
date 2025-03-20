<?php
require_once __DIR__ . '/../../main.php';

class MemberReservationController
{

    private $memberReservationModel;

    public function __construct()
    {
        require_once Config::getModelPath('memberreservationmodel.php');
        $this->memberReservationModel = new MemberReservationModel();
    }

    public function reserveBook()
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $book_id = $_POST['book_id'];
            $availability = $_POST['availability'];
            $member_id = $_SESSION["member"]["id"];

            if ($availability == "Available") {
                $result = MemberReservationModel::reserveBook($book_id, $member_id);

                if ($result["success"]) {
                    echo json_encode(["success" => true, "message" => "Book Reserved"]);
                    exit;
                } else {
                    echo json_encode(["success" => false, "message" => "{$result['message']}"]);
                    exit;
                }
            } else {                   

                $waitlistResult = MemberReservationModel::addToWaitlist($book_id, $member_id);

                if ($waitlistResult["success"]) {
                    echo json_encode(["success" => true, "message" => "Added to waiting list. We will notify when book is available"]);
                } else {
                    echo json_encode(["success" => false, "message" => "{$waitlistResult['message']}"]);

                }
            }
        } else {
            echo json_encode(["success" => false, "message" => "Invalid Request"]);
        }
    }


    public function loadReservedBooks()
    {

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $id = $_SESSION["member"]["id"];
            $reservationsData = MemberReservationModel::getReservedBooks($id);


            $reservations = $reservationsData['results'] ?? [];


            echo json_encode([
                "success" => true,
                "reservations" => $reservations,
            ]);
        } else {
            echo json_encode(["success" => false, "message" => "Invalid request."]);
        }
    }

    public function cancelReservation()
    {

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $reservation_id = $_POST['reservation_id'];

             MemberReservationModel::cancelReservation($reservation_id);

            echo json_encode(["success" => true, "message" => "Reservation cancelled."]);

        } else {
            echo json_encode(["success" => false, "message" => "Invalid request."]);
        }
    }
}
