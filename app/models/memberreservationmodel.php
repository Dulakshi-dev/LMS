<?php

require_once config::getdbPath();

class MemberReservationModel
{
    public static function reserveBook($book_id, $id)
    {
        $rs = Database::search("SELECT * FROM `reservation` WHERE `reservation_member_id` = '$id' AND `reservation_book_id` = '$book_id' AND (`status_id` = '1' OR `status_id` = '5')");
        if ($rs->num_rows > 0) {
            return ["success" => false, "message" => "Book already reserved!"];
        }

        $rs2 = Database::search("SELECT * FROM `reservation` WHERE `reservation_member_id` ='$id' AND (`status_id` = '1' OR `status_id` = '5') ");
        if ($rs2->num_rows < 5) {
            Database::insert("INSERT INTO `reservation` (`reservation_date`, `expiration_date`, `reservation_member_id`, `reservation_book_id`, `status_id`) 
            VALUES (CURDATE(), DATE_ADD(CURDATE(), INTERVAL 7 DAY), '$id', '$book_id', '1')");

            $result = Database::search("SELECT `available_qty` FROM `book` WHERE `book_id` = '$book_id'");
            $data = $result->fetch_assoc();
            $available_qty = $data["available_qty"];

            Database::ud("UPDATE `book` SET `available_qty` = $available_qty - 1 WHERE `book_id` = '$book_id'");

            return ["success" => true, "message" => "Book reserved successfully!"];
        } else {
            return ["success" => false, "message" => "Reservation limit reached!"];
        }
    }

    public static function addToWaitlist($book_id, $member_id)
    {
        $rs = Database::search("SELECT * FROM `reservation` WHERE `reservation_member_id` ='$member_id' AND `reservation_book_id` = '$book_id'");

        if ($rs->num_rows > 0) {
            return ["success" => false, "message" => "Book already reserved!"];
        }

        Database::insert("INSERT INTO `reservation` (`reservation_date`, `reservation_member_id`, `reservation_book_id`, `status_id`) 
        VALUES (CURDATE(), '$member_id','$book_id', '5')");

        return ["success" => true, "message" => "Book is currently unavailable. You have been added to the waitlist."];
    }

    public static function getReservedBooks($id)
    {
        $rs = Database::search("SELECT * FROM `reservation` 
        INNER JOIN `book` ON `reservation`.`reservation_book_id` = `book`.`book_id` 
        INNER JOIN `reservation_status` ON `reservation`.`status_id` = `reservation_status`.`status_id` 
        WHERE `reservation_member_id` = '$id' AND (`reservation`.`status_id` = '1' OR `reservation`.`status_id`='5')");

        $reservations = [];

        while ($row = $rs->fetch_assoc()) {
            $reservations[] = $row;
        }
        return [
            'results' => $reservations
        ];
    }

    public static function deactivateExpiredReservations()
    {
        // Get all expired reservations (status = 1 means reserved)
        $result = Database::search("SELECT `reservation_book_id`,`reservation_member_id` FROM `reservation` 
        INNER JOIN `member` ON `reservation`.`reservation_member_id` = `member`.`id` 
        INNER JOIN `book` ON `reservation`.`reservation_book_id` = `book`.`book_id`  
        WHERE `expiration_date` < CURDATE() AND `status_id` = '1'");



        // Update expired reservations to status 3 (expired)
        Database::ud("UPDATE `reservation` SET `status_id` = '3' WHERE `expiration_date` < CURDATE() AND `status_id` = '1'");

        // Loop through each expired reservation and update book quantity
        while ($row = $result->fetch_assoc()) {
            $book_id = $row['reservation_book_id'];
            $title = $row['title'];
            $memberid = $row['reservation_member_id'];


            // Increase book quantity for the expired reservation
            Database::ud("UPDATE `book` SET `available_qty` = `available_qty` + 1 WHERE `book_id` = '$book_id'");
            self::sendReservationExpireEmail($book_id, $memberid, $title);
        }

        return true;
    }

    private static function sendReservationExpireEmail($book_id, $member_id, $title)
    {
        $member = Database::search("SELECT `fname`,`lname`,`email` FROM `member` WHERE `id` = '$member_id'")->fetch_assoc();
        $email = $member["email"];
        $name = $member["fname"] . '' . $member["lname"];

        $subject = "Reset Password";

        $body = '
           <h1 style="padding-top: 30px;">Book Reservation</h1>
           <p style = "font-size: 30px; color: black; font-weight: bold; text-align: center;">Shelf Loom</p> 

           <div style="max-width: 600px; margin: 0 auto; padding: 20px; text-align: left;">
              <p>Dear ' . $name . ',</p>
              <p>The book you reserved ' . $title . '(' . $book_id . ') is expired. </p>
              <div>
                    <p style="margin: 0px;">If you have problems or questions regarding your account, please contact us.</p>
                    <p style="margin: 0px;">Call: [tel_num]</p>
              </div>

              <div>
                    <p style="margin-bottom: 0px;">Best regards,</p>
                    <p style="margin: 0px;">Shelf Loom</p>
              </div>
           </div>';

        $emailService = new EmailService();
        $emailSent = $emailService->sendEmail($email, $subject, $body);

        if ($emailSent) {
            echo ("Email for reset sent successfully! Check Your email address");
        } else {
            echo ("Failed to send email.");
        }
    }

    public static function cancelReservation($id)
    {
        $result = Database::search("SELECT `status_id`,`reservation_book_id` FROM `reservation` WHERE `reservation_id` = '$id'");
        $row = $result->fetch_assoc();

        $status_id = $row["status_id"];
        $book_id = $row["reservation_book_id"];

        $result = Database::search("SELECT `available_qty` FROM `book` WHERE `book_id` = '$book_id'");
        $data = $result->fetch_assoc();
        $available_qty = $data["available_qty"];

        if ($status_id == '1') {
            Database::ud("UPDATE `book` SET `available_qty`=$available_qty + 1 WHERE `book_id` = '$book_id'");
        } 

        Database::ud("UPDATE `reservation` SET `status_id`='4' WHERE `reservation_id` = '$id'");
        return true;
    }
}
