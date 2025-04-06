<?php
require_once __DIR__ . '/../../../main.php';

class DashboardController extends Controller
{
    private $dashboardModel;

    public function __construct()
    {
        require_once Config::getModelPath('staff','dashboardmodel.php');
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
            'userRegistrations' => $userdata,
            'booksIssued' => $issuebookdata,
            'borrowCategory' => $borrowcategorydata,
            'memberstatus' => $membersbystatus
        ];
    
        // Output combined data as JSON
        echo json_encode($combinedData);
    }

    public static function getDashboardCounts()
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
        // Check if it's a POST request
        if ($this->isPost()) {
            // Retrieve top books
            $topBooksResult = DashboardModel::getTopBooks();

            if (!$topBooksResult) {
                $this->jsonResponse(["message" => "Failed to fetch books."], false);
                return;
            }

            $topbooks = [];
            while ($row = $topBooksResult->fetch_assoc()) {
                $topbooks[] = $row;
            }

            $this->jsonResponse([
                "books" => $topbooks
            ]);
        } else {
            $this->jsonResponse(["message" => "Invalid request method."], false);
        }
    }
}
