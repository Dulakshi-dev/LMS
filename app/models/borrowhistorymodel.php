<?php

require_once config::getdbPath();

class BorrowHistoryModel
{
    public static function getBorrowBooks($page, $id, $resultsPerPage)
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
}
