<?php
require_once __DIR__ . '/../../../main.php';

class CirculationController extends Controller
{
    private $circulationModel;

    public function __construct()
    {
        require_once Config::getModelPath('staff','circulationmodel.php');
        $this->circulationModel = new CirculationModel();
    }

    public function getAllBorrowBooks()
    {
        $resultsPerPage = 10;
    
        if ($this->isPost()) {
            $page = (int)$this->getPost('page', 1);
            $bookid = $this->getPost('bookid');
            $memberid = $this->getPost('memberid');
            $status = $this->getPost('status', 'status1'); // default to "All"
    
            if (!empty($bookid) || !empty($memberid) || $status !== 'status1') {
                // Pass status to the search function
                $bookData = CirculationModel::searchBorrowData($bookid, $memberid, $status, $page, $resultsPerPage);
            } else {
                // Default all data
                $bookData = CirculationModel::getAllBorrowData($page, $resultsPerPage);
            }
    
            $issuebooks = $bookData['results'] ?? [];
            $total = $bookData['total'] ?? 0;
            $totalPages = ceil($total / $resultsPerPage);
    
            $this->jsonResponse([
                'issuebooks' => $issuebooks,
                'total' => $total,
                'totalPages' => $totalPages,
                'currentPage' => $page
            ]);
        } else {
            $this->jsonResponse(["message" => "Invalid request."], false);
        }
    }
    

    public function loadBookDetails()
    {
        if ($this->isPost()) {
            $book_id = $this->getPost('book_id');

            $result1 = CirculationModel::getBookDetails($book_id);

            if ($result1) {
                $bookData = $result1->fetch_assoc();
                $this->jsonResponse([
                    "isbn" => $bookData['isbn'],
                    "title" => $bookData['title'],
                    "author" => $bookData['author'],
                ]);
            } else {
                $this->jsonResponse(["message" => "Book not found."], false);
            }
        } else {
            $this->jsonResponse(["message" => "Invalid request."], false);
        }
    }

    public function loadMemberDetails()
    {
        if ($this->isPost()) {
            $member_id = $this->getPost('member_id');
            $result = CirculationModel::getMemberDetails($member_id);

            if ($result) {
                $bookData = $result->fetch_assoc();
                $this->jsonResponse([
                    "nic" => $bookData['nic'],
                    "name" => $bookData['fname'] . " " . $bookData['lname'],
                ]);
            } else {
                $this->jsonResponse(["message" => "Member not found."], false);
            }
        } else {
            $this->jsonResponse(["message" => "Invalid request."], false);
        }
    }

    public function issueBook()
{
    if ($this->isPost()) {
        // Fetch POST values
        $book_id = $this->getPost('book_id');
        $member_id = $this->getPost('member_id');
        $borrow_date = $this->getPost('borrow_date');
        $due_date = $this->getPost('due_date');

        // Call the model's function to issue the book
        $result = CirculationModel::issueBook($book_id, $member_id, $borrow_date, $due_date);
        
        if ($result["success"]) {
            // Return success message in JSON
            $this->jsonResponse(["message" => $result["message"]], true);
        } else {
            // Return failure message in JSON
            $this->jsonResponse(["message" => $result["message"]], false);
        }
    } else {
        // If not a POST request, return an error message
        $this->jsonResponse(["message" => "Invalid request method."], false);
    }
}


    public function returnBook()
    {
        if ($this->isPost()) {
            $borrow_id = $this->getPost('borrowId');
            $book_id = $this->getPost('bookId');
            $memberId = $this->getPost('memberId');
            $return_date = $this->getPost('returnDate');
            $fines = $this->getPost('fines');

            $result = CirculationModel::returnBook($borrow_id, $return_date, $book_id, $fines, $memberId);

            if ($result) {
                CirculationModel::notifyNextWaitlistMember($book_id);
                $this->jsonResponse(["message" => "Book Returned."]);
            } else {
                $this->jsonResponse(["message" => "Failed to return book."], false);
            }
        } else {
            $this->jsonResponse(["message" => "Invalid request."], false);
        }
    }
}
