<?php

require_once config::getdbPath();



class CirculationModel
{

    private $db;

    public function __construct()
    {
        $this->db = new Database();
    }

    public static function getBookDetails($id)
    {
        $rs = Database::search("SELECT * FROM `book` INNER JOIN `category` ON `book`.`category_id` = `category`.`category_id`INNER JOIN `status` ON `book`.`status_id` = `status`.`status_id` WHERE `book_id` = '$id'");
        return $rs;
    }

    public static function getMemberDetails($id)
    {
        $rs = Database::search("SELECT * FROM `member` WHERE `member_id` = '$id'");
        return $rs;
    }

    public static function issueBook($book_id, $member_id, $borrow_date, $due_date)
    {
        Database::insert("INSERT INTO `borrow`(`borrow_date`,`due_date`,`book_id`,`member_id`) VALUES('$borrow_date','$due_date','$book_id','$member_id')");
     
        $result= Database::search("SELECT `available_qty` FROM `book` WHERE `book_id` = '$book_id'");
        $data = $result->fetch_assoc();
        $available_qty = $data["available_qty"];
        
        Database::ud("UPDATE `book` SET `available_qty` = $available_qty - 1 WHERE `book_id` = '$book_id'");

        return true;
    }

    public static function getAllBorrowData()
    {
        $rs = Database::search("SELECT * FROM `borrow` INNER JOIN `book` ON `borrow`.`borrow_book_id` = `book`.`book_id` INNER JOIN `member` ON `borrow`.`borrow_member_id` = `member`.`member_id`");
        return $rs;
    }

    
    public static function searchBorrowBooks($memberid , $bookid) {
        $sql = "SELECT * FROM `borrow` INNER JOIN `book` ON `borrow`.`borrow_book_id` = `book`.`book_id` INNER JOIN `member` ON `borrow`.`borrow_member_id` = `member`.`member_id` WHERE 1";
        if (!empty($bookid)) {
            $sql .= " AND `book_id` LIKE '%$bookid%'";
        }
        if (!empty($memberid)) {
            $sql .= " AND `member_id` LIKE '%$memberid%'";
        }

        $rs = Database::search($sql);
        return $rs;
    }
}