<?php
require_once config::getdbPath();

class UserModel {
    public static function getAllUsers($page, $resultsPerPage)
    {
        $pageResults = ($page - 1) * $resultsPerPage;
        $totalUsers = self::getTotalUsers();

        $rs = Database::search("SELECT * FROM `user`
        JOIN `login` ON `user`.`id` = `login`.`userId` 
        JOIN `role` ON `user`.`role_id` = `role`.`role_id` 
        WHERE `status_id` = '1' 
        LIMIT $resultsPerPage OFFSET $pageResults");

        $users = [];

        while ($row = $rs->fetch_assoc()) {
            $users[] = $row;
        }
        return [
            'total' => $totalUsers,
            'results' => $users
        ];
    }

    private static function getTotalUsers()
    {
        $result = Database::search("SELECT COUNT(*) AS total FROM `user`JOIN `login` ON `user`.`id` = `login`.`userId` JOIN `role` ON `user`.`role_id` = `role`.`role_id` WHERE `status_id` = '1'");
        $row = $result->fetch_assoc();
        return $row['total'] ?? 0;
    }



    public static function searchUsers($memberId, $nic, $userName, $page, $resultsPerPage)
    {
        $pageResults = ($page - 1) * $resultsPerPage;
        $totalSearch = self::getTotalSearchResults($memberId, $nic, $userName);

        $sql = "SELECT * FROM `user` JOIN `login` ON `user`.`id` = `login`.`userId` WHERE `user`.`status_id`='1' AND 1";
        
        if (!empty($memberId)) {
            $sql .= " AND `user_id` LIKE '%$memberId%'";
        }
        if (!empty($nic)) {
            $sql .= " AND `nic` LIKE '%$nic%'";
        }
        if (!empty($userName)) {
            $sql .= " AND (`fname` LIKE '%$userName%' OR `lname` LIKE '%$userName%')";
        }
    
        $sql .= " LIMIT $resultsPerPage OFFSET $pageResults"; 
    
        $rs = Database::search($sql);

        $users = [];
        while ($row = $rs->fetch_assoc()) {
            $users[] = $row;
        }
        return ['results' => $users, 'total' => $totalSearch];
    }

    private static function getTotalSearchResults($memberId, $nic, $userName)
    {
        $countQuery = "SELECT COUNT(*) as total FROM `user` JOIN `login` ON `user`.`id` = `login`.`userId` WHERE `user`.`status_id`='1' AND 1";
        
        if (!empty($memberId)) {
            $countQuery .= " AND `user_id` LIKE '%$memberId%'";
        }
        if (!empty($nic)) {
            $countQuery .= " AND `nic` LIKE '%$nic%'";
        }
        if (!empty($userName)) {
            $countQuery .= " AND (`fname` LIKE '%$userName%' OR `lname` LIKE '%$userName%')";
        }
    
        $result = Database::search($countQuery);
        $row = $result->fetch_assoc();
        return $row['total'] ?? 0;
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

    
    public static function loadMailDetails($id) {
        $rs = Database::search("SELECT * FROM `user` INNER JOIN `login` ON `user`.`id` = `login`.`userId` WHERE `user_id` = '$id'");
        return $rs;
    }

    public static function deactivateUser($id) {
        $rs = Database::ud("UPDATE `user` SET `status_id`='2' WHERE `id`='$id'");
        return true;
    }

    
}
