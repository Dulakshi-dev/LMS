<?php

require_once __DIR__ . '../../../database/connection.php';

class ReservationModel
{
    public static function reserveBook($book_id, $member_id)
    {

        Database::insert("INSERT INTO `reservation` (`reservation_date`, `expiration_date`, `reservation_member_id`, `reservation_book_id`, `status_id`) 
        VALUES (CURDATE(), DATE_ADD(CURDATE(), INTERVAL 7 DAY), '$member_id', '$book_id', '1')");

        $result = Database::search("SELECT `available_qty` FROM `book` WHERE `book_id` = '$book_id'");
        $data = $result->fetch_assoc();
        $available_qty = $data["available_qty"];

        Database::ud("UPDATE `book` SET `available_qty` = $available_qty - 1 WHERE `book_id` = '$book_id'");

        return true;
    }
}
