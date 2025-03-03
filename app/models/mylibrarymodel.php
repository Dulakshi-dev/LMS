<?php

require_once __DIR__ . '../../../database/connection.php';

class MyLibraryModel
{
    public static function saveBook($book_id, $member_id)
    {
        $rs = Database::search("SELECT * FROM `member_saved_books` WHERE `saved_member_id` ='$member_id' AND `saved_book_id` = '$book_id'");

        if ($rs->num_rows > 0) {
            return false;
        }
        Database::insert("INSERT INTO `member_saved_books` (`saved_member_id`,`saved_book_id`) VALUES ('$member_id', '$book_id')");

        return true;
    }

    public static function getSavedBooks($member_id)
    {

        $rs = Database::search("SELECT * FROM `member_saved_books` INNER JOIN `book` ON `member_saved_books`.`saved_book_id` = `book`.`book_id` INNER JOIN `member_login` ON `member_login`.`member_id` = `member_saved_books`.`saved_member_id` WHERE `saved_member_id` = '$member_id'");
        $num = $rs->num_rows;
        return [
            'total' => $num,
            'results' => $rs
        ];
    }

    public static function unSaveBook($book_id, $member_id)
    {

        Database::ud("DELETE FROM `member_saved_books` WHERE `saved_member_id` = '$member_id' AND `saved_book_id` = '$book_id'");
        return true;
    }
}
