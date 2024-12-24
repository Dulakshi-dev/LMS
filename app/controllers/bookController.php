<?php
require_once __DIR__ . '/../../main.php';

class BookController{

    private $bookModel;

    public function __construct()
    {
        require_once Config::getModelPath('bookmodel.php');
        $this->bookModel = new BookModel();
    }

    public function getAllBooks()
    {
        $books = [];
        // Retrieve all users from the model
        $books  = BookModel::getAllBooks();
        require_once Config::getViewPath("staff", 'view-books.php');

    }

    public function showBookManagement(){
        require_once Config::getViewPath("staff", 'book-management.php');

    }

    public function showAddBook(){
        require_once Config::getViewPath("staff", 'add-book.php');

    }
    
    public function loadBookDetails()
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $book_id = $_POST['book_id'];

            $result = BookModel::loadBookDetails($book_id);

            if ($result) {
                $bookData = $result->fetch_assoc();
                echo json_encode([
                    "success" => true,
                    "book_id" => $bookData['book_id'],
                    "isbn" => $bookData['isbn'],
                    "author" => $bookData['author'],
                    "title" => $bookData['title'],
                    "pub_year" => $bookData['pub_year'],
                    "qty" => $bookData['qty'],
                    "description" => $bookData['description']
                ]);
            } else {
                echo json_encode(["success" => false, "message" => "User not found."]);
            }
        } else {
            echo json_encode(["success" => false, "message" => "Invalid request."]);
        }
    }

    public function updateBookDetails()
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $book_id = $_POST['book_id'];
            $isbn = $_POST['isbn'];
            $title = $_POST["title"];
            $author = $_POST["author"];
            $category = $_POST["category_id"];
            $pubYear = $_POST["pub_year"];
            $quantity = $_POST["quantity"];
            $description = $_POST["description"];

            $result = BookModel::updateBookDetails($book_id, $isbn, $title, $author, $category, $pubYear,$quantity, $description);

            if ($result) {
                echo json_encode(["success" => true, "message" => "User updated successfully."]);
            } else {
                echo json_encode(["success" => false, "message" => "User not found."]);
            }
        } else {
            echo json_encode(["success" => false, "message" => "Invalid request."]);
        }
    }

    public function addBookData(){
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $isbn = $_POST['isbn'];
            $author = $_POST['author'];
            $title = $_POST['title'];
            $category = $_POST['category'];
            $pub = $_POST['pub'];
            $qty = $_POST['qty'];
            $des = $_POST['des'];

            $result = BookModel::addBook($isbn, $author,$title,$category,$pub,$qty,$des); 

            if($result){
                require_once Config::getViewPath("staff","book-management.php");
            }

            } else {

                $error = "error";

            
        }
    }

    public function searchBooks()
    {
        $books = [];

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            // Retrieve input from the POST request
            $title = $_POST['title'] ?? null;
            $isbn = $_POST['isbn'] ?? null;

            if (empty($title) && empty($isbn)) {
                $books = BookModel::getAllBooks();
                require_once Config::getViewPath("staff", 'view-books.php');

            } else {
                $books =  BookModel::searchBooks($title, $isbn);
                require_once Config::getViewPath("staff", 'view-books.php');

            }

        } else {
            return []; // Return an empty array or an appropriate error response
        }
    }

    


}