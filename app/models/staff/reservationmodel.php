<?php

require_once config::getdbPath();

class ReservationModel
{

    public static function getAllReservations($page, $resultsPerPage)
    {
        $pageResults = ($page - 1) * $resultsPerPage;
        $totalReservations = self::getTotalReservations();

        $rs = Database::search("SELECT `reservation_id`,`id`,`member_id`,`reservation_book_id`,`title`,`reservation_date`,`expiration_date`,`status` FROM `reservation` 
INNER JOIN `book` ON `reservation`.`reservation_book_id` = `book`.`book_id` 
INNER JOIN `member` ON `reservation`.`reservation_member_id` = `member`.`id`
INNER JOIN `member_login` ON `member`.`id` = `member_login`.`memberId`  
INNER JOIN `reservation_status` ON `reservation`.`status_id` = `reservation_status`.`status_id`
        LIMIT $resultsPerPage OFFSET $pageResults");

        $reservations = [];

        while ($row = $rs->fetch_assoc()) {
            $reservations[] = $row;
        }
        return [
            'total' => $totalReservations,
            'results' => $reservations
        ];
    }

    private static function getTotalReservations()
    {
        $result = Database::search("SELECT COUNT(*) AS total FROM `reservation` 
INNER JOIN `book` ON `reservation`.`reservation_book_id` = `book`.`book_id` 
INNER JOIN `member` ON `reservation`.`reservation_member_id` = `member`.`id`
INNER JOIN `member_login` ON `member`.`id` = `member_login`.`memberId`  
INNER JOIN `reservation_status` ON `reservation`.`status_id` = `reservation_status`.`status_id`");
        $row = $result->fetch_assoc();
        return $row['total'] ?? 0;
    }

    public static function searchReservations($memberid, $bookid, $title, $status, $page, $resultsPerPage)
    {
        $pageResults = ($page - 1) * $resultsPerPage;
        $totalSearch = self::getTotalSearchResults($memberid, $bookid, $title, $status);
    
        $sql = "SELECT `reservation_id`,`id`,`member_id`,`reservation_book_id`,`title`,`reservation_date`,`expiration_date`,`reservation_status`.`status` 
                FROM `reservation` 
                INNER JOIN `book` ON `reservation`.`reservation_book_id` = `book`.`book_id` 
                INNER JOIN `member` ON `reservation`.`reservation_member_id` = `member`.`id`
                INNER JOIN `member_login` ON `member`.`id` = `member_login`.`memberId`  
                INNER JOIN `reservation_status` ON `reservation`.`status_id` = `reservation_status`.`status_id`
                WHERE 1"; // Basic where clause for conditions
    
        // Apply filters based on the input parameters
        if (!empty($memberid)) {
            $sql .= " AND `member_id` LIKE '%$memberid%'";
        }
        if (!empty($bookid)) {
            $sql .= " AND `reservation_book_id` LIKE '%$bookid%'";
        }
        if (!empty($title)) {
            $sql .= " AND `title` LIKE '%$title%'";
        }
        if (!empty($status) && $status !== 'all') { // Check if status is selected and is not 'all'
            $sql .= " AND `reservation_status`.`status` = '$status'"; // Apply status filter
        }
    
        // Limit the results with pagination
        $sql .= " LIMIT $resultsPerPage OFFSET $pageResults";
    
        // Execute the query and fetch results
        $rs = Database::search($sql);
        $reservations = [];
        while ($row = $rs->fetch_assoc()) {
            $reservations[] = $row;
        }
    
        return ['results' => $reservations, 'total' => $totalSearch];
    }
    
    private static function getTotalSearchResults($memberid, $bookid, $title, $status)
    {
        // Build the base query
        $countQuery = "SELECT COUNT(`reservation_id`) AS total
                       FROM `reservation` 
                       INNER JOIN `book` ON `reservation`.`reservation_book_id` = `book`.`book_id` 
                       INNER JOIN `member` ON `reservation`.`reservation_member_id` = `member`.`id`
                       INNER JOIN `member_login` ON `member`.`id` = `member_login`.`memberId`  
                       INNER JOIN `reservation_status` ON `reservation`.`status_id` = `reservation_status`.`status_id`
                       WHERE 1"; // Basic where clause
    
        // Apply filters based on input parameters
        if (!empty($memberid)) {
            $countQuery .= " AND `member_id` LIKE '%$memberid%'";
        }
        if (!empty($bookid)) {
            $countQuery .= " AND `reservation_book_id` LIKE '%$bookid%'";
        }
        if (!empty($title)) {
            $countQuery .= " AND `title` LIKE '%$title%'";
        }
        if (!empty($status) && $status !== 'all') { // Check if status is provided and is not 'all'
            $countQuery .= " AND `reservation_status`.`status` = '$status'"; // Filter by status
        }
    
        // Execute the query and get the total count
        $result = Database::search($countQuery);
        $row = $result->fetch_assoc();
    
        // Return the total number of results (if exists)
        return $row['total'] ?? 0;
    }
    
}
