<?php


require_once config::getdbPath();

class BookModel
{

    public static function getAllBooks($page, $resultsPerPage)
    {
        $pageResults = ($page - 1) * $resultsPerPage;
        $totalBooks = self::getTotalBooks();
        $rs = Database::search("SELECT * FROM book 
        INNER JOIN category ON book.category_id = category.category_id 
        INNER JOIN `status` ON book.status_id = status.status_id 
        INNER JOIN `language` ON `book`.`language_id` = `language`.`language_id` 
        WHERE `book`.`status_id`='1'
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

    private static function getTotalBooks()
    {
        $result = Database::search("SELECT COUNT(*) AS total FROM book 
        INNER JOIN category ON book.category_id = category.category_id 
        INNER JOIN `status` ON book.status_id = status.status_id 
        INNER JOIN `language` ON `book`.`language_id` = `language`.`language_id` 
        WHERE `book`.`status_id`='1'");
        $row = $result->fetch_assoc();
        return $row['total'] ?? 0;
    }

    public static function searchBooks($title, $category_id, $language_id, $page, $resultsPerPage)
    {
        $pageResults = ($page - 1) * $resultsPerPage;
        $totalSearch = self::getTotalSearchResults($title, $category_id, $language_id);

        $sql = "SELECT * FROM `book`
        INNER JOIN category ON book.category_id = category.category_id 
        INNER JOIN `status` ON book.status_id = status.status_id 
        INNER JOIN `language` ON `book`.`language_id` = `language`.`language_id` 
        WHERE `book`.`status_id`='1' AND 1";

        if (!empty($category_id)) {
            $sql .= " AND `book`.`category_id`='$category_id'";
        }
        if (!empty($language_id)) {
            $sql .= " AND `book`.`language_id`='$language_id'";
        }
        if (!empty($title)) {
            $sql .= " AND `book`.`title` LIKE '%$title%'";
        }

        $sql .= " LIMIT $resultsPerPage OFFSET $pageResults";

        $rs = Database::search($sql);
        $books = [];
        while ($row = $rs->fetch_assoc()) {
            $books[] = $row;
        }
        return ['results' => $books, 'total' => $totalSearch];
    }

    private static function getTotalSearchResults($title, $category_id, $language_id)
    {
        $countQuery = "SELECT COUNT(*) as total FROM `book`
        INNER JOIN category ON book.category_id = category.category_id 
        INNER JOIN `status` ON book.status_id = status.status_id 
        INNER JOIN `language` ON `book`.`language_id` = `language`.`language_id` 
        WHERE `book`.`status_id`='1' AND 1";

        if (!empty($category_id)) {
            $countQuery .= " AND `book`.`category_id`='$category_id'";
        }
        if (!empty($language_id)) {
            $countQuery .= " AND `book`.`language_id`='$language_id'";
        }
        if (!empty($title)) {
            $countQuery .= " AND `book`.`title` LIKE '%$title%'";
        }

        $result = Database::search($countQuery);
        $row = $result->fetch_assoc();
        return $row['total'] ?? 0;
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

    public static function getRandomBooks($limit = 4)
    {
        $rs = Database::search("SELECT * FROM `book` ORDER BY RAND() LIMIT $limit;");
        return [
            'results' => $rs
        ];
    }


    public static function getAllCategories()
    {
        $query = "SELECT category_id, category_name FROM category";
        $result = Database::search($query);

        $categories = [];
        if ($result && $result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                $categories[] = $row;
            }
        }

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

    public static function searchBookByTitle($title)
    {
        $query = "SELECT * FROM `book` WHERE `title` LIKE '%$title%';";
        $result = Database::search($query);

        $books = [];

        while ($row = $result->fetch_assoc()) {
            $books[] = $row;
        }
        return [
            'results' => $books
        ];
    }
}
