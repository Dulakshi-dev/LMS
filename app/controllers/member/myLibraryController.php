<?php

require_once __DIR__ . '/../../../main.php';

class MyLibraryController extends Controller
{
    private $myLibraryModel;

    public function __construct()
    {   // Load the model file for MyLibrary (handles database operations for saved books)
        require_once Config::getModelPath('member','mylibrarymodel.php');
        $this->myLibraryModel = new MyLibraryModel();
    }

    public function loadSavedBooks() // Function to load all saved books of a logged-in user
    {

        $id = $_SESSION["member"]["id"] ?? null; // Get user ID from session (if not logged in, return error)
        if (!$id) {
            Logger::warning("loadSavedBooks failed: No member ID in session");
            echo json_encode(["success" => false, "message" => "User not logged in."]);
            return;
        }

        $resultsPerPage = 10;  // Number of results per page

        if ($_SERVER['REQUEST_METHOD'] === 'POST') { // Only allow POST requests for this function
            $page = isset($_POST['page']) ? (int)$_POST['page'] : 1; // Get page number and search filters from request
            $bookid = $_POST['bookid'] ?? null;
            $title = $_POST['title'] ?? null;
            $category = $_POST['category'] ?? null;
            // If filters are provided, search books, otherwise load all saved books
            if (!empty($bookid) || !empty($title) || !empty($category)) {
                $bookData = MyLibraryModel::searchSavedBooks($id, $bookid, $title, $category, $page, $resultsPerPage);
            } else {
                $bookData = MyLibraryModel::getSavedBooks($id, $page, $resultsPerPage);
            }
            // Extract books and calculate total pages
            $books = $bookData['results'] ?? [];
            $total = $bookData['total'] ?? 0;
            $totalPages = ceil($total / $resultsPerPage);

            echo json_encode([ // Send response back as JSON
                "success" => true,
                "books" => $books,
                "total" => $total,
                "totalPages" => $totalPages,
                "currentPage" => $page
            ]);
        } else { // If request is not POST, return error
            Logger::warning("Invalid request method for loadSavedBooks");
            echo json_encode(["success" => false, "message" => "Invalid request."]);
        }
    }

    public function saveBook() // Function to save a book into user’s library
    { 
        if ($_SERVER['REQUEST_METHOD'] === 'POST') { // Only allow POST requests
            $book_id = $_POST['book_id'] ?? null;
            $id = $_SESSION["member"]["id"] ?? null;

            if (!$id) {  // Check if user is logged in
                Logger::warning("saveBook failed: No member ID in session");
                echo json_encode(["success" => false, "message" => "User not logged in."]);
                return;
            }

            if (!$book_id) { // Check if book ID is provided
                Logger::warning("saveBook failed: No book_id provided");
                echo json_encode(["success" => false, "message" => "No book ID provided."]);
                return;
            }
            // Call model function to save boo
            $result = MyLibraryModel::saveBook($book_id, $id);

            if ($result) {// If book is saved successfully
                Logger::info("Book saved successfully", ['member_id' => $id, 'book_id' => $book_id]);
                echo json_encode(["success" => true, "message" => "Book Added to Your Library!"]);
            } else { // If book already exists in library
                echo json_encode(["success" => false, "message" => "Book Already Added to Your Library!"]);
            }
        } else { // If request is not POST, return error
            Logger::warning("Invalid request method for saveBook");
            echo json_encode(["success" => false, "message" => "Invalid Request"]);
        }
    }
     // Function to remove (unsave) a book from user’s library
    public function unSaveBook()
    {
        // Only allow GET requests
        if ($_SERVER['REQUEST_METHOD'] === 'GET') {
            $book_id = $_GET['book_id'] ?? null;
            $id = $_SESSION["member"]["id"] ?? null;

            if (!$id) { // Check if user is logged in
                Logger::warning("unSaveBook failed: No member ID in session");
                echo json_encode(["success" => false, "message" => "User not logged in."]);
                return;
            }

            if (!$book_id) {  // Check if book ID is provided
                Logger::warning("unSaveBook failed: No book_id provided");
                echo json_encode(["success" => false, "message" => "No book ID provided."]);
                return;
            }
            // Call model function to remove book
            $result = MyLibraryModel::unSaveBook($book_id, $id);

            if ($result) { // If book removed successfully
                Logger::info("Book unsaved", ['member_id' => $id, 'book_id' => $book_id]);
                echo json_encode(["success" => true, "message" => "Book Removed From Your Library"]);
            } else { // If something went wrong
                Logger::error("Failed to remove book from library");
                echo json_encode(["success" => false, "message" => "Something Went Wrong."]);
            }
        } else { // If request is not GET, return error
            Logger::warning("Invalid request method for unSaveBook");
            echo json_encode(["success" => false, "message" => "Invalid Request"]);
        }
    }
}
