<?php

require_once __DIR__ . '../../../database/connection.php';

class BookModel
{

    public static function getAllBooks()
    {
        $rs = Database::search("SELECT * FROM `book` INNER JOIN `category` ON `book`.`category_id` = `category`.`category_id`INNER JOIN `status` ON `book`.`status_id` = `status`.`status_id`;");
        return $rs;
    }

    public static function loadBookDetails($id)
    {
        $rs = Database::search("SELECT * FROM `book` INNER JOIN `category` ON `book`.`category_id` = `category`.`category_id`INNER JOIN `status` ON `book`.`status_id` = `status`.`status_id` WHERE `book_id` = '$id'");
        return $rs;
    }

    public static function searchBooks($title, $isbn , $bookid) {
        $sql = "SELECT * FROM `book` WHERE 1";
        if (!empty($bookid)) {
            $sql .= " AND `book_id` LIKE '%$bookid%'";
        }
        if (!empty($title)) {
            $sql .= " AND `title` LIKE '%$title%'";
        }
        if (!empty($isbn)) {
            $sql .= " AND `isbn` LIKE '%$isbn%'";
        }

        $rs = Database::search($sql);
        return $rs;
    }


    public static function updateBookDetails($book_id, $isbn, $title, $author, $category, $pubYear, $quantity, $description)
    {

        Database::ud("UPDATE book SET
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

    public static function addBook($isbn, $author,$title,$category,$pub,$qty,$des,$coverpage){
        $book_id = self::generateID();
        Database::insert("INSERT INTO `book`(`book_id`,`isbn`,`title`,`author`,`pub_year`,`description`,`cover_page`,`qty`,`available_qty`,`category_id`,`status_id`) VALUES ('$book_id','$isbn', '$title', '$author', '$pub', '$des','$coverpage', '$qty', '$qty', '1', '1')");

        return true;
    }

    public static function generateID()
    {
        // Query to get the latest staff_id
        $result = Database::search("SELECT book_id FROM `book` ORDER BY book_id DESC LIMIT 1");

        if ($result->num_rows > 0) {
            $row = $result->fetch_assoc();
            $lastBookID = $row['book_id'];

            $number = (int)substr($lastBookID, 2);
            $newNumber = $number + 1;

            $newBookID = "B-" . str_pad($newNumber, 6, "0", STR_PAD_LEFT);
        } else {
            $newBookID = "B-000001";
        }
        return $newBookID;
    }
}

