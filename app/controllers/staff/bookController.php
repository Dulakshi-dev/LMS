<?php
require_once __DIR__ . '/../../../main.php';

class BookController extends Controller 
{
    private $bookModel;

    public function __construct()
    {
        require_once Config::getModelPath('staff', 'bookmodel.php');
        $this->bookModel = new BookModel();
    }

    public function getAllBooks()
    {
        $resultsPerPage = 10;

        if ($this->isPost()) {
            $page = $this->getPost('page', 1);
            $bookid = $this->getPost('bookid');
            $title = $this->getPost('title');
            $isbn = $this->getPost('isbn');
            $status = $this->getPost('status', 'Active');

            if (!empty($bookid) || !empty($title) || !empty($isbn)) {
                $bookData = BookModel::searchBooks($bookid, $title, $isbn, $status, $page, $resultsPerPage);
            } else {
                $bookData = BookModel::getAllBooks($page, $resultsPerPage, $status);
            }

            $books = $bookData['results'] ?? [];
            $total = $bookData['total'] ?? 0;
            $totalPages = ceil($total / $resultsPerPage);

            $this->jsonResponse([
                "books" => $books,
                "total" => $total,
                "totalPages" => $totalPages,
                "currentPage" => $page
            ]);
        } else {
            $this->jsonResponse(["message" => "Invalid request."], false);
        }
    }

    public function loadBookDetails()
    {
        if ($this->isPost()) {
            $book_id = $this->getPost('book_id');

            $result = BookModel::loadBookDetails($book_id);

            if ($result) {
                $bookData = $result->fetch_assoc();
                $this->jsonResponse([
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
                $this->jsonResponse(["message" => "Book not found."], false);
            }
        } else {
            $this->jsonResponse(["message" => "Invalid request."], false);
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
        if ($this->isPost()) {
            $book_id = $this->getPost('book_id');
            $isbn = $this->getPost('isbn');
            $title = $this->getPost('title');
            $author = $this->getPost('author');
            $category = (int)$this->getPost('category_id');
            $language = (int)$this->getPost('language_id');
            $pubYear = $this->getPost('pub_year');
            $quantity = $this->getPost('quantity');
            $description = $this->getPost('description');

            $result = BookModel::updateBookDetails($book_id, $isbn, $title, $author, $category, $language, $pubYear, $quantity, $description);

            if ($result) {
                $this->jsonResponse(["message" => "Book updated successfully."]);
            } else {
                $this->jsonResponse(["message" => "Book not found."], false);
            }
        } else {
            $this->jsonResponse(["message" => "Invalid request."], false);
        }
    }

    public function addBookData()
    {
        if ($this->isPost()) {
            $isbn = $this->getPost('isbn');
            $author = $this->getPost('author');
            $title = $this->getPost('title');
            $category = (int) $this->getPost('category');
            $language = (int) $this->getPost('language');
            $pub = $this->getPost('pub');
            $qty = $this->getPost('qty');
            $des = $this->getPost('des');
            $fileName = ''; // Default file name (in case no image is uploaded)

            // Check if a cover page is uploaded
            $receipt = $_FILES['coverpage'];
            $targetDir = Config::getBookCoverPath(); // Get the directory path for book covers

            // Generate a unique file name to avoid conflicts
            $fileName = uniqid() . "_" . basename($receipt["name"]);
            $targetFilePath = $targetDir . $fileName;

            // Move the uploaded file to the target directory
            move_uploaded_file($receipt["tmp_name"], $targetFilePath);

            // Call the model function to insert the book into the database
            $result = BookModel::addBook($isbn, $author, $title, $category, $language, $pub, $qty, $des, $fileName);

            if ($result) {
                $this->jsonResponse(["message" => "Book added successfully."]);
            } else {
                $this->jsonResponse(["message" => "Failed to add book data."], false);
            }
        } else {
            $this->jsonResponse(["message" => "Invalid Request."], false);
        }
    }

    public function addCategory()
    {
        if ($this->isPost()) {
            $category = $this->sanitize($this->getPost('category'));

            $result = BookModel::addCategory($category);

            if ($result) {
                $this->jsonResponse(["message" => "Category Added."]);
            } else {
                $this->jsonResponse(["message" => "Database insertion failed."], false);
            }
        } else {
            $this->jsonResponse(["message" => "Invalid request method."], false);
        }
    }

    public function serveBookCover()
    {
        $imageName = $_GET['image'] ?? '';

        $basePath = Config::getBookCoverPath();
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
        if ($this->isPost()) {
            $book_id = $this->getPost('book_id');

            $result = BookModel::deactivateBook($book_id);

            if ($result) {
                $this->jsonResponse(["message" => "Book deactivated"]);
            } else {
                $this->jsonResponse(["message" => "Book not found."], false);
            }
        } else {
            $this->jsonResponse(["message" => "Invalid request."], false);
        }
    }

    public function activateBook()
    {
        if ($this->isPost()) {
            $book_id = $this->getPost('book_id');

            $result = BookModel::activateBook($book_id);

            if ($result) {
                $this->jsonResponse(["message" => "Book activated again"]);
            } else {
                $this->jsonResponse(["message" => "Book not found."], false);
            }
        } else {
            $this->jsonResponse(["message" => "Invalid request."], false);
        }
    }

    public function deleteCategory()
    {
        if ($this->isPost()) {
            $category_id = $this->getPost('category_id');

            $result = BookModel::deleteCategory($category_id);

            if ($result) {
                $this->jsonResponse(["message" => "Category deleted"]);
            } else {
                $this->jsonResponse(["message" => "Category not found."], false);
            }
        } else {
            $this->jsonResponse(["message" => "Invalid request."], false);
        }
    }

}
