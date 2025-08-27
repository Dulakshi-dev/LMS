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

        $query = "SELECT * FROM `borrow` 
INNER JOIN `book` ON `borrow`.`borrow_book_id` = `book`.`book_id` 
INNER JOIN `member` ON `borrow`.`borrow_member_id` = `member`.`id` 
INNER JOIN `member_login` ON `member_login`.`memberId` = `member`.`id`
LEFT JOIN `fines` ON `borrow`.`borrow_id` = `fines`.`fine_borrow_id`
        LIMIT ? OFFSET ?";
        $params = [$resultsPerPage, $pageResults];
        $types = "ii";
        $rs = Database::search($query, $params, $types);

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

        $query = "SELECT COUNT(*) AS total FROM `borrow` 
INNER JOIN `book` ON `borrow`.`borrow_book_id` = `book`.`book_id` 
INNER JOIN `member` ON `borrow`.`borrow_member_id` = `member`.`id` 
INNER JOIN `member_login` ON `member_login`.`memberId` = `member`.`id`
LEFT JOIN `fines` ON `borrow`.`borrow_id` = `fines`.`fine_borrow_id`";

        $result = Database::search($query);

        $row = $result->fetch_assoc();
        return $row['total'] ?? 0;
    }

    public static function searchBorrowData($bookid, $memberid, $status, $page, $resultsPerPage)
    {
        $pageResults = ($page - 1) * $resultsPerPage;
        $totalSearch = self::getTotalSearchResults($bookid, $memberid, $status);

        $sql = "SELECT * FROM `borrow` 
        INNER JOIN `book` ON `borrow`.`borrow_book_id` = `book`.`book_id` 
        INNER JOIN `member` ON `borrow`.`borrow_member_id` = `member`.`id` 
        INNER JOIN `member_login` ON `member_login`.`memberId` = `member`.`id`
        LEFT JOIN `fines` ON `borrow`.`borrow_id` = `fines`.`fine_borrow_id`
        WHERE 1";

        $params = [];
        $types  = "";

        if (!empty($bookid)) {
            $sql .= " AND `book`.`book_id` LIKE ?";
            $params[] = "%$bookid%";
            $types   .= "s";
        }
        if (!empty($memberid)) {
            $sql .= " AND `member`.`id` LIKE ?";
            $params[] = "%$memberid%";
            $types   .= "s";
        }

        // Dynamically determine status
        switch ($status) {
            case 'status2': // Returned
                $sql .= " AND `return_date` IS NOT NULL";
                break;
            case 'status3': // Due
                $sql .= " AND `return_date` IS NULL AND `due_date` >= CURDATE()";
                break;
            case 'status4': // Over Due
                $sql .= " AND `return_date` IS NULL AND `due_date` < CURDATE()";
                break;
            default:
                // status1 or empty → All, no additional condition
                break;
        }

        $sql .= " ORDER BY `borrow`.`borrow_id` DESC LIMIT ? OFFSET ?";
        $params[] = $resultsPerPage;
        $params[] = $pageResults;
        $types   .= "ii";

        $rs = Database::search($sql, $params, $types);

        $books = [];
        while ($row = $rs->fetch_assoc()) {
            $books[] = $row;
        }

        return ['results' => $books, 'total' => $totalSearch];
    }


    private static function getTotalSearchResults($bookid, $memberid, $status)
    {
        // Base query
        $countQuery = "SELECT COUNT(*) AS total 
                   FROM borrow 
                   INNER JOIN book ON borrow.borrow_book_id = book.book_id 
                   INNER JOIN member ON borrow.borrow_member_id = member.id 
                   INNER JOIN member_login ON member_login.memberId = member.id 
                   LEFT JOIN fines ON borrow.borrow_id = fines.fine_borrow_id 
                   WHERE 1";

        $params = [];
        $types = "";

        // Optional filters
        if (!empty($bookid)) {
            $countQuery .= " AND book_id LIKE ?";
            $params[] = "%$bookid%";
            $types .= "s";
        }
        if (!empty($memberid)) {
            $countQuery .= " AND member_id LIKE ?";
            $params[] = "%$memberid%";
            $types .= "s";
        }

        // Status filter
        switch ($status) {
            case 'status2': // Returned
                $countQuery .= " AND return_date IS NOT NULL";
                break;
            case 'status3': // Due
                $countQuery .= " AND return_date IS NULL AND due_date >= CURDATE()";
                break;
            case 'status4': // Over Due
                $countQuery .= " AND return_date IS NULL AND due_date < CURDATE()";
                break;
            default:
                // status1 or empty → All, no additional condition
                break;
        }

        // Execute query with prepared statement
        $result = Database::search($countQuery, $params, $types);
        $row = $result->fetch_assoc();

        return $row['total'] ?? 0;
    }



    public static function getBookDetails($id)
    {
        $query = "SELECT * FROM `book` INNER JOIN `category` ON `book`.`category_id` = `category`.`category_id`INNER JOIN `status` ON `book`.`status_id` = `status`.`status_id` WHERE `book_id` = ?";
        $params = [$id];
        $types = "s";
        $rs = Database::search($query, $params, $types);
        return $rs;
    }

    public static function getMemberDetails($id)
    {
        $query = "SELECT * FROM `member` INNER JOIN `member_login` ON `member`.`id` = `member_login`.`memberId` WHERE `member_id` = ?";
        $params = [$id];
        $types = "s";
        $rs = Database::search($query, $params, $types);
        return $rs;
    }


    public static function issueBook($book_id, $member_id, $borrow_date, $due_date)
    {
        // Verify if the member exists in the system
        $query = "SELECT `id` FROM `member` 
        INNER JOIN `member_login` ON `member`.`id` = `member_login`.`memberId` WHERE `member_id` = ?";
        $params = [$member_id];
        $types = "s";
        $id_result = Database::search($query, $params, $types);

        if ($id_result->num_rows == 0) {
            return ["success" => false, "message" => "Member does not exist."];
        }

        // Fetch the member ID from the database result
        $id_data = $id_result->fetch_assoc();
        $memberid = $id_data['id'];

        // Check if the member has already borrowed this book and has not returned it yet
        $query = "SELECT * FROM `borrow` 
                WHERE `borrow_book_id` = ? 
                AND `borrow_member_id` = ? 
                AND `return_date` IS NULL";
        $params = [$book_id, $memberid];
        $types = "si";
        $borrow_check = Database::search($query, $params, $types);

        if ($borrow_check->num_rows > 0) {
            // The member has already borrowed the book and not returned it yet
            return ["success" => false, "message" => "Member has already borrowed this book and has not returned it yet."];
        }

        // Check if the member has reserved this book

        $query = "SELECT * FROM `reservation` 
                WHERE `reservation_book_id` = ? 
                AND `reservation_member_id` = ?";
        $params = [$book_id, $memberid];
        $types = "si";
        $result = Database::search($query, $params, $types);

        $num = $result->num_rows;

        // If the book is reserved by the member
        if ($num > 0) {
            // Issue the book by inserting a record into the `borrow` table
            $query = "INSERT INTO `borrow`(`borrow_date`, `due_date`, `borrow_book_id`, `borrow_member_id`) 
                              VALUES(?, ?, ?, ?)";
            $params = [$borrow_date, $due_date, $book_id, $memberid];
            $types = "sssi";
            Database::insert($query, $params, $types);

            // Update the reservation status to indicate the book has been borrowed
            $query = "UPDATE `reservation` SET `status_id` = '2' 
                        WHERE `reservation_book_id` = ? 
                        AND `reservation_member_id` = ?";
            $params = [$book_id, $memberid];
            $types = "si";
            Database::ud($query, $params, $types);
        } else { // If the book is not reserved, check availability
            $query = "SELECT `available_qty` FROM `book` WHERE `book_id` = ?";
            $params = [$book_id];
            $types = "s";
            $result =  Database::search($query, $params, $types);


            $data = $result->fetch_assoc();
            $available_qty = $data["available_qty"];

            // If copies are available
            if ($available_qty > 0) {
                // Issue the book by inserting a record into the `borrow` table
                $query = "INSERT INTO `borrow`(`borrow_date`, `due_date`, `borrow_book_id`, `borrow_member_id`) 
                                  VALUES(?, ?, ?, ?)";
                $params = [$borrow_date, $due_date, $book_id, $memberid];
                $types = "sssi";
                Database::insert($query, $params, $types);

                // Reduce the available quantity of the book by 1
                $query = "UPDATE `book` SET `available_qty` = ? - 1 WHERE `book_id` = ?";
                $params = [$available_qty, $book_id];
                $types = "is";
                Database::ud($query, $params, $types);
            } else {
                return ["success" => false, "message" => "No copies available for this book."];
            }
        }
        return ["success" => true, "message" => "Book issued successfully."];
    }

    public static function generateFineID()
    {
        // get the latest fine_id
        $query = "SELECT fine_id FROM `fines` ORDER BY fine_id DESC LIMIT 1";
        $result = Database::search($query);

        if ($result->num_rows > 0) {
            $row = $result->fetch_assoc();
            $lastFineID = (int)$row['fine_id'];

            $newNumber = $lastFineID + 1;
        } else {
            $newNumber = 1;
        }

        // Format as F000001
        $newFineID = "F" . str_pad($newNumber, 6, "0", STR_PAD_LEFT);
        return $newFineID;
    }


    public static function returnBook($borrow_id, $return_date, $book_id, $fines, $memberId)
    {
        // Update the borrow record with the return date
        $query = "UPDATE `borrow` SET `return_date` = ? WHERE `borrow_id` = ?";
        $params = [$return_date, $borrow_id];
        $types = "si";
        Database::ud($query, $params, $types);


        $fineID = self::generateFineID();

        // If there is a fine, insert the fine record into the database
        if ($fines > 0) {
            $today = date("Y-m-d");

            $query = "INSERT INTO `fines`(`fine_id`, `amount`, `fine_borrow_id`, `payed_on`) 
        VALUES(?, ?, ?, ?)";
            $params = [$fineID, $fines, $borrow_id, $today];
            $types = "sdis";
            Database::search($query, $params, $types);
        }


        // Get the current available quantity of the book

        $query = "SELECT `available_qty` FROM `book` WHERE `book_id` = ?";
        $params = [$book_id];
        $types = "s";
        $result = Database::search($query, $params, $types);

        $data = $result->fetch_assoc();
        $available_qty = $data["available_qty"];

        // Increase the available quantity of the returned book
        $query = "UPDATE `book` SET `available_qty` = ? + 1 WHERE `book_id` = ?";
        $params = [$available_qty, $book_id];
        $types = "is";
        Database::ud($query, $params, $types);
        return true;
    }

    public static function notifyNextWaitlistMember($book_id)
    {
        // Include the email service

        // Retrieve the next member on the waitlist (oldest reservation first)
        $query = "SELECT * FROM `reservation` 
                                  WHERE `reservation_book_id` = ? AND `status_id` = 5
                                  ORDER BY `reservation_date` ASC 
                                  LIMIT 1";
        $params = [$book_id];
        $types = "s";
        $waitlist = Database::search($query, $params, $types);

        if ($waitlist->num_rows > 0) {
            $reservation = $waitlist->fetch_assoc();
            $reservation_id = $reservation["reservation_id"];
            $reserved_member_id = $reservation["reservation_member_id"];

            //Update reservation status to "reserved" 
            $query = "UPDATE `reservation` 
                      SET `status_id` = 1,`notified_date` = CURDATE(),`expiration_date` = DATE_ADD(CURDATE(), INTERVAL 3 DAY) 
                      WHERE `reservation_id` = ?";
            $params = [$reservation_id];
            $types = "i";
            Database::ud($query, $params, $types);

            // Get book details
            $query = "SELECT `title`, `available_qty` FROM `book` WHERE `book_id` = ?";
            $params = [$book_id];
            $types = "s";
            $result = Database::search($query, $params, $types);

            $book = $result->fetch_assoc();
            $title = $book["title"];
            $available_qty = $book["available_qty"];


            // Send email notification to the reserved member
            self::sendReservationEmail($book_id, $reserved_member_id, $title);

            // Reduce the available book quantity as it is now reserved
            $query = "UPDATE `book` SET `available_qty` = ? - 1 WHERE `book_id` = ?";
            $params = [$available_qty, $book_id];
            $types = "is";
            Database::ud($query, $params, $types);

            return ["success" => true, "message" => "Book status changed to reserved."];
        }

        return ["success" => false, "message" => "No waitlisted reservations found."];
    }

    private static function sendReservationEmail($book_id, $member_id, $title)
    {
        $query = "SELECT `fname`,`lname`,`email` FROM `member` WHERE `id` = ?";
        $params = [$member_id];
        $types = "i";
        $result = Database::search($query, $params, $types);

        $member = $result->fetch_assoc();
        $email = filter_var($member["email"], FILTER_SANITIZE_EMAIL);

        // HTML-escape all dynamic content
        $recipientName = htmlspecialchars($member["fname"] . ' ' . $member["lname"], ENT_QUOTES, 'UTF-8');
        $bookTitleEscaped = htmlspecialchars($title, ENT_QUOTES, 'UTF-8');
        $bookIdEscaped = htmlspecialchars($book_id, ENT_QUOTES, 'UTF-8');

        $subject = "Book Reservation Available - Shelf Loom";

        // Construct email message safely
        $specificMessage = 'The book you reserved, "' . $bookTitleEscaped . '" (ID: ' . $bookIdEscaped . '), is now available for pickup. Please collect it within the next 3 days, or your reservation will expire.';

        $emailTemplate = new EmailTemplate();
        $body = $emailTemplate->getEmailBody($recipientName, $specificMessage);

        $emailService = new EmailService();
        $emailSent = $emailService->sendEmail($email, $subject, $body);

        if ($emailSent) {
            echo ("Email for reservation sent successfully! Check your email address.");
        } else {
            echo ("Failed to send email.");
        }
    }
}
