<?php
require_once __DIR__ . '/../../../main.php';

class BookController extends Controller
{
    private $bookModel;

    public function __construct()
    {
        require_once Config::getModelPath('member', 'bookmodel.php');
        $this->bookModel = new BookModel();
    }

    public function getAllBooks()
    {
        $resultsPerPage = 10;

        if ($this->isPost()) {
            $page = (int)$this->getPost('page', 1);
            $title = $this->getPost('title');
            $category_id = $this->getPost('category');
            $language_id = $this->getPost('language');

            if (!empty($title) || !empty($category_id) || !empty($language_id)) {
                $bookData = $this->bookModel->searchBooks($title, $category_id, $language_id, $page, $resultsPerPage);
            } else {
                $bookData = $this->bookModel->getAllBooks($page, $resultsPerPage);
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

    public function getDashboardBooks()
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
            $randomBooksData = $this->bookModel->getRandomBooks(6); // Get 6 random books
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
    
        // Return as JSON response
        $this->jsonResponse([
            "books" => $data
        ]);
    }
    
    public function serveBookCover()
    {
        $imageName = $this->getGet('image');

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

    public function getAllCategories()
    {
        $categories = $this->bookModel->getAllCategories();

        if ($categories) {
            $this->jsonResponse([
                'categories' => $categories,
            ]);
        } else {
            $this->jsonResponse([
                'message' => 'No categories found',
            ], false);
        }
    }

    public function getLanguages()
    {
        $languages = $this->bookModel->getLanguages();

        if ($languages) {
            $this->jsonResponse([
                'languages' => $languages,
            ]);
        } else {
            $this->jsonResponse([
                'message' => 'No languages found',
            ], false);
        }
    }


    public function getSearchResult()
    {
        if ($this->isPost()) {
            $title = $this->getPost('title');

            $bookData = $this->bookModel->searchBookByTitle($title);

            $books = $bookData['results'] ?? [];

            $this->jsonResponse([
                "books" => $books
            ]);
        } else {
            $this->jsonResponse(["message" => "Invalid request."], false);
        }
    }
}
