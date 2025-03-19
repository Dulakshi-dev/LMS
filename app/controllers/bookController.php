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
        $resultsPerPage = 10;

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $page = isset($_POST['page']) ? (int)$_POST['page'] : 1; 
            $bookid = $_POST['bookid'] ?? null;
            $title = $_POST['title'] ?? null;
            $isbn = $_POST['isbn'] ?? null;

            if (!empty($bookid) || !empty($title) || !empty($isbn)) {
                $bookData = BookModel::searchBooks($bookid, $title, $isbn, $page, $resultsPerPage);
             }else {
                $bookData = BookModel::getAllBooks($page, $resultsPerPage);
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

        }else{
            echo json_encode(["success" => false, "message" => "Invalid request."]);

        }
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
                echo json_encode(["success" => true, "message" => "Book updated successfully."]);
            } else {
                echo json_encode(["success" => false, "message" => "Book not found."]);
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

                $receipt = $_FILES['coverpage'];
                $targetDir = Config::getBookCoverPath();  // Ensure this directory exists
                $fileName = uniqid() . "_" . basename($receipt["name"]);
                $targetFilePath = $targetDir . $fileName;

                move_uploaded_file($receipt["tmp_name"], $targetFilePath);
            

            $result = BookModel::addBook($isbn, $author, $title, $category, $language, $pub, $qty, $des, $fileName);

            if ($result) {
                echo json_encode(["success" => true, "message" => "Book added successfully."]);
            } else {
                echo json_encode(["success" => false, "message" => "Failed to add book data."]);
            }
        } else {
            echo json_encode(["success" => false, "message" => "Invalid Request."]);
        }
    }

    public function addCategory()
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $category = trim($_POST['category']);
    
            $result = BookModel::addCategory($category);
    
            if ($result) {
                echo json_encode(["success" => true, "message" => "Category Added."]);
            } else {
                echo json_encode(["success" => false, "message" => "Database insertion failed."]);
            }
        } else {
            echo json_encode(["success" => false, "message" => "Invalid request method."]);
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

    public function deactivateBook()
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $book_id = $_POST['book_id'];

            $result = BookModel::deactivateBook($book_id);

            if ($result) {
                echo json_encode(["success" => true, "message" => "Book deactivated"]);
            } else {
                echo json_encode(["success" => false, "message" => "Book not found."]);
            }
        } else {
            echo json_encode(["success" => false, "message" => "Invalid request."]);
        }
    }

   
}
