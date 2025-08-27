<?php

require_once config::getdbPath();

class MyLibraryModel
{

    public static function getSavedBooks($id, $page, $resultsPerPage)
    {
        $pageResults = ($page - 1) * $resultsPerPage;
        $totalBooks = self::getTotalSavedBooks($id);



        $query = "SELECT * FROM `member_saved_book` 
        INNER JOIN `book` ON `member_saved_book`.`saved_book_id` = `book`.`book_id` 
        INNER JOIN `member` ON `member`.`id` = `member_saved_book`.`saved_member_id` 
        WHERE `saved_member_id` = ? 
        LIMIT ? OFFSET ?";
        $params = [$id, $resultsPerPage, $pageResults];
        $types = "iii";
        $booksResult = Database::search($query, $params, $types);


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
        $query = "SELECT COUNT(*) AS total FROM `member_saved_book` 
        INNER JOIN `book` ON `member_saved_book`.`saved_book_id` = `book`.`book_id` 
        INNER JOIN `member` ON `member`.`id` = `member_saved_book`.`saved_member_id` 
        WHERE `saved_member_id` = ?";
        $params = [$id];
        $types = "i";
        $result = Database::search($query, $params, $types);

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
            WHERE `member`.`id` = ?";

        $params = [$id];
        $types = "i";

        if (!empty($bookid)) {
            $sql .= " AND `book_id` LIKE ?";
            $params[] = "%$bookid%";
            $types .= "s";
        }
        if (!empty($title)) {
            $sql .= " AND `title` LIKE ?";
            $params[] = "%$title%";
            $types .= "s";
        }
        if (!empty($category)) {
            $sql .= " AND `category_name` LIKE ?";
            $params[] = "%$category%";
            $types .= "s";
        }

        $sql .= " LIMIT ? OFFSET ?";
        $params[] = $resultsPerPage;
        $params[] = $pageResults;
        $types .= "ii";

        $rs = Database::search($sql, $params, $types);

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
        WHERE `member`.`id` = ?";

        $params = [$id];
        $types = "i";

        if (!empty($bookid)) {
            $countQuery .= " AND `book_id` LIKE ?";
            $params[] = "%$bookid%";
            $types .= "s";
        }
        if (!empty($title)) {
            $countQuery .= " AND `title` LIKE ?";
            $params[] = "%$title%";
            $types .= "s";
        }
        if (!empty($category)) {
            $countQuery .= " AND `category_name` LIKE ?";
            $params[] = "%$category%";
            $types .= "s";
        }

        $result = Database::search($countQuery, $params, $types);
        $row = $result->fetch_assoc();
        return $row['total'] ?? 0;
    }


    public static function saveBook($book_id, $id)
    {
        // Check if the book is already saved by the member

        $query = "SELECT * FROM `member_saved_book` WHERE `saved_member_id` =? AND `saved_book_id` = ?";
        $params = [$id, $book_id];
        $types = "is";
        $rs = Database::search($query, $params, $types);

        if ($rs->num_rows > 0) {
            // If the book is already saved, return false 
            return false;
        }

        // If the book is not already saved, insert it into the member's saved books list

        $query = "INSERT INTO `member_saved_book` (`saved_member_id`,`saved_book_id`) VALUES (?, ?)";
        $params = [$id, $book_id];
        $types = "is";
        Database::insert($query, $params, $types);

        return true;
    }


    public static function unSaveBook($book_id, $id)
    {
         $query = "DELETE FROM `member_saved_book` WHERE `saved_member_id` = ? AND `saved_book_id` = ?";
        $params = [$id, $book_id];
        $types = "is";
        Database::ud($query, $params, $types);

        return true;
    }
}
