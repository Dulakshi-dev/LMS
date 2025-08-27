<?php
require_once __DIR__ . '/../../../main.php';

// BookController handles book-related actions (getting books, categories, languages, images, etc.)
class BookController extends Controller
{
    private $bookModel;

    public function __construct()
    {
        require_once Config::getModelPath('member', 'bookmodel.php'); // Load the BookModel (handles database queries for books)
        $this->bookModel = new BookModel();
    }

    // Function to get all books (with optional search filters and pagination)
    public function getAllBooks()
    {
        $resultsPerPage = 10; // Show 10 books per page

        if ($this->isPost()) { // Only works if request method is POST
            $page = (int)$this->getPost('page', 1);
            $title = $this->getPost('title');
            $category_id = $this->getPost('category');
            $language_id = $this->getPost('language');

            // If search filters are given (title/category/language), search by them
            if (!empty($title) || !empty($category_id) || !empty($language_id)) {
                $bookData = $this->bookModel->searchBooks($title, $category_id, $language_id, $page, $resultsPerPage);
            } else {
                $bookData = $this->bookModel->getAllBooks($page, $resultsPerPage); // Otherwise, get all books
            }

            $books = $bookData['results'] ?? [];  // Extract results
            $total = $bookData['total'] ?? 0;
            $totalPages = ceil($total / $resultsPerPage);

            $this->jsonResponse([  // Send data back as JSON
                "books" => $books,
                "total" => $total,
                "totalPages" => $totalPages,
                "currentPage" => $page
            ]);
        } else { // If request is not POST, log warning and return error
            Logger::warning("Invalid request method for getAllBooks()");
            $this->jsonResponse(["message" => "Invalid request."], false);
        }
    }

    public function getDashboardBooks()// Function to get books for dashboard (recommended, latest, top)
    {
        $data = [];

        // Fetch Recommended Books
        $recBooksData = $this->bookModel->getRecommendedBooks();
        $recBooksResult = $recBooksData['results'];
        $booksrec = [];
        while ($row = $recBooksResult->fetch_assoc()) {
            $booksrec[] = $row;
        }

        // If no recommended books, load random books
        if (empty($booksrec)) {
            $randomBooksData = $this->bookModel->getRandomBooks(4); // Get 4 random books
            $randomBooksResult = $randomBooksData['results'];
            while ($row = $randomBooksResult->fetch_assoc()) {
                $booksrec[] = $row;
            }
        }

        $data['recommended'] = $booksrec;

        // Fetch Latest Arrival Books
        $newArrivalData = $this->bookModel->getLatestArrivalBooks();
        $newArrivalResult = $newArrivalData['results'];
        $latestbooks = [];
        while ($row = $newArrivalResult->fetch_assoc()) {
            $latestbooks[] = $row;
        }
        $data['latest'] = $latestbooks;

        // Fetch Top Books
        $topData = $this->bookModel->getTopBooks();
        $topResult = $topData['results'];
        $topbooks = [];
        while ($row = $topResult->fetch_assoc()) {
            $topbooks[] = $row;
        }
        $data['top'] = $topbooks;

        // Return as JSON response(Send all dashboard books data as JSON)
        $this->jsonResponse([
            "books" => $data
        ]);
    }

        // Function to serve book cover image file
    public function serveBookCover()
    {
        $imageName = $this->getGet('image');// Get image name from URL parameter

        $basePath = Config::getBookCoverPath(); // Base folder where images are stored
        $filePath = realpath($basePath . basename($imageName));// Full path to file
        // Check if file exists inside correct folder
        if ($filePath && strpos($filePath, realpath($basePath)) === 0 && file_exists($filePath)) {
            header('Content-Type: ' . mime_content_type($filePath)); // Set correct content type
            readfile($filePath); // Output the image
            exit;
        }
        // If not found, log warning and return 404
        Logger::warning("Book cover image not found", ['imageName' => $imageName]);
        http_response_code(404);
        echo "Image not found.";
        exit;
    }
    // Function to get all book categories
    public function getAllCategories()
    {

        $categories = $this->bookModel->getAllCategories();

        if ($categories) {
            $this->jsonResponse([       // If categories found, return them
                'categories' => $categories,
            ]);
        } else {    // If no categories found, log warning and return error
            Logger::warning("No categories found");
            $this->jsonResponse([
                'message' => 'No categories found',
            ], false);
        }
    }
    // Function to get all languages of books
    public function getLanguages()
    {

        $languages = $this->bookModel->getLanguages();

        if ($languages) {  // If languages found, return them
            $this->jsonResponse([
                'languages' => $languages,
            ]);
        } else { // If no languages found, log warning and return error
            Logger::warning("No languages found");
            $this->jsonResponse([
                'message' => 'No languages found',
            ], false);
        }
    }

    public function getSearchResult() // Function to search book by title
    {

        if ($this->isPost()) {  // Works only with POST request
            $title = $this->getPost('title');

            $bookData = $this->bookModel->searchBookByTitle($title);
            $books = $bookData['results'] ?? [];
            
            $this->jsonResponse([ // Return matching books as JSON
                "books" => $books
            ]);
        } else {  // If not POST, log warning and return error
            Logger::warning("Invalid request method for getSearchResult()");
            $this->jsonResponse(["message" => "Invalid request."], false);
        }
    }
}
