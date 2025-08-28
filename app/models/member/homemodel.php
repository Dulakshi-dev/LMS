<?php

require_once config::getdbPath();

class HomeModel
{   /**
 * HomeModel
 * Handles data retrieval for the home page such as opening hours, news updates,
 * library info, top books, and latest arrivals.
 */

    private $db;

    public function __construct()
    {
        $this->db = new Database();
    }

    public static function getOpeningHours()
    {
        // Execute the query and return the results
        $query = "SELECT 
                    CASE 
                        WHEN `day` = 'Week Day' THEN 'Weekday' 
                        WHEN `day` = 'Week End' THEN 'Weekend' 
                        WHEN `day` = 'Holiday' THEN 'Holiday' 
                        ELSE `day` 
                    END AS `day_label`,
                    `open_time`, 
                    `close_time`
                  FROM `opening_hours`;";

        $result = Database::search($query);

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

        /**
     * getNewsUpdates
     * Fetches news updates from the database.
     * @return array|false Returns an array of news results or false if no news found.
     */

    public static function getNewsUpdates()
    {
        // Execute the query and return the results
        $query = "SELECT * FROM `news`";
        $result = Database::search($query);
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
        $query = "SELECT * FROM `library_info` LIMIT 1;";
        $result = Database::search($query);

        if ($result && $result->num_rows > 0) {
            return $result->fetch_assoc();
        }

        return false;
    }

    public static function getTopBooks($limit = 4)
    {
        $query = "SELECT book.*, COUNT(borrow.borrow_book_id) AS borrow_count
                  FROM book
                  INNER JOIN borrow ON book.book_id = borrow.borrow_book_id
                  GROUP BY book.book_id
                  ORDER BY borrow_count DESC
                  LIMIT ?";

        $params = [$limit];
        $types = "i";

        $rs = Database::search($query, $params, $types);

        return $rs ?: null; // Return null if the query fails
    }

    public static function getLatestArrivals()
    {
        $query = "SELECT * FROM `book` ORDER BY CAST(SUBSTRING(`book_id`, 3) AS UNSIGNED) DESC LIMIT 4;";
        $rs = Database::search($query);

        return $rs ?: null; // Return null if the query fails
    }
}
