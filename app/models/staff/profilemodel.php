<?php

require_once config::getdbPath();

class ProfileModel
{
    public static function updateUserDetails($nic, $fname, $lname, $address, $mobile, $fileName)
    {
        Database::ud("UPDATE `staff` SET `fname`='$fname',`lname`='$lname',`address`='$address', `mobile`='$mobile', `profile_img`='$fileName' WHERE `nic` = '$nic'");
        return true;
    }

    public static function updateUserDetailsWithoutImage($nic, $fname, $lname, $address, $mobile)
    {
        Database::ud("UPDATE `staff` SET `fname`='$fname',`lname`='$lname',`address`='$address', `mobile`='$mobile' WHERE `nic` = '$nic'");
        return true;
    }

    public static function getUserCurrentProfileImage($nic)
    {
        $result = Database::search("SELECT `profile_img` FROM `staff` WHERE `nic` = '$nic'");
        if ($row = $result->fetch_assoc()) {
            return $row['profile_img'];
        }
        return null;
    }

    public static function validateCurrentPassword($staffid ,$password)
    {
        $result = Database::search("SELECT * from `staff_login` WHERE `staff_id`='$staffid' AND `password` = '$password'");
        if ($result && $result->num_rows > 0) {
            return true;
        }else{
            return false;
        }
    }

    public static function resetPassword($staffid ,$password)
    {
        Database::ud("UPDATE `staff_login` SET `password` = '$password' WHERE `staff_id`='$staffid'");
        return true;
    }
}
