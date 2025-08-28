<?php

require_once config::getdbPath();

/**
 * AuthModel
 * Handles all authentication-related database operations for members.
 */

class AuthModel
{
    private $db;

    public function __construct()
    {
        $this->db = new Database();
    }

    public function validateLogin($memid, $password)
    {
        $query = "SELECT * FROM `member`JOIN `member_login` ON `member`.`id` = `member_login`.`memberId` WHERE `member_id` = ?";
        $params = [$memid];
        $types = "s";

        $result = Database::search($query, $params, $types);

        if ($result && $result->num_rows > 0) {
            $user = $result->fetch_assoc();
            if (password_verify($password, $user['password'])) {
                return $user;
            }
        }
        return false;
    }

        /**
     * getUserByRememberToken
     * Retrieves user by a "remember me" token.
     * @param string $token Hashed token
     * @return array|false User data if token matches, false otherwise
     */

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

        /**
     * storeRememberToken
     * Stores a hashed "remember me" token for a member.
     * @param string $memberid Member ID
     * @param string $hashedToken Hashed token
     */

    public function storeRememberToken($memberid, $hashedToken)
    {
        $query = "UPDATE `member_login` SET `remember_token` = ? WHERE `member_id` = ?";
        $params = [$hashedToken, $memberid];
        $types = "ss";

        Database::ud($query, $params, $types);
    }

    public function updateRememberToken($memberid, $hashedToken)
    {
        return $this->storeRememberToken($memberid, $hashedToken);
    }

    public function clearRememberToken($memberid)
    {
        $query = "UPDATE `member_login` SET `remember_token` = NULL WHERE `member_id` = ?";
        $params = [$memberid];
        $types = "s";
        Database::ud($query, $params, $types);
    }

    public static function registerMember($nic, $address, $phone, $email, $fname, $lname)
    {
        $query = "INSERT INTO `member` (`nic`,`fname`,`lname`,`mobile`,`address`,`email`,`date_joined`,`status_id`)
              VALUES (?, ?, ?, ?, ?, ?, CURDATE(), '3')";
        $params = [$nic, $fname, $lname, $phone, $address, $email];
        $types = "ssssss";

        $id = Database::insert($query, $params, $types);
        return $id;
    }

        /**
     * verifyEmail
     * Updates verification code for a member's email if exists.
     * @return bool True if successful, false if email not found
     */

    public static function verifyEmail($email, $vcode)
    {
        $query = "SELECT * FROM `member` WHERE `email` = ?";
        $params = [$email];
        $types = "s";
        $rs = Database::search($query, $params, $types);

        if ($rs->num_rows > 0) {
            $row = $rs->fetch_assoc();
            $id = $row["id"];

            $query = "UPDATE `member` SET `vcode` =? WHERE `id`=?";
            $params = [$vcode, $id];
            $types = "ss";
            Database::ud($query, $params, $types);

            return true;
        } else {
            return false;
        }
    }

    public static function validateEmail($email)
    {
        $query = "SELECT * FROM `member` WHERE `email` = ?";
        $params = [$email];
        $types = "s";
        $rs = Database::search($query, $params, $types);

        if ($rs->num_rows > 0) {
            return false;
        } else {
            return true;
        }
    }

    public static function validateNIC($nic)
    {
        $query = "SELECT * FROM `member` WHERE `nic` = ?";
        $params = [$nic];
        $types = "s";
        $rs = Database::search($query, $params, $types);

        if ($rs->num_rows > 0) {
            return false;
        } else {
            return true;
        }
    }

    /**
     * changePasswordwithvcode
     * Updates member password using a verification code.
     * @return bool True if successful, false otherwise
     */

    public static function changePasswordwithvcode($password, $vcode)
    {
        $vcode = trim($vcode);
        $hashedPassword = password_hash($password, PASSWORD_DEFAULT);

        $query = "SELECT * FROM `member` WHERE `vcode` = ?";
        $params = [$vcode];
        $types = "s";
        $rs = Database::search($query, $params, $types);

        if ($rs->num_rows > 0) {

            $row = $rs->fetch_assoc();
            $id = $row["id"];

            $query = "UPDATE `member_login` SET `password` =? WHERE `memberId`=?";
            $params = [$hashedPassword, $id];
            $types = "si";
            Database::ud($query, $params, $types);

            $query = "UPDATE `member` SET `vcode` = NULL WHERE `id`=?";
            $params = [$id];
            $types = "i";
            Database::ud($query, $params, $types);

            return true;
        } else {
            return false;
        }
    }
    
    /**
     * changePasswordwithid
     * Updates member password using their member ID.
     * @return bool True if successful, false otherwise
     */

    public static function changePasswordwithid($password, $id)
    {
        $query = "SELECT * FROM `member` WHERE `id`=?";
        $params = [$id];
        $types = "i";
        $rs = Database::search($query, $params, $types);

        $hashedPassword = password_hash($password, PASSWORD_DEFAULT);

        if ($rs->num_rows > 0) {
            $query = "UPDATE `member_login` SET `password` =? WHERE `memberId`=?";
            $params = [$hashedPassword, $id];
            $types = "si";
            Database::ud($query, $params, $types);

            return true;
        } else {
            return false;
        }
    }
}
