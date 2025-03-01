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

    public function reserveBook()
    {
            if ($_SERVER['REQUEST_METHOD'] === 'GET') {
                $book_id = $_GET['book_id'];
                $member_id = $_SESSION["member"]["member_id"];

                $result = ReservationModel::reserveBook($book_id, $member_id);
    
                if ($result) {
                    header("Location: index.php?action=loadbooks");
                
    
                } else {
                    echo json_encode(["success" => false, "message" => "Reservation failed"]);
                }  
            }else{
                
                echo json_encode(["success" => false, "message" => "Invalid Request"]);

            }
    }
}