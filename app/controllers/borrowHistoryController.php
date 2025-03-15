<?php
require_once __DIR__ . '/../../main.php';

class BorrowHistoryController
{

    private $borrowHistoryModel;

    public function __construct()
    {
        require_once Config::getModelPath('borrowhistorymodel.php');
        $this->borrowHistoryModel = new BorrowHistoryModel();
    }

    public function loadBorrowBooks()
    {
        $id = $_SESSION["member"]["id"] ?? '';

        $resultsPerPage = 10;

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $page = isset($_POST['page']) ? (int)$_POST['page'] : 1; 
            $bookid = $_POST['bookid'] ?? null;
            $title = $_POST['title'] ?? null;
            $category = $_POST['category'] ?? null;

            if (!empty($bookid) || !empty($title) || !empty($category)) {
                $bookData = BorrowHistoryModel::searchBorrowBooks($id, $bookid, $title, $category, $page, $resultsPerPage);
             }else {
                $bookData = BorrowHistoryModel::getBorrowBooks($id, $page, $resultsPerPage);
            }

            $books = $bookData['results'] ?? [];
            $total = $bookData['total'] ?? 0;
            $totalPages = ceil($total / $resultsPerPage);
        
            echo json_encode([
                "success" => true,
                "books" => $books,
                "total" => $total,
                "totalPages" => $totalPages,
                "currentPage" => $page
            ]);

        }else{
            echo json_encode(["success" => false, "message" => "Invalid request."]);

        }
    }

    
    

}