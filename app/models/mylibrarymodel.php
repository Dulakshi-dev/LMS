<?php

require_once config::getdbPath();

class MyLibraryModel
{

    public static function getSavedBooks($id, $page, $resultsPerPage)
    {
        $pageResults = ($page - 1) * $resultsPerPage;
        $totalBooks = self::getTotalSavedBooks($id);

        $booksResult = Database::search("SELECT * FROM `member_saved_book` 
        INNER JOIN `book` ON `member_saved_book`.`saved_book_id` = `book`.`book_id` 
        INNER JOIN `member` ON `member`.`id` = `member_saved_book`.`saved_member_id` 
        WHERE `saved_member_id` = '$id' 
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

    private static function getTotalSavedBooks($id)
    {
        $result = Database::search("SELECT COUNT(*) AS total FROM `member_saved_book` 
        INNER JOIN `book` ON `member_saved_book`.`saved_book_id` = `book`.`book_id` 
        INNER JOIN `member` ON `member`.`id` = `member_saved_book`.`saved_member_id` 
        WHERE `saved_member_id` = '$id'");
        $row = $result->fetch_assoc();
        return $row['total'] ?? 0;
    }

  
    public static function searchSavedBooks($id, $bookid, $title, $category, $page, $resultsPerPage)
    {
        $pageResults = ($page - 1) * $resultsPerPage;
        $totalSearch = self::getTotalSearchResults($id, $title, $category, $bookid);

        $sql = "SELECT * FROM `member_saved_book`
        INNER JOIN `book` ON `member_saved_book`.`saved_book_id` = `book`.`book_id`
        INNER JOIN `member` ON `member_saved_book`.`saved_member_id` = `member`.`id`
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
        $countQuery = "SELECT COUNT(*) as total FROM `member_saved_book`
        INNER JOIN `book` ON `member_saved_book`.`saved_book_id` = `book`.`book_id`
        INNER JOIN `member` ON `member_saved_book`.`saved_member_id` = `member`.`id`
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

    public static function saveBook($book_id, $id)
    {
        // Check if the book is already saved by the member
        $rs = Database::search("SELECT * FROM `member_saved_book` WHERE `saved_member_id` ='$id' AND `saved_book_id` = '$book_id'");

        if ($rs->num_rows > 0) {
            // If the book is already saved, return false 
            return false;
        }
        
        // If the book is not already saved, insert it into the member's saved books list
        Database::insert("INSERT INTO `member_saved_book` (`saved_member_id`,`saved_book_id`) VALUES ('$id', '$book_id')");

        return true;
    }


    public static function unSaveBook($book_id, $id)
    {
        Database::ud("DELETE FROM `member_saved_book` WHERE `saved_member_id` = '$id' AND `saved_book_id` = '$book_id'");
        return true;
    }
}
