<?php
require_once __DIR__ . '/../../main.php';

class MemberBookController
{

    private $memberBookModel;

    public function __construct()
    {
        require_once Config::getModelPath('memberbookmodel.php');
        $this->memberBookModel = new MemberBookModel();
    }

    public function getAllBooks()
    {
        $page = isset($_GET['page']) ? (int)$_GET['page'] : 1; 
        $data = MemberBookModel::getAllBooks($page);

        $totalBooks = $data['total']; 
        $booksResult = $data['results']; 

        $books = [];
        while ($row = $booksResult->fetch_assoc()) {
            $books[] = $row;
        }

        $resultsPerPage = 10;
        $totalPages = ceil($totalBooks / $resultsPerPage); 
        require_once Config::getViewPath("member", 'dashboard.php');

    }
}