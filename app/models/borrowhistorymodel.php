<?php

require_once config::getdbPath();

class BorrowHistoryModel
{
    public static function getBorrowBooks($id, $page, $resultsPerPage)
    {
        $pageResults = ($page - 1) * $resultsPerPage;
        $totalBooks = self::getTotalBorrowedBooks($id);

        $booksResult = Database::search("SELECT * FROM `borrow` 
              INNER JOIN `book` ON `borrow`.`borrow_book_id` = `book`.`book_id` 
              WHERE `borrow_member_id` = '$id' 
              LIMIT $resultsPerPage OFFSET $pageResults");
        $books = [];

        while ($row = $booksResult->fetch_assoc()) {
            $books[] = $row;
        }

        return [
            'total' => $totalBooks,
            'results' => $books
        ];
    }

    private static function getTotalBorrowedBooks($id)
    {
        $result = Database::search("SELECT COUNT(*) AS total FROM `borrow` WHERE `borrow_member_id` = '$id'");
        $row = $result->fetch_assoc();
        return $row['total'] ?? 0;
    }

  
    public static function searchBorrowBooks($id, $bookid, $title, $category, $page, $resultsPerPage)
    {
        $pageResults = ($page - 1) * $resultsPerPage;
        $totalSearch = self::getTotalSearchResults($id, $title, $category, $bookid);

        $sql = "SELECT * FROM `borrow`
        INNER JOIN `book` ON `borrow`.`borrow_book_id` = `book`.`book_id`
        INNER JOIN `member` ON `borrow`.`borrow_member_id` = `member`.`id`
        INNER JOIN `category` ON `book`.`category_id` = `category`.`category_id` 
        WHERE `member`.`id`='$id' AND 1";

        if (!empty($bookid)) {
            $sql .= " AND `book_id` LIKE '%$bookid%'";
        }
        if (!empty($title)) {
            $sql .= " AND `title` LIKE '%$title%'";
        }
        if (!empty($category)) {
            $sql .= " AND `category_name` LIKE '%$category%'";
        }
        $sql .= " LIMIT $resultsPerPage OFFSET $pageResults"; 
        
        $rs = Database::search($sql);
        $books = [];
        while ($row = $rs->fetch_assoc()) {
            $books[] = $row;
        }
        return ['results' => $books, 'total' => $totalSearch];
    }

    private static function getTotalSearchResults($id, $title, $category, $bookid)
    {
        $countQuery = "SELECT COUNT(*) as total FROM `borrow`
        INNER JOIN `book` ON `borrow`.`borrow_book_id` = `book`.`book_id`
        INNER JOIN `member` ON `borrow`.`borrow_member_id` = `member`.`id`
        INNER JOIN `category` ON `book`.`category_id` = `category`.`category_id` 
        WHERE `member`.`id`='$id' AND 1";
        
        if (!empty($bookid)) {
            $countQuery .= " AND `book_id` LIKE '%$bookid%'";
        }
        if (!empty($title)) {
            $countQuery .= " AND `title` LIKE '%$title%'";
        }
        if (!empty($category)) {
            $countQuery .= " AND `category_name` LIKE '%$category%'";
        }
    
        $result = Database::search($countQuery);
        $row = $result->fetch_assoc();
        return $row['total'] ?? 0;
    }
     
}
