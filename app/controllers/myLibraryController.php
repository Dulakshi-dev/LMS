<?php
require_once __DIR__ . '/../../main.php';

class MyLibraryController
{

    private $myLibraryModel;

    public function __construct()
    {
        require_once Config::getModelPath('mylibrarymodel.php');
        $this->myLibraryModel = new MyLibraryModel();
    }

    public function loadSavedBooks()
    {
        $id = $_SESSION["member"]["id"] ?? '';

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
            echo json_encode(["success" => false, "message" => "Invalid request."]);
        }
    }

    public function saveBook()
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            // Retrieve the data from the request
            $book_id = $_POST['book_id'];
            $id = $_SESSION["member"]["id"];

            // call model function to saveBook
            $result = MyLibraryModel::saveBook($book_id, $id);

            if ($result) {
                // If the book was successfully added, send a success response
                echo json_encode(["success" => true, "message" => "Book Added to Your Library!"]);
            } else {
                // If the book is already saved, inform the user  
                echo json_encode(["success" => false, "message" => "Book Already Added to Your Library!"]);
            }
        } else {
            // Handle invalid request methods  
            echo json_encode(["success" => false, "message" => "Invalid Request"]);
        }
    }


    public function unSaveBook()
    {
        if ($_SERVER['REQUEST_METHOD'] === 'GET') {
            $book_id = $_GET['book_id'];
            $id = $_SESSION["member"]["id"];

            $result = MyLibraryModel::unSaveBook($book_id, $id);

            if ($result) {
                echo json_encode(["success" => true, "message" => "Book Removed From Your Library"]);
            } else {
                echo json_encode(["success" => false, "message" => "Something Wend Wrong."]);
            }
        } else {
            echo json_encode(["success" => false, "message" => "Invalid Request"]);
        }
    }
}
