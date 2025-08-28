<?php
// Include the main configuration and base setup file
require_once __DIR__ . '/../../../main.php';

// DashboardController handles staff dashboard related operations
class DashboardController extends Controller
{
    private $dashboardModel;

    public function __construct()
    {
        // Load the DashboardModel file (staff dashboard model)
        require_once Config::getModelPath('staff','dashboardmodel.php');
        $this->dashboardModel = new DashboardModel();
    }

    // This function prepares data for different charts on the dashboard
    public static function getUserChartData()
    {
        try {
            // Get number of user registrations for each month
            $userdata = DashboardModel::getUserRegistrationsPerMonth();
            // Get number of books issued for each month
            $issuebookdata = DashboardModel::getBooksIssuedPerMonth();
            // Get borrowing statistics based on book categories
            $borrowcategorydata = DashboardModel::getBookCategoryBorrowData(); 
            // Get number of members by their status (active/inactive/etc.)
            $membersbystatus = DashboardModel::getMembersByStatus();

            // Combine all the above results into one array
            $combinedData = [
                'userRegistrations' => $userdata,
                'booksIssued' => $issuebookdata,
                'borrowCategory' => $borrowcategorydata,
                'memberstatus' => $membersbystatus
            ];
        
            // Send the combined data as a JSON response
            echo json_encode($combinedData);

        } catch (Exception $e) {
            // Log error if fetching data fails
            Logger::error("Failed to fetch user chart data: " . $e->getMessage());
            // Send error response
            echo json_encode(['error' => 'Failed to fetch user chart data']);
        }
    }

    // This function returns important counts to show in dashboard summary cards
    public static function getDashboardCounts()
    {
        try {
            // Get total number of books in the library
            $bookcount = DashboardModel::getNoOfBooks();
            // Get total number of members
            $membercount = DashboardModel::getNoOfMembers();
            // Get total number of reservations made
            $reservationcount = DashboardModel::getNoOfReservations();
            // Get number of books currently issued
            $issuecount = DashboardModel::getNoOfIssuedBooks();
            // Get total fines collected
            $totalfines = DashboardModel::getAmountOfFines();

            // Put all data into one array
            $data = [
                'bookcount' => $bookcount,
                'membercount' => $membercount,
                'reservationcount' => $reservationcount,
                'issuecount' => $issuecount,
                'finestotal' => $totalfines
            ];
        
            // Return success response with library data
            echo json_encode([
                'success' => true,
                'libraryData' => $data
            ]);

        } catch (Exception $e) {
            // Log error if fetching counts fails
            Logger::error("Failed to fetch dashboard counts: " . $e->getMessage());
            // Send error response
            echo json_encode(['error' => 'Failed to fetch dashboard counts']);
        }
    }

    // This function loads top borrowed books in the library
    public function loadTopBooks()
    {
        // Check if request is POST
        if ($this->isPost()) {

            // Get the result of top books from the model
            $topBooksResult = DashboardModel::getTopBooks();

            // If no books found, return error response
            if (!$topBooksResult) {
                Logger::error("Failed to fetch top books.");
                $this->jsonResponse(["message" => "Failed to fetch books."], false);
                return;
            }

            // Convert database result into an array
            $topbooks = [];
            while ($row = $topBooksResult->fetch_assoc()) {
                $topbooks[] = $row;
            }

            // Return the list of top books as a JSON response
            $this->jsonResponse([
                "books" => $topbooks
            ]);
        } else {
            // If request is not POST, return invalid request response
            Logger::warning("Invalid request method for loading top books.");
            $this->jsonResponse(["message" => "Invalid request method."], false);
        }
    }
}
