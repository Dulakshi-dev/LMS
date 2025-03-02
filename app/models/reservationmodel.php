<?php

require_once __DIR__ . '../../../database/connection.php';

class ReservationModel
{
    public static function getAllReservations($member_id, $page)
    {

        $rs = Database::search("SELECT * FROM `reservation` INNER JOIN `book` ON `reservation`.`reservation_book_id` = `book`.`book_id` INNER JOIN `reservation_status` ON `reservation`.`status_id` = `reservation_status`.`status_id` WHERE `reservation_member_id` = '$member_id';");
        $num = $rs->num_rows;
        $resultsPerPage = 10;
        $pageResults = ($page - 1) * $resultsPerPage;

        $rs2 = Database::search("SELECT * FROM `reservation` INNER JOIN `book` ON `reservation`.`reservation_book_id` = `book`.`book_id` INNER JOIN `reservation_status` ON `reservation`.`status_id` = `reservation_status`.`status_id` WHERE `reservation_member_id` = '$member_id';");
        return [
            'total' => $num,
            'results' => $rs2
        ];
    }



}
