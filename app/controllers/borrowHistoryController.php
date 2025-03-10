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
        $resultsPerPage = 10; 
    
        $data = BorrowHistoryModel::getBorrowBooks($page, $id, $resultsPerPage);
        
        $totalBooks = $data['total'];
        $books = $data['results']; 
        $totalPages = ceil($totalBooks / $resultsPerPage);
    
        require_once Config::getViewPath("member", 'borrow-history.php');
    }
    
    

}