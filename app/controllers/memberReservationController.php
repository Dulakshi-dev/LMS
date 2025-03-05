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
                $id = $_SESSION["id"];

                $result = MemberReservationModel::reserveBook($book_id, $id);
    
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
        $id = $_SESSION["id"];
        $data = MemberReservationModel::getReservedBooks($id);

        $booksResult = $data['results']; 

        $books = [];
        while ($row = $booksResult->fetch_assoc()) {
            $books[] = $row;
        }

        require_once Config::getViewPath("member", 'reserved-books.php');
    }
}