<?php

require_once config::getdbPath();

class ReservationModel
{
    public static function reserveBook($book_id, $id)
    {
        // Check if the member has already reserved this book and if it's active or pending
        $rs = Database::search("SELECT * FROM `reservation` WHERE `reservation_member_id` = '$id' 
        AND `reservation_book_id` = '$book_id' AND (`status_id` = '1' OR `status_id` = '5')");

        if ($rs->num_rows > 0) {
            return ["success" => false, "message" => "Book already reserved!"];
        }

        // Check how many active reservations the member has
        $rs2 = Database::search("SELECT * FROM `reservation` WHERE `reservation_member_id` ='$id' 
        AND (`status_id` = '1' OR `status_id` = '5') ");

        if ($rs2->num_rows < 5) { // Limit reservation count to 5

            // Insert a new reservation record with a 7-day expiration
            Database::insert("INSERT INTO `reservation` (`reservation_date`, `expiration_date`, 
            `reservation_member_id`, `reservation_book_id`, `status_id`) 
            VALUES (CURDATE(), DATE_ADD(CURDATE(), INTERVAL 7 DAY), '$id', '$book_id', '1')");

            // Retrieve the available quantity of the book
            $result = Database::search("SELECT `available_qty` FROM `book` WHERE `book_id` = '$book_id'");
            $data = $result->fetch_assoc();
            $available_qty = $data["available_qty"];

            // Reduce the available quantity by 1 since the book is now reserved
            Database::ud("UPDATE `book` SET `available_qty` = $available_qty - 1 WHERE `book_id` = '$book_id'");

            return ["success" => true, "message" => "Book reserved successfully!"];
        } else {
            return ["success" => false, "message" => "Reservation limit reached!"];
        }
    }

    public static function addToWaitlist($book_id, $member_id)
    {
        // Check if the member has already reserved this book
        $rs = Database::search("SELECT * FROM `reservation` WHERE `reservation_member_id` ='$member_id' 
        AND `reservation_book_id` = '$book_id'");

        if ($rs->num_rows > 0) {
            return ["success" => false, "message" => "Book already reserved!"];
        }

        // Add the member to the waitlist since the book is unavailable
        Database::insert("INSERT INTO `reservation` (`reservation_date`, `reservation_member_id`,
        `reservation_book_id`, `status_id`) VALUES (CURDATE(), '$member_id','$book_id', '5')");

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
        WHERE `expiration_date` < CURDATE() AND `reservation`.`status_id` = '1'");

        // Update expired reservations to status 3 (expired)
        Database::ud("UPDATE `reservation` SET `status_id` = '3' WHERE `expiration_date` < CURDATE() AND `status_id` = '1'");

        // Loop through each expired reservation and update book quantity
        while ($row = $result->fetch_assoc()) {
            $book_id = $row['reservation_book_id'];
            $title = $row['title'];
            $email = $row["email"];
            $name = $row["fname"] . '' . $row["lname"];

            // Increase book quantity for the expired reservation
            Database::ud("UPDATE `book` SET `available_qty` = `available_qty` + 1 WHERE `book_id` = '$book_id'");
            self::sendReservationExpiredEmail($book_id, $title, $email, $name);
        }

        return true;
    }

    private static function sendReservationExpiredEmail($book_id, $title, $email, $name)
    {
        $emailService = new EmailService();
        $emailTemplate = new EmailTemplate();
        $notificationController = new NotificationController();

        $subject = "Book Reservation Expired";

        $specificMessage = "<h4>Your reservation for the book '<strong>$title</strong>' (ID: $book_id) has expired.</h4> 
        <p>This occurred because you did not complete the borrowing process within the specified time frame.</p>";

        $body = $emailTemplate->getEmailBody($name, $specificMessage);

        $emailSent = $emailService->sendEmail($email, $subject, $body);
        $notification = $notificationController->insertNotification($email, strip_tags($specificMessage));

        if (!$emailSent) {
            error_log("Failed to send email to: " . $email);
        }

        if (!$notification) {
            error_log("Failed to send notification to: $name ");
        }
    }

    public static function getReservationsToExpire()
    {
        $result = Database::search("SELECT `reservation_book_id`, `reservation_member_id`, `title`
        FROM `reservation`
        INNER JOIN `member` ON `reservation`.`reservation_member_id` = `member`.`id`
        INNER JOIN `book` ON `reservation`.`reservation_book_id` = `book`.`book_id`
        WHERE `expiration_date` = CURDATE() + INTERVAL 2 DAY AND `reservation`.`status_id` = '1'");

        while ($row = $result->fetch_assoc()) {
            $book_id = $row['reservation_book_id'];
            $title = $row['title'];
            $email = $row["email"];
            $name = $row["fname"] . '' . $row["lname"];
            $expirationDate = $row["expiration_date"];

            self::sendReseravationExpirationReminder($book_id, $title, $email, $name, $expirationDate);
        }
    }

    public static function sendReseravationExpirationReminder($book_id, $title, $email, $name, $expirationDate)
    {
        require_once Config::getServicePath('emailService.php');
        $emailService = new EmailService();
        $emailTemplate = new EmailTemplate();
        $notificationController = new NotificationController();

        $subject = "Book Reservation Reminder";

        $specificMessage = "<h4>Your reservation for the book '<strong>$title</strong>' (ID: $book_id) is about to expire on $expirationDate.</h4> 
        <p>Please make sure to collect the book before $expirationDate.</p>";

        $body = $emailTemplate->getEmailBody($name, $specificMessage);

        $emailSent = $emailService->sendEmail($email, $subject, $body);
        $notification = $notificationController->insertNotification($email, strip_tags($specificMessage));

        if (!$emailSent) {
            error_log("Failed to send email to: " . $email);
        }

        if (!$notification) {
            error_log("Failed to send notification to: $name ");
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
