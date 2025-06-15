<?php
require_once __DIR__ . '/../../../main.php';

class BorrowHistoryController extends Controller
{
    private $borrowHistoryModel;

    public function __construct()
    {
        require_once Config::getModelPath('member', 'borrowhistorymodel.php');
        $this->borrowHistoryModel = new BorrowHistoryModel();
    }

    public function loadBorrowBooks()
    {

        $id = $_SESSION["member"]["id"] ?? '';

        $resultsPerPage = 10;

        if ($this->isPost()) {
            $page = (int)$this->getPost('page', 1);
            $bookid = $this->getPost('bookid', null);
            $title = $this->getPost('title', null);
            $category = $this->getPost('category', null);

            if (!empty($bookid) || !empty($title) || !empty($category)) {
                $bookData = $this->borrowHistoryModel->searchBorrowBooks($id, $bookid, $title, $category, $page, $resultsPerPage);
            } else {
                $bookData = $this->borrowHistoryModel->getBorrowBooks($id, $page, $resultsPerPage);
            }

            $books = $bookData['results'] ?? [];
            $total = $bookData['total'] ?? 0;
            $totalPages = ceil($total / $resultsPerPage);

            $this->jsonResponse([
                "books" => $books,
                "total" => $total,
                "totalPages" => $totalPages,
                "currentPage" => $page
            ]);
        } else {
            Logger::warning("Invalid request method for loadBorrowBooks()");
            $this->jsonResponse([
                "message" => "Invalid request."
            ], false);
        }
    }
}
