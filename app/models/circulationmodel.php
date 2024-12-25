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
        $rs = Database::search("SELECT * FROM `book_details` WHERE `book_id` = '$id'");
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
}