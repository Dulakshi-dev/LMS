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
        $query = "SELECT * FROM `member`
JOIN `member_login` ON `member`.`id` = `member_login`.`memberId` WHERE `member_id` = '$memid' AND `password` = '$password' ;";
        $result = Database::search($query);

        if ($result && $result->num_rows > 0) {
            $user = $result->fetch_assoc();
            return $user;
        }
        return false;
    }
}