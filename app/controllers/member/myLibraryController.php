<?php

require_once __DIR__ . '/../../../main.php';

class MyLibraryController extends Controller
{
    private $myLibraryModel;

    public function __construct()
    {
        require_once Config::getModelPath('member','mylibrarymodel.php');
        $this->myLibraryModel = new MyLibraryModel();
    }

    public function loadSavedBooks()
    {

        $id = $_SESSION["member"]["id"] ?? null;
        if (!$id) {
            Logger::warning("loadSavedBooks failed: No member ID in session");
            echo json_encode(["success" => false, "message" => "User not logged in."]);
            return;
        }

        $resultsPerPage = 10;

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $page = isset($_POST['page']) ? (int)$_POST['page'] : 1;
            $bookid = $_POST['bookid'] ?? null;
            $title = $_POST['title'] ?? null;
            $category = $_POST['category'] ?? null;

            if (!empty($bookid) || !empty($title) || !empty($category)) {
                $bookData = MyLibraryModel::searchSavedBooks($id, $bookid, $title, $category, $page, $resultsPerPage);
            } else {
                $bookData = MyLibraryModel::getSavedBooks($id, $page, $resultsPerPage);
            }

            $books = $bookData['results'] ?? [];
            $total = $bookData['total'] ?? 0;
            $totalPages = ceil($total / $resultsPerPage);

            echo json_encode([
                "success" => true,
                "books" => $books,
                "total" => $total,
                "totalPages" => $totalPages,
                "currentPage" => $page
            ]);
        } else {
            Logger::warning("Invalid request method for loadSavedBooks");
            echo json_encode(["success" => false, "message" => "Invalid request."]);
        }
    }

    public function saveBook()
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $book_id = $_POST['book_id'] ?? null;
            $id = $_SESSION["member"]["id"] ?? null;

            if (!$id) {
                Logger::warning("saveBook failed: No member ID in session");
                echo json_encode(["success" => false, "message" => "User not logged in."]);
                return;
            }

            if (!$book_id) {
                Logger::warning("saveBook failed: No book_id provided");
                echo json_encode(["success" => false, "message" => "No book ID provided."]);
                return;
            }

            $result = MyLibraryModel::saveBook($book_id, $id);

            if ($result) {
                Logger::info("Book saved successfully", ['member_id' => $id, 'book_id' => $book_id]);
                echo json_encode(["success" => true, "message" => "Book Added to Your Library!"]);
            } else {
                echo json_encode(["success" => false, "message" => "Book Already Added to Your Library!"]);
            }
        } else {
            Logger::warning("Invalid request method for saveBook");
            echo json_encode(["success" => false, "message" => "Invalid Request"]);
        }
    }

    public function unSaveBook()
    {

        if ($_SERVER['REQUEST_METHOD'] === 'GET') {
            $book_id = $_GET['book_id'] ?? null;
            $id = $_SESSION["member"]["id"] ?? null;

            if (!$id) {
                Logger::warning("unSaveBook failed: No member ID in session");
                echo json_encode(["success" => false, "message" => "User not logged in."]);
                return;
            }

            if (!$book_id) {
                Logger::warning("unSaveBook failed: No book_id provided");
                echo json_encode(["success" => false, "message" => "No book ID provided."]);
                return;
            }

            $result = MyLibraryModel::unSaveBook($book_id, $id);

            if ($result) {
                Logger::info("Book unsaved", ['member_id' => $id, 'book_id' => $book_id]);
                echo json_encode(["success" => true, "message" => "Book Removed From Your Library"]);
            } else {
                Logger::error("Failed to remove book from library");
                echo json_encode(["success" => false, "message" => "Something Went Wrong."]);
            }
        } else {
            Logger::warning("Invalid request method for unSaveBook");
            echo json_encode(["success" => false, "message" => "Invalid Request"]);
        }
    }
}
