<?php

require_once config::getdbPath();

class AuthModel
{
    private $db;

    public function __construct()
    {
        $this->db = new Database();
    }

    public static function validateLogin($memid, $password)
    {
        // SQL query to retrieve user details 
        $query = "SELECT * FROM `member`JOIN `member_login` ON `member`.`id` = `member_login`.`memberId` 
        WHERE `member_id` = '$memid' AND `password` = '$password' ;";
        $result = Database::search($query);

        // Check if a user record is found
        if ($result && $result->num_rows > 0) {
            // Fetch and return user details as an associative array
            $user = $result->fetch_assoc();
            return $user;
        } else {
            return false;
        }
    }

    public static function registerMember($nic,  $address, $phone, $email, $fname, $lname)
    {

        $id = Database::insert("INSERT INTO `member`(`nic`,`fname`,`lname`,`mobile`,`address`,`email`,`date_joined`,`status_id`) VALUES ('$nic','$fname','$lname','$phone','$address','$email',CURDATE(),'3')");
        return $id;
    }

    public static function validateEmail($email, $vcode)
    {
        $rs = Database::search("SELECT * FROM `member` WHERE `email` = '$email'");

        if ($rs->num_rows > 0) {
            $row = $rs->fetch_assoc();
            $id = $row["id"];
            Database::insert("UPDATE `member` SET `vcode` ='$vcode' WHERE `id`='$id'");
            return true;
        } else {
            return false;
        }
    }

    public static function changePasswordwithvcode($password, $vcode)
    {
        $vcode = trim($vcode);

        $rs = Database::search("SELECT * FROM `member` WHERE `vcode` = '$vcode'");

        if ($rs->num_rows > 0) {

            $row = $rs->fetch_assoc();
            $id = $row["id"];

            Database::ud("UPDATE `member_login` SET `password` ='$password' WHERE `memberId`='$id'");
            Database::ud("UPDATE `member` SET `vcode` = NULL WHERE `id`='$id'");

            return true;
        } else {
            return false;
        }
    }

    public static function changePasswordwithid($password, $id)
    {

        $rs = Database::search("SELECT * FROM `member` WHERE `id`='$id'");

        if ($rs->num_rows > 0) {

            $row = $rs->fetch_assoc();
            $id = $row["id"];

            Database::ud("UPDATE `member_login` SET `password` ='$password' WHERE `memberId`='$id'");
            return true;
        } else {
            return false;
        }
    }
}
