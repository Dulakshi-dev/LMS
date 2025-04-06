<?php

require_once config::getdbPath();

class ProfileModel
{
    public static function loadMemberDetails($id) {
        $rs = Database::search("SELECT `id`,`member_id`,`nic`,`fname`,`lname`,`address`,`mobile`,`email`,`profile_img` FROM `member` INNER JOIN `member_login` ON `member`.`id` = `member_login`.`memberId` WHERE `member_id` = '$id'");
        return $rs;
    }

    public static function updateMemberDetails($nic, $fname, $lname, $address, $mobile, $fileName)
    {
        Database::ud("UPDATE `member` SET `fname`='$fname',`lname`='$lname',`address`='$address', `mobile`='$mobile', `profile_img`='$fileName' WHERE `nic` = '$nic'");

        return true;
    }

    public static function updateMemberDetailsWithoutImage($nic, $fname, $lname, $address, $mobile)
    {
        Database::ud("UPDATE `member` SET `fname`='$fname',`lname`='$lname',`address`='$address', `mobile`='$mobile' WHERE `nic` = '$nic'");

        return true;
    }

    public static function getMemberCurrentProfileImage($nic)
    {
        $result = Database::search("SELECT `profile_img` FROM `member` WHERE `nic` = '$nic'");
        if ($row = $result->fetch_assoc()) {
            return $row['profile_img'];
        }
        return null;
    }

    public static function validateCurrentPassword($memid ,$password)
    {
        $result = Database::search("SELECT * from `member_login` WHERE `member_id`='$memid' AND `password` = '$password'");
        if ($result && $result->num_rows > 0) {
            return true;
        }else{
            return false;
        }
        
    }

    
    public static function resetPassword($memid ,$password)
    {
        Database::ud("UPDATE `member_login` SET `password` = '$password' WHERE `member_id`='$memid'");
            return true;
        
    }
}
