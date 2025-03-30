<?php

require_once config::getdbPath();

class DashboardModel
{

    private $db;

    public function __construct()
    {
        $this->db = new Database();
    }

    public static function getUserRegistrationsPerMonth()
    {

        $sql = "SELECT MONTHNAME(date_joined) AS month, COUNT(*) AS total
FROM `member`
GROUP BY MONTH(date_joined), MONTHNAME(date_joined)
ORDER BY MONTH(date_joined)";

        $result = Database::search($sql);
        $data = [];

        while ($row = $result->fetch_assoc()) {
            $data[] = $row;
        }

        return $data; // Returns an array of results
    }

    public static function getBooksIssuedPerMonth()
    {

        $sql = "SELECT MONTHNAME(borrow_date) AS month, COUNT(*) AS total
FROM `borrow`
GROUP BY MONTH(borrow_date), MONTHNAME(borrow_date)
ORDER BY MONTH(borrow_date);";

        $result = Database::search($sql);
        $data = [];

        while ($row = $result->fetch_assoc()) {
            $data[] = $row;
        }

        return $data; // Returns an array of results
    }

    public static function getBookCategoryBorrowData()
    {
        $sql = "SELECT c.category_name, COUNT(*) AS total
FROM borrow b
INNER JOIN book bk ON b.borrow_book_id = bk.book_id
INNER JOIN category c ON c.category_id = bk.category_id
GROUP BY c.category_id
ORDER BY total DESC;";

        $result = Database::search($sql);
        $data = [];

        while ($row = $result->fetch_assoc()) {
            $data[] = $row;
        }

        return $data; // Returns an array of results
    }

    public static function getMembersByStatus()
    {
        $sql = "SELECT s.status, COUNT(*) AS total
FROM member m
INNER JOIN status s ON m.status_id = s.status_id
GROUP BY m.status_id
ORDER BY total DESC";

        $result = Database::search($sql);
        $data = [];

        while ($row = $result->fetch_assoc()) {
            $data[] = $row;
        }

        return $data; // Returns an array of results
    }


    public static function getNoOfBooks()
    {
        // Assuming Database::search() returns a result object, extract the count
        $result = Database::search("SELECT COUNT(*) AS total FROM `book` WHERE `status_id`='1'");
        $row = $result->fetch_assoc(); // Fetch the first row (since it's a single value)
        return (int)$row['total']; // Return the count as an integer
    }
    
    public static function getNoOfMembers()
    {
        $result = Database::search("SELECT COUNT(*) AS total FROM `member` WHERE `status_id`='1'");
        $row = $result->fetch_assoc(); // Fetch the first row
        return (int)$row['total']; // Return the count as an integer
    }
    
    public static function getNoOfReservations()
    {
        $result = Database::search("SELECT COUNT(*) AS total FROM `reservation` WHERE `status_id`='1' OR `status_id`='5'");
        $row = $result->fetch_assoc(); // Fetch the first row
        return (int)$row['total']; // Return the count as an integer
    }
    
    public static function getNoOfIssuedBooks()
    {
        $result = Database::search("SELECT COUNT(*) AS total FROM `borrow`");
        $row = $result->fetch_assoc(); // Fetch the first row
        return (int)$row['total']; // Return the count as an integer
    }

    public static function getAmountOfFines()
    {
        $result = Database::search("SELECT SUM(amount) AS total FROM fines;");
        $row = $result->fetch_assoc(); // Fetch the first row
        return (int)$row['total']; // Return the count as an integer
    }
    
    public static function getTopBooks()
    {
        $rs = Database::search("SELECT book.*, COUNT(borrow.borrow_book_id) AS borrow_count
            FROM book
            INNER JOIN borrow ON book.book_id = borrow.borrow_book_id
            GROUP BY book.book_id
            ORDER BY borrow_count DESC
            LIMIT 4;");
    
        return $rs ?: null; // Return null if the query fails
    }    


}
