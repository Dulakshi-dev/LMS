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
            $category_id = $this->getPost('category');
            $language_id = $this->getPost('language');
            $status = $this->getPost('status', 'Active');

            if (!empty($bookid) || !empty($title) || !empty($isbn) || !empty($category_id) || !empty($language_id)) {
                $bookData = BookModel::searchBooks($bookid, $title, $isbn, $category_id, $language_id, $status, $page, $resultsPerPage);
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
            Logger::warning("getAllBooks called with invalid request method");
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
                Logger::warning("loadBookDetails failed - book not found", ['book_id' => $book_id]);
                $this->jsonResponse(["message" => "Book not found."], false);
            }
        } else {
            Logger::warning("loadBookDetails called with invalid request method");
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
            Logger::warning("getAllCategories failed - no categories found");
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
            Logger::warning("getLanguages failed - no languages found");
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

            Logger::info("Updating book details", [
                'book_id' => $book_id,
                'isbn' => $isbn,
                'title' => $title,
                'author' => $author,
                'category' => $category,
                'language' => $language,
                'pub_year' => $pubYear,
                'quantity' => $quantity
            ]);

            $result = BookModel::updateBookDetails($book_id, $isbn, $title, $author, $category, $language, $pubYear, $quantity, $description);

            if ($result) {
                Logger::info("Book updated successfully", ['book_id' => $book_id]);
                $this->jsonResponse(["message" => "Book updated successfully."]);
            } else {
                Logger::warning("Failed to update book - book not found", ['book_id' => $book_id]);
                $this->jsonResponse(["message" => "Book not found."], false);
            }
        } else {
            Logger::warning("updateBookDetails called with invalid request method");
            $this->jsonResponse(["message" => "Invalid request."], false);
        }
    }

    public function addBookData()
    {
        if ($this->isPost()) {
            $isbn = trim($this->getPost('isbn'));
            $author = trim($this->getPost('author'));
            $title = trim($this->getPost('title'));
            $category = (int) $this->getPost('category');
            $language = (int) $this->getPost('language');
            $pub = trim($this->getPost('pub'));
            $qty = $this->getPost('qty');
            $des = trim($this->getPost('des'));

            Logger::info("Checking ISBN", ['isbn' => $isbn]);

            if (BookModel::isbnExists($isbn)) {
                Logger::warning("Duplicate ISBN attempted", ['isbn' => $isbn]);
                $this->jsonResponse(["message" => "This ISBN already exists."], false);
                return;
            }

            $receipt = $_FILES['coverpage'];
            $targetDir = Config::getBookCoverPath();
            $fileName = uniqid() . "_" . basename($receipt["name"]);
            $targetFilePath = $targetDir . $fileName;

            move_uploaded_file($receipt["tmp_name"], $targetFilePath);

            $result = BookModel::addBook($isbn, $author, $title, $category, $language, $pub, $qty, $des, $fileName);

            if ($result) {
                Logger::info("Book added successfully", ['title' => $title, 'isbn' => $isbn]);
                $this->jsonResponse(["message" => "Book added successfully."]);
            } else {
                Logger::error("Failed to add book", ['isbn' => $isbn]);
                $this->jsonResponse(["message" => "Failed to add book."], false);
            }
        } else {
            Logger::warning("addBookData called with invalid method");
            $this->jsonResponse(["message" => "Invalid Request."], false);
        }
    }


    public function addCategory()
    {
        if ($this->isPost()) {
            $category = $this->sanitize($this->getPost('category'));
            $result = BookModel::addCategory($category);

            if ($result) {
                Logger::info("Category added successfully", ['category' => $category]);
                $this->jsonResponse(["message" => "Category Added."]);
            } else {
                Logger::error("Database insertion failed for category", ['category' => $category]);
                $this->jsonResponse(["message" => "Database insertion failed."], false);
            }
        } else {
            Logger::warning("addCategory called with invalid request method");
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

        Logger::warning("Book cover image not found", ['image' => $imageName]);
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
                Logger::info("Book deactivated", ['book_id' => $book_id]);
                $this->jsonResponse(["message" => "Book deactivated"]);
            } else {
                Logger::warning("Failed to deactivate book - book not found", ['book_id' => $book_id]);
                $this->jsonResponse(["message" => "Book not found."], false);
            }
        } else {
            Logger::warning("deactivateBook called with invalid request method");
            $this->jsonResponse(["message" => "Invalid request."], false);
        }
    }

    public function activateBook()
    {
        if ($this->isPost()) {
            $book_id = $this->getPost('book_id');
            $result = BookModel::activateBook($book_id);

            if ($result) {
                Logger::info("Book activated again", ['book_id' => $book_id]);
                $this->jsonResponse(["message" => "Book activated again"]);
            } else {
                Logger::warning("Failed to activate book - book not found", ['book_id' => $book_id]);
                $this->jsonResponse(["message" => "Book not found."], false);
            }
        } else {
            Logger::warning("activateBook called with invalid request method");
            $this->jsonResponse(["message" => "Invalid request."], false);
        }
    }

    public function deleteCategory()
    {
        if ($this->isPost()) {
            $category_id = $this->getPost('category_id');
            $result = BookModel::deleteCategory($category_id);

            if ($result) {
                Logger::info("Category deleted", ['category_id' => $category_id]);
                $this->jsonResponse(["message" => "Category deleted"]);
            } else {
                Logger::warning("Failed to delete category - not found", ['category_id' => $category_id]);
                $this->jsonResponse(["message" => "Category not found."], false);
            }
        } else {
            Logger::warning("deleteCategory called with invalid request method");
            $this->jsonResponse(["message" => "Invalid request."], false);
        }
    }
}
