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
        try {
            $userdata = DashboardModel::getUserRegistrationsPerMonth();
            $issuebookdata = DashboardModel::getBooksIssuedPerMonth();
            $borrowcategorydata = DashboardModel::getBookCategoryBorrowData(); 
            $membersbystatus = DashboardModel::getMembersByStatus();

            $combinedData = [
                'userRegistrations' => $userdata,
                'booksIssued' => $issuebookdata,
                'borrowCategory' => $borrowcategorydata,
                'memberstatus' => $membersbystatus
            ];
        
            echo json_encode($combinedData);

        } catch (Exception $e) {
            Logger::error("Failed to fetch user chart data: " . $e->getMessage());
            echo json_encode(['error' => 'Failed to fetch user chart data']);
        }
    }

    public static function getDashboardCounts()
    {
        try {

            $bookcount = DashboardModel::getNoOfBooks();
            $membercount = DashboardModel::getNoOfMembers();
            $reservationcount = DashboardModel::getNoOfReservations();
            $issuecount = DashboardModel::getNoOfIssuedBooks();
            $totalfines = DashboardModel::getAmountOfFines();

            $data = [
                'bookcount' => $bookcount,
                'membercount' => $membercount,
                'reservationcount' => $reservationcount,
                'issuecount' => $issuecount,
                'finestotal' => $totalfines
            ];
        
            echo json_encode([
                'success' => true,
                'libraryData' => $data
            ]);

        } catch (Exception $e) {
            Logger::error("Failed to fetch dashboard counts: " . $e->getMessage());
            echo json_encode(['error' => 'Failed to fetch dashboard counts']);
        }
    }

    public function loadTopBooks()
    {
        if ($this->isPost()) {

            $topBooksResult = DashboardModel::getTopBooks();

            if (!$topBooksResult) {
                Logger::error("Failed to fetch top books.");
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
            Logger::warning("Invalid request method for loading top books.");
            $this->jsonResponse(["message" => "Invalid request method."], false);
        }
    }
}
