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
        require_once Config::getViewPath("member", 'view-all-books.php');
    }
    

    public function getDashboardBooks()
    {
        $data = MemberDashboardModel::getRecommendedBooks();
        $recBooksResult = $data['results']; 

        $booksrec = [];
        while ($row = $recBooksResult->fetch_assoc()) {
            $booksrec[] = $row;
        }

        $newArrivalData = MemberDashboardModel::getLatestArrivalBooks();
        $newArrivalResult = $newArrivalData['results']; 

        $latestbooks = [];
        while ($row = $newArrivalResult->fetch_assoc()) {
            $latestbooks[] = $row;
        }

        $topData = MemberDashboardModel::getTopBooks();
        $topResult = $topData['results']; 
        $topbooks = [];
        while ($row = $topResult->fetch_assoc()) {
            $topbooks[] = $row;
        }


        require_once Config::getViewPath("member", 'dashboard.php');


    }
}