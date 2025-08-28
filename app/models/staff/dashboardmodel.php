<?php

require_once config::getdbPath();

/**
 * DashboardModel
 * Handles fetching aggregated data for dashboard statistics.
 */
class DashboardModel
{

    private $db;

    public function __construct()
    {
        $this->db = new Database();
    }

    public static function getUserRegistrationsPerMonth()
    {

        $query = "SELECT MONTHNAME(date_joined) AS month, COUNT(*) AS total
FROM `member`
GROUP BY MONTH(date_joined), MONTHNAME(date_joined)
ORDER BY MONTH(date_joined)";

        $result = Database::search($query);
        $data = [];

        while ($row = $result->fetch_assoc()) {
            $data[] = $row;
        }

        return $data; // Returns an array of results
    }

    public static function getBooksIssuedPerMonth()
    {

        $query = "SELECT MONTHNAME(borrow_date) AS month, COUNT(*) AS total
FROM `borrow`
GROUP BY MONTH(borrow_date), MONTHNAME(borrow_date)
ORDER BY MONTH(borrow_date);";

        $result = Database::search($query);
        $data = [];

        while ($row = $result->fetch_assoc()) {
            $data[] = $row;
        }

        return $data; // Returns an array of results
    }

    public static function getBookCategoryBorrowData()
    {
        $query = "SELECT c.category_name, COUNT(*) AS total
FROM borrow b
INNER JOIN book bk ON b.borrow_book_id = bk.book_id
INNER JOIN category c ON c.category_id = bk.category_id
GROUP BY c.category_id
ORDER BY total DESC;";

        $result = Database::search($query);
        $data = [];

        while ($row = $result->fetch_assoc()) {
            $data[] = $row;
        }

        return $data; // Returns an array of results
    }

    public static function getMembersByStatus()
    {
        $query = "SELECT s.status, COUNT(*) AS total
FROM member m
INNER JOIN status s ON m.status_id = s.status_id
GROUP BY m.status_id
ORDER BY total DESC";

        $result = Database::search($query);
        $data = [];

        while ($row = $result->fetch_assoc()) {
            $data[] = $row;
        }

        return $data; // Returns an array of results
    }


    public static function getNoOfBooks()
    {
        // Assuming Database::search() returns a result object, extract the count
        $query = "SELECT COUNT(*) AS total FROM `book` WHERE `status_id`='1'";
        $result = Database::search($query);

        $row = $result->fetch_assoc(); // Fetch the first row (since it's a single value)
        return (int)$row['total']; // Return the count as an integer
    }

    public static function getNoOfMembers()
    {
        $query = "SELECT COUNT(*) AS total FROM `member` WHERE `status_id`='1'";
        $result = Database::search($query);

        $row = $result->fetch_assoc(); // Fetch the first row
        return (int)$row['total']; // Return the count as an integer
    }

    public static function getNoOfReservations()
    {
        $query = "SELECT COUNT(*) AS total FROM `reservation` WHERE `status_id`='1' OR `status_id`='5'";
        $result = Database::search($query);

        $row = $result->fetch_assoc(); // Fetch the first row
        return (int)$row['total']; // Return the count as an integer
    }

    public static function getNoOfIssuedBooks()
    {
        $query = "SELECT COUNT(*) AS total FROM `borrow`";
        $result = Database::search($query);

        $row = $result->fetch_assoc(); // Fetch the first row
        return (int)$row['total']; // Return the count as an integer
    }

    public static function getAmountOfFines()
    {
        $query = "SELECT SUM(amount) AS total FROM fines;";
        $result = Database::search($query);

        $row = $result->fetch_assoc(); // Fetch the first row
        return (int)$row['total']; // Return the count as an integer
    }

    public static function getTopBooks()
    {
        $query = "SELECT book.*, COUNT(borrow.borrow_book_id) AS borrow_count
            FROM book
            INNER JOIN borrow ON book.book_id = borrow.borrow_book_id
            GROUP BY book.book_id
            ORDER BY borrow_count DESC
            LIMIT 4;";
        $rs = Database::search($query);

        return $rs ?: null; // Return null if the query fails
    }
}
