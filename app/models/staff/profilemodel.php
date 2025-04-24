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
        $result = Database::search("SELECT * from `staff_login` WHERE `staff_id`='$staffid'");

        if ($result && $result->num_rows > 0) {
            $user = $result->fetch_assoc();

            if (password_verify($password, $user['password'])) {
                return true;
            }
        }
        return false;
    }

    public static function resetPassword($staffid ,$password)
    {
        $hashedPassword = password_hash($password, PASSWORD_DEFAULT);

        Database::ud("UPDATE `staff_login` SET `password` = '$hashedPassword' WHERE `staff_id`='$staffid'");
        return true;
    }
}
