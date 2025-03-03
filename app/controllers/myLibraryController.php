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
                $member_id = $_SESSION["member"]["member_id"];

                $result = MyLibraryModel::saveBook($book_id, $member_id);
    
                if ($result) {
                    header("Location: index.php?action=loadbooks");
                
    
                } else {
                    echo json_encode(["success" => false, "message" => "Failed to save"]);
                }  
            }else{
                
                echo json_encode(["success" => false, "message" => "Invalid Request"]);

            }
    }

    public function loadSavedBooks()
    {
        $member_id = $_SESSION["member"]["member_id"];
        $data = MyLibraryModel::getSavedBooks($member_id);

        $booksResult = $data['results']; 

        $books = [];
        while ($row = $booksResult->fetch_assoc()) {
            $books[] = $row;
        }

        require_once Config::getViewPath("member", 'my-library.php');
    }
}