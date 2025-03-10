<?php

require_once config::getdbPath();

class MemberReservationModel
{
    public static function reserveBook($book_id, $id)
    {

        $rs = Database::search("SELECT * FROM `reservation` WHERE `reservation_member_id` ='$id' AND `reservation_book_id` = '$book_id'");
        if ($rs->num_rows > 0) {
            return ["success" => false, "message" => "Book already reserved!"];
        }

        $rs2 = Database::search("SELECT * FROM `reservation` WHERE `reservation_member_id` ='$id'");
        if ($rs2->num_rows < 5) {
            Database::insert("INSERT INTO `reservation` (`reservation_date`, `expiration_date`, `reservation_member_id`, `reservation_book_id`, `status_id`) 
            VALUES (CURDATE(), DATE_ADD(CURDATE(), INTERVAL 7 DAY), '$id', '$book_id', '1')");

            $result = Database::search("SELECT `available_qty` FROM `book` WHERE `book_id` = '$book_id'");
            $data = $result->fetch_assoc();
            $available_qty = $data["available_qty"];

            Database::ud("UPDATE `book` SET `available_qty` = $available_qty - 1 WHERE `book_id` = '$book_id'");

            return ["success" => true, "message" => "Book reserved successfully!"];
        }else{
            return ["success" => false, "message" => "Reservation limit reached!"];
        }
    }

    public static function getReservedBooks($id)
    {

        $rs = Database::search("SELECT * FROM `reservation` INNER JOIN `book` ON `reservation`.`reservation_book_id` = `book`.`book_id` INNER JOIN `reservation_status` ON `reservation`.`status_id` = `reservation_status`.`status_id` WHERE `reservation_member_id` = '$id' AND `reservation`.`status_id` = '1';");
        $num = $rs->num_rows;
        return [
            'total' => $num,
            'results' => $rs
        ];
    }

    public static function deactivateExpiredReservations()
    {
        // Get all expired reservations (status = 1 means reserved)
        $result = Database::search("SELECT `reservation_book_id` FROM `reservation` WHERE `expiration_date` < CURDATE() AND `status_id` = '1'");

        // Update expired reservations to status 3 (expired)
        Database::ud("UPDATE `reservation` SET `status_id` = '3' WHERE `expiration_date` < CURDATE() AND `status_id` = '1'");

        // Loop through each expired reservation and update book quantity
        while ($row = $result->fetch_assoc()) {
            $book_id = $row['reservation_book_id'];

            // Increase book quantity for the expired reservation
            Database::ud("UPDATE `book` SET `available_qty` = `available_qty` + 1 WHERE `book_id` = '$book_id'");
        }

        return true;
    }
}
