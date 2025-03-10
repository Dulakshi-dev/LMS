<?php

require_once config::getdbPath();

class MyLibraryModel
{
    public static function saveBook($book_id, $id)
    {
        $rs = Database::search("SELECT * FROM `member_saved_book` WHERE `saved_member_id` ='$id' AND `saved_book_id` = '$book_id'");

        if ($rs->num_rows > 0) {
            return false;
        }
        Database::insert("INSERT INTO `member_saved_book` (`saved_member_id`,`saved_book_id`) VALUES ('$id', '$book_id')");

        return true;
    }

    public static function getSavedBooks($page, $id, $resultsPerPage)
    {
        $pageResults = ($page - 1) * $resultsPerPage;
        $totalBooks = self::getTotalSavedBooks($id);

        $rs = Database::search("SELECT * FROM `member_saved_book` 
        INNER JOIN `book` ON `member_saved_book`.`saved_book_id` = `book`.`book_id` 
        INNER JOIN `member` ON `member`.`id` = `member_saved_book`.`saved_member_id` 
        WHERE `saved_member_id` = '$id' LIMIT $resultsPerPage OFFSET $pageResults");
        $books = [];
        
        while ($row = $rs->fetch_assoc()) {
            $books[] = $row;
        }

        return [
            'total' => $totalBooks,
            'results' => $books
        ];
    }

    private static function getTotalSavedBooks($id)
    {
        $result = Database::search("SELECT COUNT(*) AS total FROM `member_saved_book` INNER JOIN `book` ON `member_saved_book`.`saved_book_id` = `book`.`book_id` INNER JOIN `member` ON `member`.`id` = `member_saved_book`.`saved_member_id` WHERE `saved_member_id` = '$id'");
        $row = $result->fetch_assoc();
        return $row['total'] ?? 0;
    }

    public static function unSaveBook($book_id, $id)
    {

        Database::ud("DELETE FROM `member_saved_book` WHERE `saved_member_id` = '$id' AND `saved_book_id` = '$book_id'");
        return true;
    }
}
