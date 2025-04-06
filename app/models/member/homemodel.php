<?php

require_once config::getdbPath();

class HomeModel
{

    private $db;

    public function __construct()
    {
        $this->db = new Database();
    }

    public static function getOpeningHours()
    {
        // Execute the query and return the results
        $result = Database::search("SELECT 
            CASE 
                WHEN `day` = 'Week Day' THEN 'Weekday' 
                WHEN `day` = 'Week End' THEN 'Weekend' 
                WHEN `day` = 'Holiday' THEN 'Holiday' 
                ELSE `day` 
            END AS `day_label`,
            `open_time`, 
            `close_time`
        FROM `opening_hours`;");

        // Check if there are results and return them
        if ($result && $result->num_rows > 0) {
            $openingHours = [];

            // Fetch each row and add it to the result array
            while ($row = $result->fetch_assoc()) {
                $openingHours[] = [
                    'day_label' => $row['day_label'],
                    'open_time' => $row['open_time'],
                    'close_time' => $row['close_time']
                ];
            }

            return $openingHours; // Return the array of opening hours
        }

        return false; // If no results are found, return false
    }

    public static function getNewsUpdates()
    {
        // Execute the query and return the results
        $result = Database::search("SELECT * FROM `news` ");

        // Check if there are results and return them
        if ($result && $result->num_rows > 0) {
            $newsData = [];

            while ($row = $result->fetch_assoc()) {
                $newsData[] = $row;
            }
            return [
                'results' => $newsData
            ];
        }

        return false; 
    }


    public static function getLibraryInfo()
    {
        $result = Database::search("SELECT * FROM `library_info` LIMIT 1");

        if ($result && $result->num_rows > 0) {
            return $result->fetch_assoc();
        }

        return false;
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
