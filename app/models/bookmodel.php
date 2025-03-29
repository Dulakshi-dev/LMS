<?php
require_once config::getdbPath();

class BookModel
{

    public static function getAllBooks($page, $resultsPerPage, $status = 'Active')
    {
        $statusId = ($status === 'Active') ? 1 : 2;

        $pageResults = ($page - 1) * $resultsPerPage;
        $totalBooks = self::getTotalBooks($statusId);
        
        $rs = Database::search("SELECT * FROM book 
        INNER JOIN category ON book.category_id = category.category_id 
        INNER JOIN `status` ON book.status_id = status.status_id 
        INNER JOIN `language` ON `book`.`language_id` = `language`.`language_id` 
        WHERE `book`.`status_id`='$statusId'
        LIMIT $resultsPerPage OFFSET $pageResults");

        $books = [];

        while ($row = $rs->fetch_assoc()) {
            $books[] = $row;
        }
        return [
            'total' => $totalBooks,
            'results' => $books
        ];
    }

    private static function getTotalBooks($statusId)
    {
        $result = Database::search("SELECT COUNT(*) AS total FROM book 
        INNER JOIN category ON book.category_id = category.category_id 
        INNER JOIN `status` ON book.status_id = status.status_id 
        INNER JOIN `language` ON `book`.`language_id` = `language`.`language_id` 
        WHERE `book`.`status_id`='$statusId'");
        $row = $result->fetch_assoc();
        return $row['total'] ?? 0;
    }

    public static function searchBooks($bookid, $title, $isbn, $status = 'Active', $page, $resultsPerPage)
    {
        $statusId = ($status === 'Active') ? 1 : 2;
        $pageResults = ($page - 1) * $resultsPerPage;
        $totalSearch = self::getTotalSearchResults($title, $isbn, $bookid, $statusId);

        $sql = "SELECT * FROM `book`
        INNER JOIN category ON book.category_id = category.category_id 
        INNER JOIN `status` ON book.status_id = status.status_id 
        INNER JOIN `language` ON `book`.`language_id` = `language`.`language_id` 
        WHERE `book`.`status_id`='$statusId' AND 1";

        if (!empty($bookid)) {
            $sql .= " AND `book_id` LIKE '%$bookid%'";
        }
        if (!empty($title)) {
            $sql .= " AND `title` LIKE '%$title%'";
        }
        if (!empty($isbn)) {
            $sql .= " AND `isbn` LIKE '%$isbn%'";
        }
        $sql .= " LIMIT $resultsPerPage OFFSET $pageResults";

        $rs = Database::search($sql);
        $books = [];
        while ($row = $rs->fetch_assoc()) {
            $books[] = $row;
        }
        return ['results' => $books, 'total' => $totalSearch];
    }

    private static function getTotalSearchResults($title, $isbn, $bookid, $statusId)
    {
        $countQuery = "SELECT COUNT(*) as total FROM `book`
        INNER JOIN category ON book.category_id = category.category_id 
        INNER JOIN `status` ON book.status_id = status.status_id 
        INNER JOIN `language` ON `book`.`language_id` = `language`.`language_id` 
        WHERE `book`.`status_id`='$statusId' AND 1";

        if (!empty($bookid)) {
            $countQuery .= " AND `book_id` LIKE '%$bookid%'";
        }
        if (!empty($title)) {
            $countQuery .= " AND `title` LIKE '%$title%'";
        }
        if (!empty($isbn)) {
            $countQuery .= " AND `isbn` LIKE '%$isbn%'";
        }

        $result = Database::search($countQuery);
        $row = $result->fetch_assoc();
        return $row['total'] ?? 0;
    }


    public static function loadBookDetails($id)
    {
        $rs = Database::search("SELECT * FROM `book` INNER JOIN `category` ON `book`.`category_id` = `category`.`category_id`INNER JOIN `status` ON `book`.`status_id` = `status`.`status_id`INNER JOIN `language` ON `book`.`language_id` = `language`.`language_id` WHERE `book_id` = '$id'");
        return $rs;
    }

    public static function getAllCategories()
    {
        // Modified query to also get the count of books for each category
        $query = "SELECT category.category_id, category.category_name, COUNT(book.book_id) AS book_count
                  FROM category
                  LEFT JOIN book ON category.category_id = book.category_id
                  GROUP BY category.category_id, category.category_name";
    
        // Execute the query
        $result = Database::search($query);
    
        // Initialize an array to store categories with their book count
        $categories = [];
    
        // If there are results, loop through and fetch each category with book count
        if ($result && $result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                $categories[] = [
                    'category_id' => $row['category_id'],
                    'category_name' => $row['category_name'],
                    'book_count' => $row['book_count'] // Count of books in each category
                ];
            }
        }
    
        // Return the categories with book counts
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
        // Generate a unique book ID
        $book_id = self::generateID();

        // Insert the new book into the database
        Database::insert("INSERT INTO `book`(`book_id`,`isbn`,`title`,`author`,`pub_year`,`description`,`cover_page`,`qty`,
        `available_qty`,`category_id`,`language_id`,`status_id`) 
        VALUES ('$book_id','$isbn', '$title', '$author', '$pub', '$des','$coverpage', '$qty', '$qty', '$category','$language','1')");

        return true;
    }

    public static function generateID()
    {
        // Query to get the latest staff_id
        $result = Database::search("SELECT book_id FROM `book` ORDER BY book_id DESC LIMIT 1");

        if ($result->num_rows > 0) {
            // Fetch the last book ID
            $row = $result->fetch_assoc();
            $lastBookID = $row['book_id'];

            // Extract numeric part and increment
            $number = (int)substr($lastBookID, 2);
            $newNumber = $number + 1;

            // Format new ID with zero-padding
            $newBookID = "B-" . str_pad($newNumber, 6, "0", STR_PAD_LEFT);
        } else {
            // If no book exists, start from B-000001
            $newBookID = "B-000001";
        }
        return $newBookID;
    }

    public static function addCategory($category)
    {
        Database::insert("INSERT INTO `category`(`category_name`) VALUES ('$category')");
        return true;
    }

    public static function deactivateBook($book_id)
    {
        $rs = Database::ud("UPDATE `book` SET `status_id`='2' WHERE `book_id`='$book_id'");
        return true;
    }

    public static function activateBook($book_id)
    {
        $rs = Database::ud("UPDATE `book` SET `status_id`='1' WHERE `book_id`='$book_id'");
        return true;
    }

    public static function deleteCategory($category_id)
    {
        $rs = Database::ud("DELETE FROM `category` WHERE `category_id` = '$category_id'");
        return true;
    }
}
