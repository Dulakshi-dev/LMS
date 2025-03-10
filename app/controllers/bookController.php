<?php
require_once __DIR__ . '/../../main.php';

class BookController
{

    private $bookModel;

    public function __construct()
    {
        require_once Config::getModelPath('bookmodel.php');
        $this->bookModel = new BookModel();
    }

    public function getAllBooks()
    {
        $page = isset($_GET['page']) ? (int)$_GET['page'] : 1; 
        $data = BookModel::getAllBooks($page);

        $totalBooks = $data['total']; 
        $booksResult = $data['results']; 

        $books = [];
        while ($row = $booksResult->fetch_assoc()) {
            $books[] = $row;
        }

        $resultsPerPage = 10;
        $totalPages = ceil($totalBooks / $resultsPerPage); 

        require_once Config::getViewPath("staff", 'view-books.php');
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
                    "description" => $bookData['description'],
                    "category_id" => $bookData['category_id'],
                    "language_id" => $bookData['language_id']

                ]);
            } else {
                echo json_encode(["success" => false, "message" => "User not found."]);
            }
        } else {
            echo json_encode(["success" => false, "message" => "Invalid request."]);
        }
    }


    public static function getAllCategories()
    {
        $categories = BookModel::getAllCategories();

        if ($categories) {
            echo json_encode([
                'success' => true,
                'categories' => $categories,
            ]);
        } else {
            echo json_encode([
                'success' => false,
                'message' => 'No categories found',
            ]);
        }
    }

    public static function getLanguages()
    {
        $languages = BookModel::getLanguages();

        if ($languages) {
            echo json_encode([
                'success' => true,
                'languages' => $languages,
            ]);
        } else {
            echo json_encode([
                'success' => false,
                'message' => 'No languages found',
            ]);
        }
    }

    public function updateBookDetails()
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $book_id = $_POST['book_id'];
            $isbn = $_POST['isbn'];
            $title = $_POST["title"];
            $author = $_POST["author"];
            $category = (int)$_POST["category_id"];
            $language = (int)$_POST["language_id"];
            $pubYear = $_POST["pub_year"];
            $quantity = $_POST["quantity"];
            $description = $_POST["description"];

            $result = BookModel::updateBookDetails($book_id, $isbn, $title, $author, $category,$language, $pubYear, $quantity, $description);

            if ($result) {
                echo json_encode(["success" => true, "message" => "User updated successfully."]);
            } else {
                echo json_encode(["success" => false, "message" => "User not found."]);
            }
        } else {
            echo json_encode(["success" => false, "message" => "Invalid request."]);
        }
    }

    public function addBookData()
    {
        

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $isbn = $_POST['isbn'];
            $author = $_POST['author'];
            $title = $_POST['title'];
            $category = (int) $_POST['category']; 
            $language = (int) $_POST['language'];
            $pub = $_POST['pub'];
            $qty = $_POST['qty'];
            $des = $_POST['des'];
            $fileName = '';

            if (isset($_FILES['coverpage']) && $_FILES['coverpage']['error'] === UPLOAD_ERR_OK) {
                $receipt = $_FILES['coverpage'];
                $targetDir = Config::getBookCoverPath();  // Ensure this directory exists
                $fileName = uniqid() . "_" . basename($receipt["name"]);
                $targetFilePath = $targetDir . $fileName;

                if (move_uploaded_file($receipt["tmp_name"], $targetFilePath)) {
                    echo "File uploaded successfully to: $targetFilePath<br>";
                } else {
                    echo "Error moving uploaded file.<br>";
                    exit();
                }
            } else {
                echo "No file uploaded or upload error.<br>";
            }

            $result = BookModel::addBook($isbn, $author, $title, $category, $language, $pub, $qty, $des, $fileName);

            if ($result) {
                header("Location: index.php?action=bookmanagement");
            } else {
                echo "Failed to add book data.";
            }
        } else {
            echo "Invalid request method.";
        }
    }


    public function serveBookCover()
    {
        $imageName = $_GET['image'] ?? '';

        $basePath = Config::getBookCoverPath();;
        $filePath = realpath($basePath . basename($imageName));

        if ($filePath && strpos($filePath, realpath($basePath)) === 0 && file_exists($filePath)) {
            header('Content-Type: ' . mime_content_type($filePath));
            readfile($filePath);
            exit;
        }

        http_response_code(404);
        echo "Image not found.";
        exit;
    }


    public function searchBooks()
    {
        $books = [];

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            // Retrieve input from the POST request
            $bookid = $_POST['bid'] ?? null;
            $title = $_POST['title'] ?? null;
            $isbn = $_POST['isbn'] ?? null;

            if (empty($title) && empty($isbn) && empty($bookid)) {
                $books = BookModel::getAllBooks(1);
                require_once Config::getViewPath("staff", 'view-books.php');
            } else {
                $books =  BookModel::searchBooks($title, $isbn, $bookid);
                require_once Config::getViewPath("staff", 'view-books.php');
            }
        } else {
            return []; 
        }
    }
}
