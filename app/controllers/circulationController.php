<?php
require_once __DIR__ . '/../../main.php';

class CirculationController{

    private $circulationModel;

    public function __construct()
    {
        require_once Config::getModelPath('circulationmodel.php');
        $this->circulationModel = new CirculationModel();
    }

    public function showCirculationManagement()
    {
        require_once Config::getViewPath("staff","circulation-management.php");
    }

    public function showIssueBook()
    {
        require_once Config::getViewPath("staff","issue-book.php");
    }

    public function loadBookDetails()
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $book_id = $_POST['book_id'];

            $result1 = CirculationModel::getBookDetails($book_id);

            if ($result1) {
                $bookData = $result1->fetch_assoc();
                echo json_encode([
                    "success" => true,
                    "isbn" => $bookData['isbn'],
                    "title" => $bookData['title'],
                    "author" => $bookData['author'],
            
                ]);
            } else {
                echo json_encode(["success" => false, "message" => "Book not found."]);
            }

        } else {
            echo json_encode(["success" => false, "message" => "Invalid request."]);
        }
    }

    public function loadMemberDetails()
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $member_id = $_POST['member_id'];
            $result = CirculationModel::getMemberDetails($member_id);


            if ($result) {
                $bookData = $result->fetch_assoc();
                echo json_encode([
                    "success" => true,
                    "nic" => $bookData['nic'],
                    "name" => $bookData['fname']." ".$bookData['lname'],
            
                ]);
            } else {
                echo json_encode(["success" => false, "message" => "Member not found."]);
            }

        } else {
            echo json_encode(["success" => false, "message" => "Invalid request."]);
        }
    }

    public function issueBook()
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $book_id = $_POST['book_id'];
            $member_id = $_POST['member_id'];
            $borrow_date = $_POST['borrow_date'];
            $due_date = $_POST['due_date'];
          
            $result = CirculationModel::issueBook($book_id, $member_id, $borrow_date, $due_date);
            if($result){
                echo("issued");
            }else{
                echo("error");

            }

         
        } else {
            echo json_encode(["success" => false, "message" => "Invalid request."]);
        }
    }

}