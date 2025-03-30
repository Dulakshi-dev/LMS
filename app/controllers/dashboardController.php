<?php
require_once __DIR__ . '/../../main.php';

class DashboardController
{

    private $dashboardModel;

    public function __construct()
    {
        require_once Config::getModelPath('dashboardmodel.php');
        $this->dashboardModel = new DashboardModel();
    }

    public static function getUserChartData()
    {
        $userdata = DashboardModel::getUserRegistrationsPerMonth();
    
        $issuebookdata = DashboardModel::getBooksIssuedPerMonth();
            
        $borrowcategorydata = DashboardModel::getBookCategoryBorrowData(); 
        $membersbystatus = DashboardModel::getMembersByStatus();

        // Combine both datasets into one array
        $combinedData = [
            'userRegistrations' => $userdata, // User registrations data
            'booksIssued' => $issuebookdata,// Books issued data
            'borrowCategory' => $borrowcategorydata,
            'memberstatus' => $membersbystatus

        ];
    
        // Output combined data as JSON
        echo json_encode($combinedData);
    }


    public static function getDshboardCounts()
    {
        // Get counts for books, members, reservations, and issued books
        $bookcount = DashboardModel::getNoOfBooks();
        $membercount = DashboardModel::getNoOfMembers();
        $reservationcount = DashboardModel::getNoOfReservations();
        $issuecount = DashboardModel::getNoOfIssuedBooks();
        $totalfines = DashboardModel::getAmountOfFines();

        // Prepare the data to send as a JSON response
        $data = [
            'bookcount' => $bookcount,
            'membercount' => $membercount,
            'reservationcount' => $reservationcount,
            'issuecount' => $issuecount,
            'finestotal' => $totalfines

        ];
    
        // Return the data as JSON
        echo json_encode([
            'success' => true,
            'libraryData' => $data
        ]);
    }

    public function loadTopBooks()
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $topBooksResult = DashboardModel::getTopBooks();

            if (!$topBooksResult) {
                echo json_encode(["success" => false, "message" => "Failed to fetch books."]);
                return;
            }

            $topbooks = [];
            while ($row = $topBooksResult->fetch_assoc()) {
                $topbooks[] = $row;
            }

            echo json_encode([
                "success" => true,
                "books" => $topbooks
            ]);
        } else {
            echo json_encode(["success" => false, "message" => "Invalid request method."]);
        }
    }
    
}