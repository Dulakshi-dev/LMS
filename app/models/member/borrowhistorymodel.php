<?php

require_once config::getdbPath();
/**
 * BorrowHistoryModel
 * Handles all database operations related to a member's borrowed books and search history.
 */

class BorrowHistoryModel
{       /**
     * getBorrowBooks
     * Retrieves a paginated list of books borrowed by a member.
     * @param int $id Member ID
     * @param int $page Current page number
     * @param int $resultsPerPage Number of results per page
     * @return array Returns total borrowed books and results array
     */
    public static function getBorrowBooks($id, $page, $resultsPerPage)
    {
        $pageResults = ($page - 1) * $resultsPerPage;
        $totalBooks = self::getTotalBorrowedBooks($id);

        $query = "SELECT * FROM `borrow` 
              INNER JOIN `book` ON `borrow`.`borrow_book_id` = `book`.`book_id` 
              WHERE `borrow_member_id` = ? 
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

    private static function getTotalBorrowedBooks($id)
    {
        $query = "SELECT COUNT(*) AS total FROM `borrow` WHERE `borrow_member_id` = ?";
        $params = [$id];
        $types = "i";

        $result = Database::search($query, $params, $types);
        $row = $result->fetch_assoc();
        return $row['total'] ?? 0;
    }

        /**
     * searchBorrowBooks
     * Searches borrowed books based on book ID, title, and category with pagination.
     * @param int $id Member ID
     * @param string $bookid Book ID filter
     * @param string $title Book title filter
     * @param string $category Book category filter
     * @param int $page Current page number
     * @param int $resultsPerPage Number of results per page
     * @return array Returns search results and total count
     */
    public static function searchBorrowBooks($id, $bookid, $title, $category, $page, $resultsPerPage)
    {
        $pageResults = ($page - 1) * $resultsPerPage;
        $totalSearch = self::getTotalSearchResults($id, $title, $category, $bookid);

        $query = "SELECT * FROM `borrow`
        INNER JOIN `book` ON `borrow`.`borrow_book_id` = `book`.`book_id`
        INNER JOIN `member` ON `borrow`.`borrow_member_id` = `member`.`id`
        INNER JOIN `category` ON `book`.`category_id` = `category`.`category_id`
        WHERE `member`.`id` = ?";

        $params = [$id];
        $types = "i";

        if (!empty($bookid)) {
            $query .= " AND `book_id` LIKE ?";
            $params[] = "%$bookid%";
            $types .= "s";
        }
        if (!empty($title)) {
            $query .= " AND `title` LIKE ?";
            $params[] = "%$title%";
            $types .= "s";
        }
        if (!empty($category)) {
            $query .= " AND `category_name` LIKE ?";
            $params[] = "%$category%";
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
     * Counts total borrowed books matching search criteria.
     * @param int $id Member ID
     * @param string $title Book title filter
     * @param string $category Book category filter
     * @param string $bookid Book ID filter
     * @return int Total matched books
     */

    private static function getTotalSearchResults($id, $title, $category, $bookid)
    {
        $query = "SELECT COUNT(*) as total FROM `borrow`
        INNER JOIN `book` ON `borrow`.`borrow_book_id` = `book`.`book_id`
        INNER JOIN `member` ON `borrow`.`borrow_member_id` = `member`.`id`
        INNER JOIN `category` ON `book`.`category_id` = `category`.`category_id`
        WHERE `member`.`id` = ?";

        $params = [$id];
        $types = "i"; 

        if (!empty($bookid)) {
            $query .= " AND `book_id` LIKE ?";
            $params[] = "%$bookid%";
            $types .= "s";
        }
        if (!empty($title)) {
            $query .= " AND `title` LIKE ?";
            $params[] = "%$title%";
            $types .= "s";
        }
        if (!empty($category)) {
            $query .= " AND `category_name` LIKE ?";
            $params[] = "%$category%";
            $types .= "s";
        }

        $result = Database::search($query, $params, $types);
        $row = $result->fetch_assoc();
        return $row['total'] ?? 0;
    }
}
