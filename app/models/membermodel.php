<?php
// UserModel.php
require_once __DIR__ . '../../../database/connection.php'; 

class MemberModel {
    public static function getAllMembers($page)
    {
        $rs = Database::search("SELECT * FROM `member`JOIN `member_login` ON `member`.`id` = `member_login`.`memberId`");
        $num = $rs->num_rows;
        $resultsPerPage = 1;
        $pageResults = ($page - 1) * $resultsPerPage;

        $rs2 = Database::search("SELECT * FROM `member`JOIN `member_login` ON `member`.`id` = `member_login`.`memberId` JOIN `status` ON `member`.`status_status_id`= `status`.`status_id` LIMIT $resultsPerPage OFFSET $pageResults");
        return [
            'total' => $num,
            'results' => $rs2
        ];
    }

    

    public static function searchMembers($memberId, $nic, $userName) {
        $sql = "SELECT * FROM `member` JOIN `member_login` ON `member`.`id` = `member_login`.`memberId` WHERE 1";
        if (!empty($memberId)) {
            $sql .= " AND `member_id` LIKE '%$memberId%'";
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

    public static function loadMemberDetails($id) {
        $rs = Database::search("SELECT * FROM `member` INNER JOIN `member_login` ON `member`.`id` = `member_login`.`memberId` WHERE `member_id` = '$id'");
        return $rs;
    }

    public static function getMemberbyID($member_id) {
        $rs = Database::search("SELECT * FROM `member` JOIN `member_login` ON `member`.`id` = `member_login`.`memberId` WHERE `member_id` = '$member_id'");
        return $rs->fetch_assoc();
    }

    
    public static function UpdateMemberDetails($member_id, $fname, $lname, $email, $phone, $address, $nic) {
      
            Database::ud("UPDATE `member` INNER JOIN `member_login` ON `member`.`id` = `member_login`.`memberId` SET 
                `fname` = '$fname', 
                `lname` = '$lname', 
                `mobile` = '$phone',  
                `address` = '$address', 
                `nic` = '$nic',
                `email` = '$email'
                WHERE `member_id` = '$member_id'");
                return true;
       
    }
    

    public static function toggleMemberStatus($id) {
       
        Database::ud("UPDATE `member` SET `status_id` = 3 - `status_id` WHERE `id` = '$id'");
        return true;
    }
    
    public static function loadMailDetails($id) {
        $rs = Database::search("SELECT * FROM `member` INNER JOIN `member_login` ON `member`.`id` = `member_login`.`memberId` WHERE `member_id` = '$id'");
        return $rs;
    }
    
}
