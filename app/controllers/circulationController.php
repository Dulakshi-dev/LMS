<?php
require_once __DIR__ . '/../../main.php';

class CirculationController
{

    private $circulationModel;

    public function __construct()
    {
        require_once Config::getModelPath('circulationmodel.php');
        $this->circulationModel = new CirculationModel();
    }

    public function getAllBorrowBooks()
    {
        $resultsPerPage = 10;

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $page = isset($_POST['page']) ? (int)$_POST['page'] : 1;
            $bookid = $_POST['bookid'] ?? null;
            $memberid = $_POST['memberid'] ?? null;

            if (!empty($bookid) || !empty($memberid)) {
                $bookData = CirculationModel::searchBorrowData($bookid, $memberid, $page, $resultsPerPage);
            } else {
                $bookData = CirculationModel::getAllBorrowData($page, $resultsPerPage);
            }

            $issuebooks = $bookData['results'] ?? [];
            $total = $bookData['total'] ?? 0;
            $totalPages = ceil($total / $resultsPerPage);

            echo json_encode([
                "success" => true,
                "issuebooks" => $issuebooks,
                "total" => $total,
                "totalPages" => $totalPages,
                "currentPage" => $page
            ]);
        } else {
            echo json_encode(["success" => false, "message" => "Invalid request."]);
        }
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
                    "name" => $bookData['fname'] . " " . $bookData['lname'],

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

            // Retrieve form data from the request
            $book_id = $_POST['book_id'];
            $member_id = $_POST['member_id'];
            $borrow_date = $_POST['borrow_date'];
            $due_date = $_POST['due_date'];

            // Call the model function to issue the book
            $result = CirculationModel::issueBook($book_id, $member_id, $borrow_date, $due_date);
            if ($result) {
                // If book issuance is successful, return success response
                echo json_encode(["success" => true, "message" => "Book Issued."]);
            } else {
                // If issuance fails, return an error response
                echo json_encode(["success" => false, "message" => "Invalid request."]);
            }
        } else {
            echo json_encode(["success" => false, "message" => "Invalid request."]);
        }
    }

    public function returnBook()
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {

            // Retrieve form data from the request
            $borrow_id = $_POST['borrowId'];
            $book_id = $_POST['bookId'];
            $memberId = $_POST['memberId'];
            $return_date = $_POST['returnDate'];
            $fines = $_POST['fines'];

            // Call model function to process the return
            $result = CirculationModel::returnBook($borrow_id, $return_date, $book_id, $fines, $memberId);

            if ($result) {
                // Notify the next member in the waitlist (if applicable)
                CirculationModel::notifyNextWaitlistMember($book_id);
                echo json_encode(["success" => true, "message" => "Book Retruned."]);
            } else {
                echo json_encode(["success" => false, "message" => "Failed to return book."]);
            }
        } else {
            echo json_encode(["success" => false, "message" => "Invalid request."]);
        }
    }
}
