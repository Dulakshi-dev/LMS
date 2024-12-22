<?php
// UserModel.php
require_once __DIR__ . '../../../database/connection.php';

class BookModel
{

    public function getAllBooks()
    {
        $rs = Database::search("SELECT * FROM `book_details`");
        return $rs;
    }

    public static function loadBookDetails($id)
    {
        $rs = Database::search("SELECT * FROM `book_details` WHERE `book_id` = '$id'");
        return $rs;
    }


    public static function updateBookDetails($book_id, $isbn, $title, $author, $category, $pubYear, $quantity, $description)
    {

        Database::iud("UPDATE book SET
            `isbn` = '$isbn',
            `title` = '$title', 
            `author` = '$author', 
            `pub_year` = '$pubYear', 
            `qty` = '$quantity', 
            `category_id` = '$category', 
             `description` = '$description'
            WHERE `book_id` = '$book_id'");
        return true;
    }

    public static function addBook($isbn, $author,$title,$category,$pub,$qty,$des){
        Database::iud("INSERT INTO `book`(`isbn`,`title`,`author`,`pub_year`,`description`,`qty`,`available_qty`,`category_id`,`status_id`) VALUES ('$isbn', '$title', '$author', '$pub', '$des', '$qty', '$qty', '1', '1')");
        return true;
    }
}
