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
        $page = isset($_GET['page']) ? (int)$_GET['page'] : 1; 
        $data = BorrowHistoryModel::getBorrowBooks($page, $id);

        $totalBooks = $data['total']; 
        $booksResult = $data['results']; 

        $books = [];
        while ($row = $booksResult->fetch_assoc()) {
            $books[] = $row;
        }

        $resultsPerPage = 10;
        $totalPages = ceil($totalBooks / $resultsPerPage); 
        require_once Config::getViewPath("member", 'borrow-history.php');
    }

}