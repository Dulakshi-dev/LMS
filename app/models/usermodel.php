<?php
// UserModel.php
require_once __DIR__ . '../../../database/connection.php'; 

class UserModel {
    public static function getAllUsers() {
        $rs = Database::search("SELECT * FROM `user_details`");
        return $rs;
    }

    public static function searchUsers($memberId, $nic, $userName) {
        $sql = "SELECT * FROM `user_details` WHERE 1";
        if (!empty($memberId)) {
            $sql .= " AND `user_id` LIKE '%$memberId%'";
        }
        if (!empty($nic)) {
            $sql .= " AND `nic` LIKE '%$nic%'";
        }
        if (!empty($userName)) {
            $sql .= " AND (`fname` LIKE '%$userName%' OR `lname` LIKE '%$userName%')";
        }
        $rs = Database::search($sql);
        return $rs;
    }

    public static function loadUserDetails($id) {
        $rs = Database::search("SELECT * FROM `user` INNER JOIN `login` ON `user`.`id` = `login`.`userId` WHERE `user_id` = '$id'");
        return $rs;
    }

    public static function getUserbyID($user_id) {
        $rs = Database::search("SELECT * FROM `user_details` WHERE `user_id` = '$user_id'");
        return $rs->fetch_assoc();
    }

    
    public static function UpdateUserDetails($user_id, $fname, $lname, $email, $phone, $address, $nic) {
      
            Database::ud("UPDATE `user` SET 
                `fname` = '$fname', 
                `lname` = '$lname', 
                `mobile` = '$phone',  
                `address` = '$address', 
                `nic` = '$nic' 
                WHERE `email` = '$email'");
                return true;
       
    }
    

    public static function toggleUserStatus($id) {
       
        Database::ud("UPDATE `user` SET `status_id` = 3 - `status_id` WHERE `id` = '$id'");
        return true;
    }
    
    public static function loadMailDetails($id) {
        $rs = Database::search("SELECT * FROM `user` INNER JOIN `login` ON `user`.`id` = `login`.`userId` WHERE `user_id` = '$id'");
        return $rs;
    }
    
}
