<?php
// UserModel.php
require_once __DIR__ . '../../../database/connection.php'; 

class UserModel {
    public static function getAllUsers($page)
    {
        $rs = Database::search("SELECT * FROM `user`JOIN `login` ON `user`.`id` = `login`.`userId` JOIN `role` ON `user`.`role_id` = `role`.`role_id`");
        $num = $rs->num_rows;
        $resultsPerPage = 1;
        $pageResults = ($page - 1) * $resultsPerPage;

        $rs2 = Database::search("SELECT * FROM `user`JOIN `login` ON `user`.`id` = `login`.`userId` JOIN `role` ON `user`.`role_id` = `role`.`role_id` LIMIT $resultsPerPage OFFSET $pageResults");
        return [
            'total' => $num,
            'results' => $rs2
        ];
    }

    

    public static function searchUsers($memberId, $nic, $userName) {
        $sql = "SELECT * FROM `user` JOIN `login` ON `user`.`id` = `login`.`userId` WHERE 1";
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
        $rs = Database::search("SELECT * FROM `user` JOIN `login` ON `user`.`id` = `login`.`userId` WHERE `user_id` = '$user_id'");
        return $rs->fetch_assoc();
    }

    
    public static function UpdateUserDetails($user_id, $fname, $lname, $email, $phone, $address, $nic) {
      
            Database::ud("UPDATE `user` INNER JOIN `login` ON `user`.`id` = `login`.`userId` SET 
                `fname` = '$fname', 
                `lname` = '$lname', 
                `mobile` = '$phone',  
                `address` = '$address', 
                `nic` = '$nic',
                `email` = '$email' 
                WHERE `user_id` = '$user_id'");
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
