<?php
require_once __DIR__ . '/../../main.php';

class MemberDashboardController
{

    private $memberDashboardModel;

    public function __construct()
    {
        require_once Config::getModelPath('memberdashboardmodel.php');
        $this->memberDashboardModel = new MemberDashboardModel();
    }

    public function getAllBooks()
    {
        $page = isset($_GET['page']) ? (int)$_GET['page'] : 1; 
        $data = MemberDashboardModel::getAllBooks($page);
    
        $totalBooks = $data['total']; 
        $booksResult = $data['results']; 
    
        $books = [];
        while ($row = $booksResult->fetch_assoc()) {
            $books[] = $row;
        }
    
        $resultsPerPage = 10;
        $totalPages = ceil($totalBooks / $resultsPerPage);
    
        // Fetch recommended books
        $recData = MemberDashboardModel::getRecommendedBooks();
    
        $totalRecBooks = $recData['total']; 
        $recBooksResult = $recData['results']; 
    
        $booksrec = [];
        while ($row = $recBooksResult->fetch_assoc()) {
            $booksrec[] = $row;
        }
    
        require_once Config::getViewPath("member", 'dashboard.php');
    }
    

    public function getRecommendedBooks()
    {
        $data = MemberDashboardModel::getRecommendedBooks();

        $totalBooks = $data['total']; 
        $booksResult = $data['results']; 

        $booksrec = [];
        while ($row = $booksResult->fetch_assoc()) {
            $booksrec[] = $row;
        }

        $resultsPerPage = 10;
        $totalPages = ceil($totalBooks / $resultsPerPage); 
        require_once Config::getViewPath("member", 'dashboard.php');
    }
}