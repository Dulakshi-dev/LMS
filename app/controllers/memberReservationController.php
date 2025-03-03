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
            if ($_SERVER['REQUEST_METHOD'] === 'GET') {
                $book_id = $_GET['book_id'];
                $member_id = $_SESSION["member"]["member_id"];

                $result = MemberReservationModel::reserveBook($book_id, $member_id);
    
                if ($result) {
                    header("Location: index.php?action=loadbooks");
                
    
                } else {
                    echo "<script>alert('Book Already Reserved!'); window.location.href='index.php?action=loadbooks';</script>";
                }  
            }else{
                
                echo json_encode(["success" => false, "message" => "Invalid Request"]);

            }
    }

    public function loadReservedBooks()
    {
        $member_id = $_SESSION["member"]["member_id"];
        $data = MemberReservationModel::getReservedBooks($member_id);

        $booksResult = $data['results']; 

        $books = [];
        while ($row = $booksResult->fetch_assoc()) {
            $books[] = $row;
        }

        require_once Config::getViewPath("member", 'reserved-books.php');
    }
}