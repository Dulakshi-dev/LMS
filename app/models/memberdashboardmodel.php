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
        $member_id = $_SESSION["member"]["member_id"];

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
                    WHERE borrow.borrow_member_id = '$member_id'
                    UNION
                    SELECT DISTINCT book.category_id
                    FROM book
                    INNER JOIN member_saved_books ON book.book_id = member_saved_books.saved_book_id
                    WHERE member_saved_books.saved_member_id = '$member_id'
                ))
                AND 
                (b.language_id IN (
                    -- Languages of books the member borrowed or saved
                    SELECT DISTINCT book.language_id 
                    FROM book 
                    INNER JOIN borrow ON book.book_id = borrow.borrow_book_id
                    WHERE borrow.borrow_member_id = '$member_id'
                    UNION
                    SELECT DISTINCT book.language_id
                    FROM book
                    INNER JOIN member_saved_books ON book.book_id = member_saved_books.saved_book_id
                    WHERE member_saved_books.saved_member_id = '$member_id'
                ))
                OR 
                (b.author IN (
                    -- Authors of books the member borrowed or saved
                    SELECT DISTINCT book.author 
                    FROM book 
                    INNER JOIN borrow ON book.book_id = borrow.borrow_book_id
                    WHERE borrow.borrow_member_id = '$member_id'
                    UNION
                    SELECT DISTINCT book.author
                    FROM book
                    INNER JOIN member_saved_books ON book.book_id = member_saved_books.saved_book_id
                    WHERE member_saved_books.saved_member_id = '$member_id'
                ))
            )
            -- Exclude books already borrowed by the user
            AND b.book_id NOT IN (
                SELECT borrow_book_id FROM borrow WHERE borrow_member_id = '$member_id'
            )
            LIMIT 10");
        
        $num = $rs->num_rows;
        return [
            'total' => $num,
            'results' => $rs
        ];
    }
}
