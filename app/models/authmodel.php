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
        $query = "SELECT * FROM `member`JOIN `member_login` ON `member`.`id` = `member_login`.`memberId` WHERE `member_id` = '$memid' AND `password` = '$password' ;";
        $result = Database::search($query);

        if ($result && $result->num_rows > 0) {
            $user = $result->fetch_assoc();
            return $user;
        }
        return false;
    }

    public static function registerMember($nic,  $address, $phone, $email, $fname, $lname)
    {

        $id = Database::insert("INSERT INTO `member`(`nic`,`fname`,`lname`,`mobile`,`address`,`email`,`status_id`) VALUES ('$nic','$fname','$lname','$phone','$address','$email','3')");
        // $memberID = self::generateMemberID();
        return $id;


    }

        

}
