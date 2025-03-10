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

    public function saveBook()
    {
            if ($_SERVER['REQUEST_METHOD'] === 'GET') {
                $book_id = $_GET['book_id'];
                $id = $_SESSION["member"]["id"];

                $result = MyLibraryModel::saveBook($book_id, $id);
    
                if ($result) {
                    
                    header("Location: index.php?action=loaddashboardbooks");
            
                } else {
                    echo "<script>alert('Book already saved!'); window.location.href='index.php?action=loaddashboardbooks';</script>";
                }  
            }else{
                
                echo json_encode(["success" => false, "message" => "Invalid Request"]);

            }
    }

    public function loadSavedBooks()
    {
        $id = $_SESSION["member"]["id"];
        $page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
        $resultsPerPage = 10; 

        $data = MyLibraryModel::getSavedBooks($page, $id, $resultsPerPage);

        $totalBooks = $data['total'];
        $books = $data['results']; 
        $totalPages = ceil($totalBooks / $resultsPerPage);

        require_once Config::getViewPath("member", 'my-library.php');
    }

    public function unSaveBook()
    {
            if ($_SERVER['REQUEST_METHOD'] === 'GET') {
                $book_id = $_GET['book_id'];
                $id = $_SESSION["member"]["id"];

                $result = MyLibraryModel::unSaveBook($book_id, $id);
    
                if ($result) {
                    header("Location: index.php?action=savedbooks");
                } else {
                    echo json_encode(["success" => false, "message" => "Error"]);
                }  
            }else{               
                echo json_encode(["success" => false, "message" => "Invalid Request"]);
            }
    }
}