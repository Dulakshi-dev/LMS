<?php


require_once __DIR__ . '../../../database/connection.php';

class MemberDashboardModel
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

    public static function getRecommendedBooks()
    {
        $id = $_SESSION["member"]["id"];

        $rs = Database::search("SELECT DISTINCT b.*
            FROM book b
            INNER JOIN category c ON b.category_id = c.category_id
            INNER JOIN language l ON b.language_id = l.language_id
            WHERE (
                (b.category_id IN (
                    -- Categories of books the member borrowed or saved
                    SELECT DISTINCT book.category_id 
                    FROM book 
                    INNER JOIN borrow ON book.book_id = borrow.borrow_book_id
                    WHERE borrow.borrow_member_id = '$id'
                    UNION
                    SELECT DISTINCT book.category_id
                    FROM book
                    INNER JOIN member_saved_book ON book.book_id = member_saved_book.saved_book_id
                    WHERE member_saved_book.saved_member_id = '$id'
                ))
                AND 
                (b.language_id IN (
                    -- Languages of books the member borrowed or saved
                    SELECT DISTINCT book.language_id 
                    FROM book 
                    INNER JOIN borrow ON book.book_id = borrow.borrow_book_id
                    WHERE borrow.borrow_member_id = '$id'
                    UNION
                    SELECT DISTINCT book.language_id
                    FROM book
                    INNER JOIN member_saved_book ON book.book_id = member_saved_book.saved_book_id
                    WHERE member_saved_book.saved_member_id = '$id'
                ))
                OR 
                (b.author IN (
                    -- Authors of books the member borrowed or saved
                    SELECT DISTINCT book.author 
                    FROM book 
                    INNER JOIN borrow ON book.book_id = borrow.borrow_book_id
                    WHERE borrow.borrow_member_id = '$id'
                    UNION
                    SELECT DISTINCT book.author
                    FROM book
                    INNER JOIN member_saved_book ON book.book_id = member_saved_book.saved_book_id
                    WHERE member_saved_book.saved_member_id = '$id'
                ))
            )
            -- Exclude books already borrowed by the user
            AND b.book_id NOT IN (
                SELECT borrow_book_id FROM borrow WHERE borrow_member_id = '$id'
            )
            LIMIT 10");
        
        return [
            'results' => $rs
        ];
    }

    public static function getLatestArrivalBooks()
    {
        $rs = Database::search("SELECT * FROM `book` ORDER BY CAST(SUBSTRING(`book_id`, 3) AS UNSIGNED) DESC LIMIT 4;");
        
        return [
            'results' => $rs
        ];
    }

    public static function getTopBooks()
    {
        $rs = Database::search("SELECT book.*, COUNT(borrow.borrow_book_id) AS borrow_count
FROM book
INNER JOIN borrow ON book.book_id = borrow.borrow_book_id
GROUP BY book.book_id
ORDER BY borrow_count DESC
LIMIT 4;");
        
        return [
            'results' => $rs
        ];
    }
}
