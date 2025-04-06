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

    public static function searchReservations($memberid, $bookid, $title, $page, $resultsPerPage)
    {
        $pageResults = ($page - 1) * $resultsPerPage;
        $totalSearch = self::getTotalSearchResults($memberid, $bookid, $title);

        $sql = "SELECT `reservation_id`,`id`,`member_id`,`reservation_book_id`,`title`,`reservation_date`,`expiration_date`,`status` FROM `reservation` 
INNER JOIN `book` ON `reservation`.`reservation_book_id` = `book`.`book_id` 
INNER JOIN `member` ON `reservation`.`reservation_member_id` = `member`.`id`
INNER JOIN `member_login` ON `member`.`id` = `member_login`.`memberId`  
INNER JOIN `reservation_status` ON `reservation`.`status_id` = `reservation_status`.`status_id` AND 1";

        if (!empty($memberid)) {
            $sql .= " AND `member_id` LIKE '%$memberid%'";
        }
        if (!empty($bookid)) {
            $sql .= " AND `book_id` LIKE '%$bookid%'";
        }
        if (!empty($title)) {
            $sql .= " AND `title` LIKE '%$title%'";
        }
        $sql .= " LIMIT $resultsPerPage OFFSET $pageResults";

        $rs = Database::search($sql);
        $books = [];
        while ($row = $rs->fetch_assoc()) {
            $books[] = $row;
        }
        return ['results' => $books, 'total' => $totalSearch];
    }

    private static function getTotalSearchResults($memberid, $bookid, $title)
    {
        $countQuery = "SELECT `reservation_id`,`id`,`member_id`,`reservation_book_id`,`title`,`reservation_date`,`expiration_date`,`status` FROM `reservation` 
INNER JOIN `book` ON `reservation`.`reservation_book_id` = `book`.`book_id` 
INNER JOIN `member` ON `reservation`.`reservation_member_id` = `member`.`id`
INNER JOIN `member_login` ON `member`.`id` = `member_login`.`memberId`  
INNER JOIN `reservation_status` ON `reservation`.`status_id` = `reservation_status`.`status_id` AND 1";

if (!empty($memberid)) {
    $countQuery .= " AND `member_id` LIKE '%$memberid%'";
}
if (!empty($bookid)) {
    $countQuery .= " AND `book_id` LIKE '%$bookid%'";
}
if (!empty($title)) {
    $countQuery .= " AND `title` LIKE '%$title%'";
}

        $result = Database::search($countQuery);
        $row = $result->fetch_assoc();
        return $row['total'] ?? 0;
    }
}
