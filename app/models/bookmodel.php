<?php

require_once __DIR__ . '../../../database/connection.php';

class BookModel
{

    public static function getAllBooks($page)
    {
        $rs = Database::search("SELECT * FROM book INNER JOIN category ON book.category_id = category.category_id INNER JOIN `status` ON book.status_id = status.status_id INNER JOIN `language` ON `book`.`language_id` = `language`.`language_id`");
        $num = $rs->num_rows;
        $resultsPerPage = 10;
        $pageResults = ($page - 1) * $resultsPerPage;

        $rs2 = Database::search("SELECT * FROM book INNER JOIN category ON book.category_id = category.category_id INNER JOIN `status` ON book.status_id = status.status_id INNER JOIN `language` ON `book`.`language_id` = `language`.`language_id` LIMIT $resultsPerPage OFFSET 
$pageResults");
        return [
            'total' => $num,
            'results' => $rs2
        ];
    }

    public static function loadBookDetails($id)
    {
        $rs = Database::search("SELECT * FROM `book` INNER JOIN `category` ON `book`.`category_id` = `category`.`category_id`INNER JOIN `status` ON `book`.`status_id` = `status`.`status_id`INNER JOIN `language` ON `book`.`language_id` = `language`.`language_id` WHERE `book_id` = '$id'");
        return $rs;
    }

    public static function getAllCategories()
    {
        $query = "SELECT category_id, category_name FROM category";
        $result = Database::search($query);

        $categories = [];
        if ($result && $result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                $categories[] = $row;
            }
        }

        return $categories;
    }

    public static function getLanguages()
    {
        $query = "SELECT `language_id`, `language_name` FROM `language`";
        $result = Database::search($query);

        $languages = [];
        if ($result && $result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                $languages[] = $row;
            }
        }

        return $languages;
    }

    public static function searchBooks($title, $isbn, $bookid)
    {
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

    public static function updateBookDetails($book_id, $isbn, $title, $author, $category, $language, $pubYear, $quantity, $description)
    {

        Database::ud("UPDATE book SET
            `isbn` = '$isbn',
            `title` = '$title', 
            `author` = '$author', 
            `pub_year` = '$pubYear', 
            `qty` = '$quantity', 
            `category_id` = '$category', 
            `language_id` = '$language', 
             `description` = '$description'
            WHERE `book_id` = '$book_id'");
        return true;
    }

    public static function addBook($isbn, $author, $title, $category, $language, $pub, $qty, $des, $coverpage)
    {
        $book_id = self::generateID();
        Database::insert("INSERT INTO `book`(`book_id`,`isbn`,`title`,`author`,`pub_year`,`description`,`cover_page`,`qty`,`available_qty`,`category_id`,`language_id`,`status_id`) VALUES ('$book_id','$isbn', '$title', '$author', '$pub', '$des','$coverpage', '$qty', '$qty', '$category','$language','1')");

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
