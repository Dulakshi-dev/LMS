<?php

require_once config::getdbPath();
require_once Config::getServicePath('emailService.php');
require_once Config::getMailPath('emailTemplate.php');

class CirculationModel
{

    private $db;

    public function __construct()
    {
        $this->db = new Database();
    }

    public static function getAllBorrowData($page, $resultsPerPage)
    {
        $pageResults = ($page - 1) * $resultsPerPage;
        $totalBooks = self::getTotalBorrowData();
        $rs = Database::search("SELECT * FROM `borrow` 
INNER JOIN `book` ON `borrow`.`borrow_book_id` = `book`.`book_id` 
INNER JOIN `member` ON `borrow`.`borrow_member_id` = `member`.`id` 
INNER JOIN `member_login` ON `member_login`.`memberId` = `member`.`id`
LEFT JOIN `fines` ON `borrow`.`borrow_id` = `fines`.`fine_borrow_id`
        LIMIT $resultsPerPage OFFSET $pageResults");

        $books = [];

        while ($row = $rs->fetch_assoc()) {
            $books[] = $row;
        }
        return [
            'total' => $totalBooks,
            'results' => $books
        ];
    }

    private static function getTotalBorrowData()
    {
        $result = Database::search("SELECT COUNT(*) AS total FROM `borrow` 
INNER JOIN `book` ON `borrow`.`borrow_book_id` = `book`.`book_id` 
INNER JOIN `member` ON `borrow`.`borrow_member_id` = `member`.`id` 
INNER JOIN `member_login` ON `member_login`.`memberId` = `member`.`id`
LEFT JOIN `fines` ON `borrow`.`borrow_id` = `fines`.`fine_borrow_id`");
        $row = $result->fetch_assoc();
        return $row['total'] ?? 0;
    }

    public static function searchBorrowData($bookid, $memberid, $page, $resultsPerPage)
    {
        $pageResults = ($page - 1) * $resultsPerPage;
        $totalSearch = self::getTotalSearchResults($bookid, $memberid);

        $sql = "SELECT * FROM `borrow` 
INNER JOIN `book` ON `borrow`.`borrow_book_id` = `book`.`book_id` 
INNER JOIN `member` ON `borrow`.`borrow_member_id` = `member`.`id` 
INNER JOIN `member_login` ON `member_login`.`memberId` = `member`.`id`
LEFT JOIN `fines` ON `borrow`.`borrow_id` = `fines`.`fine_borrow_id` WHERE 1";

        if (!empty($bookid)) {
            $sql .= " AND `book_id` LIKE '%$bookid%'";
        }
        if (!empty($memberid)) {
            $sql .= " AND `member_id` LIKE '%$memberid%'";
        }
        $sql .= "LIMIT $resultsPerPage OFFSET $pageResults";

        $rs = Database::search($sql);
        $books = [];
        while ($row = $rs->fetch_assoc()) {
            $books[] = $row;
        }
        return ['results' => $books, 'total' => $totalSearch];
    }

    private static function getTotalSearchResults($bookid, $memberid)
    {
        $countQuery = "SELECT COUNT(*) as total FROM `borrow` 
INNER JOIN `book` ON `borrow`.`borrow_book_id` = `book`.`book_id` 
INNER JOIN `member` ON `borrow`.`borrow_member_id` = `member`.`id` 
INNER JOIN `member_login` ON `member_login`.`memberId` = `member`.`id`
LEFT JOIN `fines` ON `borrow`.`borrow_id` = `fines`.`fine_borrow_id` WHERE 1";

        if (!empty($bookid)) {
            $countQuery .= " AND `book_id` LIKE '%$bookid%'";
        }
        if (!empty($memberid)) {
            $countQuery .= " AND `member_id` LIKE '%$memberid%'";
        }

        $result = Database::search($countQuery);
        $row = $result->fetch_assoc();
        return $row['total'] ?? 0;
    }

    public static function getBookDetails($id)
    {
        $rs = Database::search("SELECT * FROM `book` INNER JOIN `category` ON `book`.`category_id` = `category`.`category_id`INNER JOIN `status` ON `book`.`status_id` = `status`.`status_id` WHERE `book_id` = '$id'");
        return $rs;
    }

    public static function getMemberDetails($id)
    {
        $rs = Database::search("SELECT * FROM `member` INNER JOIN `member_login` ON `member`.`id` = `member_login`.`memberId` WHERE `member_id` = '$id'");
        return $rs;
    }


    public static function issueBook($book_id, $member_id, $borrow_date, $due_date)
    {
        // Verify if the member exists in the system
        $id_result = Database::search("SELECT `id` FROM `member` 
        INNER JOIN `member_login` ON `member`.`id` = `member_login`.`memberId` WHERE `member_id` = '$member_id'");
    
        if ($id_result->num_rows == 0) {
            return ["success" => false, "message" => "Member does not exist."];
        }
    
        // Fetch the member ID from the database result
        $id_data = $id_result->fetch_assoc();
        $memberid = $id_data['id'];
    
        // Check if the member has already borrowed this book and has not returned it yet
        $borrow_check = Database::search("SELECT * FROM `borrow` 
                                          WHERE `borrow_book_id` = '$book_id' 
                                          AND `borrow_member_id` = '$memberid' 
                                          AND `return_date` IS NULL");
    
        if ($borrow_check->num_rows > 0) {
            // The member has already borrowed the book and not returned it yet
            return ["success" => false, "message" => "Member has already borrowed this book and has not returned it yet."];
        }
    
        // Check if the member has reserved this book
        $result = Database::search("SELECT * FROM `reservation` 
                                    WHERE `reservation_book_id` = '$book_id' 
                                    AND `reservation_member_id` = '$memberid'");
    
        $num = $result->num_rows;
    
        // If the book is reserved by the member
        if ($num > 0) {
            // Issue the book by inserting a record into the `borrow` table
            Database::insert("INSERT INTO `borrow`(`borrow_date`, `due_date`, `borrow_book_id`, `borrow_member_id`) 
                              VALUES('$borrow_date', '$due_date', '$book_id', '$memberid')");
    
            // Update the reservation status to indicate the book has been borrowed
            Database::ud("UPDATE `reservation` SET `status_id` = '2' 
                        WHERE `reservation_book_id` = '$book_id' 
                        AND `reservation_member_id` = '$memberid'");
        } else { // If the book is not reserved, check availability
            $result = Database::search("SELECT `available_qty` FROM `book` WHERE `book_id` = '$book_id'");
            $data = $result->fetch_assoc();
            $available_qty = $data["available_qty"];
    
            // If copies are available
            if ($available_qty > 0) {
                // Issue the book by inserting a record into the `borrow` table
                Database::insert("INSERT INTO `borrow`(`borrow_date`, `due_date`, `borrow_book_id`, `borrow_member_id`) 
                                  VALUES('$borrow_date', '$due_date', '$book_id', '$memberid')");
    
                // Reduce the available quantity of the book by 1
                Database::ud("UPDATE `book` SET `available_qty` = $available_qty - 1 WHERE `book_id` = '$book_id'");
            } else {
                return ["success" => false, "message" => "No copies available for this book."];
            }
        }
        return ["success" => true, "message" => "Book issued successfully."];
    }
    


    public static function returnBook($borrow_id, $return_date, $book_id, $fines, $memberId)
    {
        // Update the borrow record with the return date
        Database::ud("UPDATE `borrow` SET `return_date` = '$return_date' WHERE `borrow_id` = '$borrow_id'");

        // If there is a fine, insert the fine record into the database
        if ($fines > 0) {
            Database::insert("INSERT INTO `fines`(`amount`,`fine_borrow_id`,`fine_member_id`) 
            VALUES('$fines','$borrow_id','$memberId')");
        }

        // Get the current available quantity of the book
        $result = Database::search("SELECT `available_qty` FROM `book` WHERE `book_id` = '$book_id'");
        $data = $result->fetch_assoc();
        $available_qty = $data["available_qty"];

        // Increase the available quantity of the returned book
        Database::ud("UPDATE `book` SET `available_qty` = $available_qty + 1 WHERE `book_id` = '$book_id'");
        return true;
    }

    public static function notifyNextWaitlistMember($book_id)
    {
        // Include the email service

        // Retrieve the next member on the waitlist (oldest reservation first)
        $waitlist = Database::search("SELECT * FROM `reservation` 
                                  WHERE `reservation_book_id` = '$book_id' AND `status_id` = 5
                                  ORDER BY `reservation_date` ASC 
                                  LIMIT 1");

        if ($waitlist->num_rows > 0) {
            $reservation = $waitlist->fetch_assoc();
            $reservation_id = $reservation["reservation_id"];
            $reserved_member_id = $reservation["reservation_member_id"];

            //Update reservation status to "reserved" 
            Database::ud("UPDATE `reservation` 
                      SET `status_id` = 1,`notified_date` = CURDATE(),`expiration_date` = DATE_ADD(CURDATE(), INTERVAL 3 DAY) 
                      WHERE `reservation_id` = '$reservation_id'");

            // Get book details
            $book = Database::search("SELECT `title`,`available_qty` FROM `book` WHERE `book_id` = '$book_id'")->fetch_assoc();
            $title = $book["title"];
            $available_qty = $book["available_qty"];

            // Send email notification to the reserved member
            self::sendReservationEmail($book_id, $reserved_member_id, $title);

            // Reduce the available book quantity as it is now reserved
            Database::ud("UPDATE `book` SET `available_qty` = $available_qty - 1 WHERE `book_id` = '$book_id'");
            return ["success" => true, "message" => "Book status changed to reserved."];
        }

        return ["success" => false, "message" => "No waitlisted reservations found."];
    }

    private static function sendReservationEmail($book_id, $member_id, $title)
{
    $member = Database::search("SELECT `fname`,`lname`,`email` FROM `member` WHERE `id` = '$member_id'")->fetch_assoc();
    $email = $member["email"];
    $recipientName = $member["fname"] . ' ' . $member["lname"];

    $subject = "Book Reservation Available - Shelf Loom";

    // Create the specific message content for this email type
    $specificMessage = 'The book you reserved, "' . $title . '" (ID: ' . $book_id . '), is now available for pickup. Please collect it within the next 3 days, or your reservation will expire.';

    // Use the EmailTemplate to get the standardized email body
    $emailTemplate = new EmailTemplate();
    $body = $emailTemplate->getEmailBody($recipientName, $specificMessage);

    // Send the email
    $emailService = new EmailService();
    $emailSent = $emailService->sendEmail($email, $subject, $body);

    if ($emailSent) {
        echo ("Email for reservation sent successfully! Check your email address.");
    } else {
        echo ("Failed to send email.");
    }
}
}
