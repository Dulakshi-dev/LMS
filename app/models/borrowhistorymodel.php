<?php

require_once __DIR__ . '../../../database/connection.php';

class BorrowHistoryModel
{

    public static function getBorrowBooks($page, $id)
    {
        $rs = Database::search("SELECT * FROM `borrow` INNER JOIN `book` ON `borrow`.`borrow_book_id` = `book`.`book_id` WHERE `borrow_member_id` = '$id'");
        $num = $rs->num_rows;
        $resultsPerPage = 10;
        $pageResults = ($page - 1) * $resultsPerPage;

        $rs2 = Database::search("SELECT * FROM `borrow` INNER JOIN `book` ON `borrow`.`borrow_book_id` = `book`.`book_id` WHERE `borrow_member_id` = '$id' LIMIT $resultsPerPage OFFSET $pageResults");

        return [
            'total' => $num,
            'results' => $rs2
        ];
    }

}
