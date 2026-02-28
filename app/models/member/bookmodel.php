<?php


require_once config::getdbPath();
/**
 * BookModel
 * Handles all database operations related to books, categories, and languages.
 */

class BookModel
{       /**
     * getAllBooks
     * Retrieves a paginated list of all available books.
     * @param int $page Current page number
     * @param int $resultsPerPage Number of results per page
     * @return array Returns an array with total books and results
     */

    public static function getAllBooks($page, $resultsPerPage)
    {
        $pageResults = ($page - 1) * $resultsPerPage;
        $totalBooks = self::getTotalBooks();


        $query = "SELECT * FROM book 
        INNER JOIN category ON book.category_id = category.category_id 
        INNER JOIN `status` ON book.status_id = status.status_id 
        INNER JOIN `language` ON `book`.`language_id` = `language`.`language_id` 
        WHERE `book`.`status_id`='1'
        LIMIT ? OFFSET ?";
        $params = [$resultsPerPage, $pageResults];
        $types = "ii";
        $rs = Database::search($query, $params, $types);

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
        $query = "SELECT COUNT(*) AS total FROM book 
        INNER JOIN category ON book.category_id = category.category_id 
        INNER JOIN `status` ON book.status_id = status.status_id 
        INNER JOIN `language` ON `book`.`language_id` = `language`.`language_id` 
        WHERE `book`.`status_id`= ?";
        $params = [1];
        $types = "i";

        $result = Database::search($query, $params, $types);

        $row = $result->fetch_assoc();
        return $row['total'] ?? 0;
    }

    public static function searchBooks($title, $category_id, $language_id, $page, $resultsPerPage)
    {
        $pageResults = ($page - 1) * $resultsPerPage;
        $totalSearch = self::getTotalSearchResults($title, $category_id, $language_id);

        $query = "SELECT * FROM `book`
            INNER JOIN category ON book.category_id = category.category_id
            INNER JOIN `status` ON book.status_id = status.status_id
            INNER JOIN `language` ON book.language_id = language.language_id
            WHERE book.status_id = ?";
        $params = [1];
        $types = "i";

        if (!empty($category_id)) {
            $query .= " AND book.category_id = ?";
            $params[] = $category_id;
            $types .= "i";
        }

        if (!empty($language_id)) {
            $query .= " AND book.language_id = ?";
            $params[] = $language_id;
            $types .= "i";
        }

        if (!empty($title)) {
            $query .= " AND book.title LIKE ?";
            $params[] = "%$title%";
            $types .= "s";
        }

        $query .= " LIMIT ? OFFSET ?";
        $params[] = $resultsPerPage;
        $params[] = $pageResults;
        $types .= "ii";

        $rs = Database::search($query, $params, $types);

        $books = [];
        while ($row = $rs->fetch_assoc()) {
            $books[] = $row;
        }

        return ['results' => $books, 'total' => $totalSearch];
    }

     /**
     * getTotalSearchResults
     * Counts total number of books matching search criteria.
     */
    private static function getTotalSearchResults($title, $category_id, $language_id)
    {
        $query = "SELECT COUNT(*) as total FROM `book`
            INNER JOIN category ON book.category_id = category.category_id
            INNER JOIN `status` ON book.status_id = status.status_id
            INNER JOIN `language` ON book.language_id = language.language_id
            WHERE book.status_id = ?";
        $params = [1];
        $types = "i";

        if (!empty($category_id)) {
            $query .= " AND book.category_id = ?";
            $params[] = $category_id;
            $types .= "i";
        }

        if (!empty($language_id)) {
            $query .= " AND book.language_id = ?";
            $params[] = $language_id;
            $types .= "i";
        }

        if (!empty($title)) {
            $query .= " AND book.title LIKE ?";
            $params[] = "%$title%";
            $types .= "s";
        }

        $result = Database::search($query, $params, $types);
        $row = $result->fetch_assoc();
        return $row['total'] ?? 0;
    }


    public static function getRecommendedBooks()
    {
        $id = $_SESSION["member"]["id"];

        $query = "SELECT DISTINCT b.*
        FROM book b
        INNER JOIN category c ON b.category_id = c.category_id
        INNER JOIN language l ON b.language_id = l.language_id
        WHERE (
            (b.category_id IN (
                SELECT DISTINCT book.category_id 
                FROM book 
                INNER JOIN borrow ON book.book_id = borrow.borrow_book_id
                WHERE borrow.borrow_member_id = ?
                UNION
                SELECT DISTINCT book.category_id
                FROM book
                INNER JOIN member_saved_book ON book.book_id = member_saved_book.saved_book_id
                WHERE member_saved_book.saved_member_id = ?
            ))
            AND 
            (b.language_id IN (
                SELECT DISTINCT book.language_id 
                FROM book 
                INNER JOIN borrow ON book.book_id = borrow.borrow_book_id
                WHERE borrow.borrow_member_id = ?
                UNION
                SELECT DISTINCT book.language_id
                FROM book
                INNER JOIN member_saved_book ON book.book_id = member_saved_book.saved_book_id
                WHERE member_saved_book.saved_member_id = ?
            ))
            OR 
            (b.author IN (
                SELECT DISTINCT book.author 
                FROM book 
                INNER JOIN borrow ON book.book_id = borrow.borrow_book_id
                WHERE borrow.borrow_member_id = ?
                UNION
                SELECT DISTINCT book.author
                FROM book
                INNER JOIN member_saved_book ON book.book_id = member_saved_book.saved_book_id
                WHERE member_saved_book.saved_member_id = ?
            ))
        )
        AND b.book_id NOT IN (
            SELECT borrow_book_id FROM borrow WHERE borrow_member_id = ?
        )
        LIMIT 4";

        $params = [$id, $id, $id, $id, $id, $id, $id];
        $types = str_repeat("i", count($params));

        $rs = Database::search($query, $params, $types);

        return [
            'results' => $rs
        ];
    }

        /**
     * getLatestArrivalBooks
     * Retrieves the latest added books.
     */

    public static function getLatestArrivalBooks()
    {
        $query = "SELECT * FROM `book` ORDER BY CAST(SUBSTRING(`book_id`, 3) AS UNSIGNED) DESC LIMIT ?";

        $params = [4];
        $types = "i";
        $rs = Database::search($query, $params, $types);
        return [
            'results' => $rs
        ];
    }

    public static function getTopBooks()
    {
        $query = "SELECT book.*, COUNT(borrow.borrow_book_id) AS borrow_count
              FROM book
              INNER JOIN borrow ON book.book_id = borrow.borrow_book_id
              GROUP BY book.book_id
              ORDER BY borrow_count DESC
              LIMIT ?";
        $params = [4];
        $types = "i";
        $rs = Database::search($query, $params, $types);

        return [
            'results' => $rs
        ];
    }


    public static function getRandomBooks($limit = 4)
    {
        $query = "SELECT * FROM `book` ORDER BY RAND() LIMIT ?";
        $params = [$limit];
        $types = "i";

        $rs = Database::search($query, $params, $types);

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

        /**
     * searchBookByTitle
     * Searches books based on a partial title.
     */
    public static function searchBookByTitle($title)
    {
        $query = "SELECT * FROM `book` WHERE `title` LIKE CONCAT('%', ?, '%')";
        $params = [$title];
        $types = "s";

        $result = Database::search($query, $params, $types);

        $books = [];
        while ($row = $result->fetch_assoc()) {
            $books[] = $row;
        }

        return [
            'results' => $books
        ];
    }
}
