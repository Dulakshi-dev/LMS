<?php

require_once config::getdbPath();

class ReservationModel
{
    public static function reserveBook($book_id, $id)
    {
        // Check if the member has already reserved this book and if it's active or pending
        $query = "SELECT * FROM `reservation` WHERE `reservation_member_id` = ? 
              AND `reservation_book_id` = ? AND (`status_id` = '1' OR `status_id` = '5')";
        $params = [$id, $book_id];
        $types = "ss";
        $rs = Database::search($query, $params, $types);

        if ($rs->num_rows > 0) {
            return ["success" => false, "message" => "Book already reserved!"];
        }

        // Check how many active reservations the member has
        $query2 = "SELECT * FROM `reservation` WHERE `reservation_member_id` = ? 
               AND (`status_id` = '1' OR `status_id` = '5')";
        $params2 = [$id];
        $types2 = "s";
        $rs2 = Database::search($query2, $params2, $types2);

        if ($rs2->num_rows < 5) { // Limit reservation count to 5

            // Insert a new reservation record with a 7-day expiration
            $insertQuery = "INSERT INTO `reservation` (`reservation_date`, `expiration_date`, 
                          `reservation_member_id`, `reservation_book_id`, `status_id`) 
                        VALUES (CURDATE(), DATE_ADD(CURDATE(), INTERVAL 7 DAY), ?, ?, '1')";
            $insertParams = [$id, $book_id];
            $insertTypes = "ss";
            Database::insert($insertQuery, $insertParams, $insertTypes);

            // Retrieve the available quantity of the book
            $selectQtyQuery = "SELECT `available_qty` FROM `book` WHERE `book_id` = ?";
            $selectQtyParams = [$book_id];
            $selectQtyTypes = "s";
            $result = Database::search($selectQtyQuery, $selectQtyParams, $selectQtyTypes);

            $data = $result->fetch_assoc();
            $available_qty = $data["available_qty"];

            // Reduce the available quantity by 1 since the book is now reserved
            $updateQtyQuery = "UPDATE `book` SET `available_qty` = ? WHERE `book_id` = ?";
            $updateQtyParams = [$available_qty - 1, $book_id];
            $updateQtyTypes = "is";
            Database::ud($updateQtyQuery, $updateQtyParams, $updateQtyTypes);

            return ["success" => true, "message" => "Book reserved successfully!"];
        } else {
            return ["success" => false, "message" => "Reservation limit reached!"];
        }
    }


    public static function addToWaitlist($book_id, $member_id)
    {
        // Check if the member has already reserved this book
        $query = "SELECT * FROM `reservation` WHERE `reservation_member_id` ='$member_id' AND `reservation_book_id` = '$book_id'";
        $params = [$member_id, $book_id];
        $types = "is";
        $rs = Database::search($query, $params, $types);

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

        $query = "SELECT * FROM `reservation` 
        INNER JOIN `book` ON `reservation`.`reservation_book_id` = `book`.`book_id` 
        INNER JOIN `reservation_status` ON `reservation`.`status_id` = `reservation_status`.`status_id` 
        WHERE `reservation_member_id` = ? AND (`reservation`.`status_id` = '1' OR `reservation`.`status_id`='5')";
        $params = [$id];
        $types = "i";
        $rs = Database::search($query, $params, $types);

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

        $query = "SELECT `reservation_book_id`,`reservation_member_id` FROM `reservation` 
        INNER JOIN `member` ON `reservation`.`reservation_member_id` = `member`.`id` 
        INNER JOIN `book` ON `reservation`.`reservation_book_id` = `book`.`book_id`  
        WHERE `expiration_date` < CURDATE() AND `reservation`.`status_id` = '1'";

        $result = Database::search($query);

        // Update expired reservations to status 3 (expired)

        $query = "UPDATE `reservation` SET `status_id` = '3' WHERE `expiration_date` < CURDATE() AND `status_id` = '1'";
        Database::ud($query);

        // Loop through each expired reservation and update book quantity
        while ($row = $result->fetch_assoc()) {
            $book_id = $row['reservation_book_id'];
            $title = $row['title'];
            $email = $row["email"];
            $name = $row["fname"] . '' . $row["lname"];

            // Increase book quantity for the expired reservation
            $query = "UPDATE `book` SET `available_qty` = `available_qty` + 1 WHERE `book_id` = ?";
            $params = [$book_id];
            $types = "s";
            Database::ud($query, $params, $types);

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

        // Escape variables for safe HTML output
        $safeTitle = htmlspecialchars($title, ENT_QUOTES, 'UTF-8');
        $safeBookId = htmlspecialchars($book_id, ENT_QUOTES, 'UTF-8');
        $safeName = htmlspecialchars($name, ENT_QUOTES, 'UTF-8');

        $specificMessage = "<h4>Your reservation for the book '<strong>$safeTitle</strong>' (ID: $safeBookId) has expired.</h4> 
        <p>This occurred because you did not complete the borrowing process within the specified time frame.</p>";

        $body = $emailTemplate->getEmailBody($safeName, $specificMessage);

        $emailSent = $emailService->sendEmail($email, $subject, $body);
        $notification = $notificationController->insertNotification($email, strip_tags($specificMessage));

        if (!$emailSent) {
            error_log("Failed to send email to: " . $email);
        }

        if (!$notification) {
            error_log("Failed to send notification to: $safeName");
        }
    }


    public static function getReservationsToExpire()
    {
        $query = "SELECT `reservation_book_id`, `reservation_member_id`, `title`
        FROM `reservation`
        INNER JOIN `member` ON `reservation`.`reservation_member_id` = `member`.`id`
        INNER JOIN `book` ON `reservation`.`reservation_book_id` = `book`.`book_id`
        WHERE `expiration_date` = CURDATE() + INTERVAL 2 DAY AND `reservation`.`status_id` = '1'";
        $result = Database::search($query);

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

        // Escape variables for safe HTML output
        $safeTitle = htmlspecialchars($title, ENT_QUOTES, 'UTF-8');
        $safeBookId = htmlspecialchars($book_id, ENT_QUOTES, 'UTF-8');
        $safeName = htmlspecialchars($name, ENT_QUOTES, 'UTF-8');
        $safeExpirationDate = htmlspecialchars($expirationDate, ENT_QUOTES, 'UTF-8');

        $specificMessage = "<h4>Your reservation for the book '<strong>$safeTitle</strong>' (ID: $safeBookId) is about to expire on $safeExpirationDate.</h4> 
        <p>Please make sure to collect the book before $safeExpirationDate.</p>";

        $body = $emailTemplate->getEmailBody($safeName, $specificMessage);

        $emailSent = $emailService->sendEmail($email, $subject, $body);
        $notification = $notificationController->insertNotification($email, strip_tags($specificMessage));

        if (!$emailSent) {
            error_log("Failed to send email to: " . $email);
        }

        if (!$notification) {
            error_log("Failed to send notification to: $safeName");
        }
    }
    /**
     * cancelReservation
     * Cancels a reservation and restores book quantity if active.
     */

    public static function cancelReservation($id)
    {

        $query = "SELECT `status_id`,`reservation_book_id` FROM `reservation` WHERE `reservation_id` = ?";
        $params = [$id];
        $types = "i";
        $result = Database::search($query, $params, $types);

        $row = $result->fetch_assoc();

        $status_id = $row["status_id"];
        $book_id = $row["reservation_book_id"];

        $query = "SELECT `available_qty` FROM `book` WHERE `book_id` = ?";
        $params = [$book_id];
        $types = "s";
        $result = Database::search($query, $params, $types);


        $data = $result->fetch_assoc();
        $available_qty = $data["available_qty"];

        if ($status_id == '1') {
            $query = "UPDATE `book` SET `available_qty`=? + 1 WHERE `book_id` = ?";
            $params = [$available_qty, $book_id];
            $types = "is";
            Database::ud($query, $params, $types);
        }


        $query = "UPDATE `reservation` SET `status_id`='4' WHERE `reservation_id` = ?";
        $params = [$id];
        $types = "i";
        Database::ud($query, $params, $types);

        return true;
    }
}
