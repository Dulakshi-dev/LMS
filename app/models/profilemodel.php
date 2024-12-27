<?php

require_once __DIR__ . '../../../database/connection.php';

class ProfileModel
{
    public static function updateUserDetails($nic, $fname, $lname, $address, $mobile, $fileName)
    {
        Database::ud("UPDATE `user` SET `fname`='$fname',`lname`='$lname',`address`='$address', `mobile`='$mobile', `profile_img`='$fileName' WHERE `nic` = '$nic'");

        return true;
    }

    public static function updateUserDetailsWithoutImage($nic, $fname, $lname, $address, $mobile)
    {
        Database::ud("UPDATE `user` SET `fname`='$fname',`lname`='$lname',`address`='$address', `mobile`='$mobile' WHERE `nic` = '$nic'");

        return true;
    }

    public static function getUserCurrentProfileImage($nic)
    {
        $result = Database::search("SELECT `profile_img` FROM `user` WHERE `nic` = '$nic'");
        if ($row = $result->fetch_assoc()) {
            return $row['profile_img'];
        }
        return null;
    }
}
