<?php

require_once __DIR__ . '../../../database/connection.php';

class MemberBookModel
{

    public static function getAllBooks($page)
    {
        $rs = Database::search("SELECT * FROM book INNER JOIN category ON book.category_id = category.category_id INNER JOIN status ON book.status_id = status.status_id;");
        $num = $rs->num_rows;
        $resultsPerPage = 10;
        $pageResults = ($page - 1) * $resultsPerPage;

        $rs2 = Database::search("SELECT * FROM book INNER JOIN category ON book.category_id = category.category_id INNER JOIN status ON book.status_id = status.status_id LIMIT $resultsPerPage OFFSET 
$pageResults");
        return [
            'total' => $num,
            'results' => $rs2
        ];
    }
}
