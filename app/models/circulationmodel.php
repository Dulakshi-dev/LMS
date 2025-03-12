<?php

require_once config::getdbPath();



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

    private static function getTotalSearchResults($bookid , $memberid)
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
        $id_result = Database::search("SELECT `id` FROM `member` INNER JOIN `member_login` ON `member`.`id` = `member_login`.`memberId` WHERE `member_id` = '$member_id'");

        if ($id_result->num_rows == 0) {
            return false;
        }

        $id_data = $id_result->fetch_assoc();
        $member_real_id = $id_data['id'];

        $result = Database::search("SELECT * FROM `reservation` WHERE `reservation_book_id` = '$book_id' AND `reservation_member_id` = '$member_id'");
        $num = $result->num_rows;

        if ($num > 0) {
            Database::insert("INSERT INTO `borrow`(`borrow_date`,`due_date`,`borrow_book_id`,`borrow_member_id`) 
                              VALUES('$borrow_date','$due_date','$book_id','$member_real_id')");

            Database::ud("UPDATE `reservation` SET `status_id` = '2' WHERE `reservation_book_id` = '$book_id' AND `reservation_member_id`='$member_id'");
        } else {
            $result = Database::search("SELECT `available_qty` FROM `book` WHERE `book_id` = '$book_id'");
            $data = $result->fetch_assoc();
            $available_qty = $data["available_qty"];

            if ($available_qty > 0) {
                Database::insert("INSERT INTO `borrow`(`borrow_date`,`due_date`,`borrow_book_id`,`borrow_member_id`) 
                                  VALUES('$borrow_date','$due_date','$book_id','$member_real_id')");

                Database::ud("UPDATE `book` SET `available_qty` = $available_qty - 1 WHERE `book_id` = '$book_id'");
            } else {
                return false;
            }
        }

        return true;
    }


    public static function returnBook($borrow_id, $return_date, $book_id, $fines, $memberId)
    {
        Database::ud("UPDATE `borrow` SET `return_date` = '$return_date' WHERE `borrow_id` = '$borrow_id'");

        if ($fines > 0) {
            Database::insert("INSERT INTO `fines`(`amount`,`fine_borrow_id`,`fine_member_id`) VALUES('$fines','$borrow_id','$memberId')");
        }

        $result = Database::search("SELECT `available_qty` FROM `book` WHERE `book_id` = '$book_id'");
        $data = $result->fetch_assoc();
        $available_qty = $data["available_qty"];

        Database::ud("UPDATE `book` SET `available_qty` = $available_qty + 1 WHERE `book_id` = '$book_id'");

        return true;
    }
}
