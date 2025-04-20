<?php

require_once config::getdbPath();

class AuthModel
{
    private $db;

    public function __construct()
    {
        $this->db = new Database();
    }

    public function validateLogin($memid, $password)
    {
        $query = "SELECT * FROM `member`JOIN `member_login` ON `member`.`id` = `member_login`.`memberId` 
        WHERE `member_id` = '$memid'";
        $result = Database::search($query);


        if ($result && $result->num_rows > 0) {
            $user = $result->fetch_assoc();
            if (password_verify($password, $user['password'])) {
                return $user;
            }
        }
        return false;
    }

    public function getUserByRememberToken($token)
    {
        $query = "SELECT * FROM `member`JOIN `member_login` ON `member`.`id` = `member_login`.`memberId`
                  WHERE `remember_token` IS NOT NULL";

        $result = Database::search($query);

        if ($result && $result->num_rows > 0) {
            while ($user = $result->fetch_assoc()) {
                if (password_verify($token, $user['remember_token'])) {
                    return $user;
                }
            }
        }
        return false;
    }

    public function storeRememberToken($memberid, $hashedToken)
    {
        Database::ud("UPDATE `member_login` SET `remember_token` = '$hashedToken' WHERE `member_id` = '$memberid'");
    }

    public function updateRememberToken($memberid, $hashedToken)
    {
        return $this->storeRememberToken($memberid, $hashedToken);
    }

    public function clearRememberToken($memberid)
    {
        Database::ud("UPDATE `member_login` SET `remember_token` = NULL WHERE `member_id` = '$memberid'");
    }


    public static function registerMember($nic,  $address, $phone, $email, $fname, $lname)
    {
        $id = Database::insert("INSERT INTO `member`(`nic`,`fname`,`lname`,`mobile`,`address`,`email`,`date_joined`,`status_id`) VALUES ('$nic','$fname','$lname','$phone','$address','$email',CURDATE(),'3')");
        return $id;
    }

    public static function verifyEmail($email, $vcode)
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

    public static function validateEmail($email)
    {
        $rs = Database::search("SELECT * FROM `member` WHERE `email` = '$email'");

        if ($rs->num_rows > 0) {
            return false;
        } else {
            return true;
        }
    }

    public static function validateNIC($nic)
    {
        $rs = Database::search("SELECT * FROM `member` WHERE `nic` = '$nic'");

        if ($rs->num_rows > 0) {
            return false;
        } else {
            return true;
        }
    }


    public static function changePasswordwithvcode($password, $vcode)
    {
        $vcode = trim($vcode);
        $hashedPassword = password_hash($password, PASSWORD_DEFAULT);

        $rs = Database::search("SELECT * FROM `member` WHERE `vcode` = '$vcode'");

        if ($rs->num_rows > 0) {

            $row = $rs->fetch_assoc();
            $id = $row["id"];

            Database::ud("UPDATE `member_login` SET `password` ='$hashedPassword' WHERE `memberId`='$id'");
            Database::ud("UPDATE `member` SET `vcode` = NULL WHERE `id`='$id'");

            return true;
        } else {
            return false;
        }
    }

    public static function changePasswordwithid($password, $id)
    {

        $rs = Database::search("SELECT * FROM `member` WHERE `id`='$id'");
        $hashedPassword = password_hash($password, PASSWORD_DEFAULT);

        if ($rs->num_rows > 0) {

            $row = $rs->fetch_assoc();
            $id = $row["id"];

            Database::ud("UPDATE `member_login` SET `password` ='$hashedPassword' WHERE `memberId`='$id'");
            return true;
        } else {
            return false;
        }
    }
}
