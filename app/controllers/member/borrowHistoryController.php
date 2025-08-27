<?php
require_once __DIR__ . '/../../../main.php'; // Include the main system setup file

class BorrowHistoryController extends Controller
{
    private $borrowHistoryModel;

    public function __construct()
    {   // Load the BorrowHistoryModel (handles database queries for borrowed books)
        require_once Config::getModelPath('member', 'borrowhistorymodel.php');
        $this->borrowHistoryModel = new BorrowHistoryModel();
    }

    public function loadBorrowBooks()
    {
        // Get logged-in member's ID from session (if available)
        $id = $_SESSION["member"]["id"] ?? '';
        // Number of records to show per page
        $resultsPerPage = 10;

        if ($this->isPost()) { // Check if request came as POST method
            $page = (int)$this->getPost('page', 1);// Get current page number (default 1)
            $bookid = $this->getPost('bookid', null); // Get optional search filters from request
            $title = $this->getPost('title', null);
            $category = $this->getPost('category', null);

            if (!empty($bookid) || !empty($title) || !empty($category)) { // If user entered any search filter → search borrow history
                $bookData = $this->borrowHistoryModel->searchBorrowBooks($id, $bookid, $title, $category, $page, $resultsPerPage);
            } else {// Otherwise → load all borrowed books
                $bookData = $this->borrowHistoryModel->getBorrowBooks($id, $page, $resultsPerPage);
            }

            $books = $bookData['results'] ?? []; // Extract results and total count
            $total = $bookData['total'] ?? 0;
            $totalPages = ceil($total / $resultsPerPage);// Calculate how many pages are needed

            $this->jsonResponse([  // Send back data as JSON response (to frontend)
                "books" => $books,
                "total" => $total,
                "totalPages" => $totalPages,
                "currentPage" => $page
            ]);
        } else { // If request was not POST → log warning and return error message
            Logger::warning("Invalid request method for loadBorrowBooks()");
            $this->jsonResponse([
                "message" => "Invalid request."
            ], false);
        }
    }
}
