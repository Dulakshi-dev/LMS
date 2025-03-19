<?php
require_once __DIR__ . '/../../main.php';

class MemberBookController
{

    private $memberBookModel;

    public function __construct()
    {
        require_once Config::getModelPath('memberbookmodel.php');
        $this->memberBookModel = new MemberBookModel();
    }

    public function getAllBooks()
    {
        $resultsPerPage = 10;
    
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $page = isset($_POST['page']) ? (int)$_POST['page'] : 1; 
            $title = $_POST['title'] ?? null;
            $category_id = $_POST['category'] ?? null;
            $language_id = $_POST['language'] ?? null;
    
            if (!empty($title) || !empty($category_id) || !empty($language_id)) {
                $bookData = MemberBookModel::searchBooks($title, $category_id, $language_id, $page, $resultsPerPage);
            } else {
                $bookData = MemberBookModel::getAllBooks($page, $resultsPerPage);
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
    

    public function getDashboardBooks()
    {
        $data = [];
    
        // Fetch Recommended Books
        $recBooksData = MemberBookModel::getRecommendedBooks();
        $recBooksResult = $recBooksData['results']; 
        $booksrec = [];
        while ($row = $recBooksResult->fetch_assoc()) {
            $booksrec[] = $row;
        }
        $data['recommended'] = $booksrec;
    
        // Fetch Latest Arrival Books
        $newArrivalData = MemberBookModel::getLatestArrivalBooks();
        $newArrivalResult = $newArrivalData['results']; 
        $latestbooks = [];
        while ($row = $newArrivalResult->fetch_assoc()) {
            $latestbooks[] = $row;
        }
        $data['latest'] = $latestbooks;
    
        // Fetch Top Books
        $topData = MemberBookModel::getTopBooks();
        $topResult = $topData['results']; 
        $topbooks = [];
        while ($row = $topResult->fetch_assoc()) {
            $topbooks[] = $row;
        }
        $data['top'] = $topbooks;
    
        // Return as JSON response
        echo json_encode([
            "success" => true,
            "books" => $data
        ]);
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

    public static function getAllCategories()
    {
        $categories = MemberBookModel::getAllCategories();

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
        $languages = MemberBookModel::getLanguages();

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
}