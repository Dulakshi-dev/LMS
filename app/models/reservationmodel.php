<?php

require_once __DIR__ . '../../../database/connection.php';

class ReservationModel
{
    public static function getAllReservations( $page)
    {

        $rs = Database::search("SELECT `reservation_id`,`id`,`member_id`,`reservation_book_id`,`title`,`reservation_date`,`expiration_date`,`status` FROM `reservation` 
INNER JOIN `book` ON `reservation`.`reservation_book_id` = `book`.`book_id` 
INNER JOIN `member` ON `reservation`.`reservation_member_id` = `member`.`id`
INNER JOIN `member_login` ON `member`.`id` = `member_login`.`memberId`  
INNER JOIN `reservation_status` ON `reservation`.`status_id` = `reservation_status`.`status_id`");
        $num = $rs->num_rows;
        $resultsPerPage = 10;
        $pageResults = ($page - 1) * $resultsPerPage;

        $rs2 = Database::search("SELECT `reservation_id`,`id`,`member_id`,`reservation_book_id`,`title`,`reservation_date`,`expiration_date`,`status` FROM `reservation` 
INNER JOIN `book` ON `reservation`.`reservation_book_id` = `book`.`book_id` 
INNER JOIN `member` ON `reservation`.`reservation_member_id` = `member`.`id`
INNER JOIN `member_login` ON `member`.`id` = `member_login`.`memberId`  
INNER JOIN `reservation_status` ON `reservation`.`status_id` = `reservation_status`.`status_id`");
        return [
            'total' => $num,
            'results' => $rs2
        ];
    }
}
