<?php

require_once config::getdbPath();

class ProfileModel
{
    public static function updateUserDetails($nic, $fname, $lname, $address, $mobile, $fileName)
    {
        $query = "UPDATE `staff` SET `fname`=?, `lname`=?, `address`=?, `mobile`=?, `profile_img`=? WHERE `nic`=?";
        $params = [$fname, $lname, $address, $mobile, $fileName, $nic];
        $types = "ssssss";

        Database::ud($query, $params, $types);
        return true;
    }

    public static function updateUserDetailsWithoutImage($nic, $fname, $lname, $address, $mobile)
    {
        $query = "UPDATE `staff` SET `fname`=?, `lname`=?, `address`=?, `mobile`=? WHERE `nic`=?";
        $params = [$fname, $lname, $address, $mobile, $nic];
        $types = "sssss";

        Database::ud($query, $params, $types);
        return true;
    }

    public static function getUserCurrentProfileImage($nic)
    {
        $query = "SELECT `profile_img` FROM `staff` WHERE `nic`=?";
        $params = [$nic];
        $types = "s";

        $result = Database::search($query, $params, $types);
        if ($row = $result->fetch_assoc()) {
            return $row['profile_img'];
        }
        return null;
    }

    public static function validateCurrentPassword($staffid, $password)
    {
        $query = "SELECT `password` FROM `staff_login` WHERE `staff_id`=?";
        $params = [$staffid];
        $types = "s";

        $result = Database::search($query, $params, $types);

        if ($result && $result->num_rows > 0) {
            $user = $result->fetch_assoc();
            return password_verify($password, $user['password']);
        }
        return false;
    }

    public static function resetPassword($staffid, $password)
    {
        $hashedPassword = password_hash($password, PASSWORD_DEFAULT);

        $query = "UPDATE `staff_login` SET `password`=? WHERE `staff_id`=?";
        $params = [$hashedPassword, $staffid];
        $types = "ss";

        Database::ud($query, $params, $types);
        return true;
    }
}
