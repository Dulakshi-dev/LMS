<?php
// UserModel.php
require_once __DIR__ . '../../../database/connection.php'; 

class MemberModel {
    public static function getAllMembers($page)
    {
        $rs = Database::search("SELECT `id`,`member_id`,`nic`,`fname`,`lname`,`address`,`mobile`,`email`,`member`.`status_id` FROM `member`JOIN `member_login` ON `member`.`id` = `member_login`.`memberId`");
        $num = $rs->num_rows;
        $resultsPerPage = 10;
        $pageResults = ($page - 1) * $resultsPerPage;

        $rs2 = Database::search("SELECT `id`,`member_id`,`nic`,`fname`,`lname`,`address`,`mobile`,`email`,`member`.`status_id` FROM `member`JOIN `member_login` ON `member`.`id` = `member_login`.`memberId` JOIN `status` ON `member`.`status_id`= `status`.`status_id` LIMIT $resultsPerPage OFFSET $pageResults");
        return [
            'total' => $num,
            'results' => $rs2
        ];
    }


    public static function searchMembers($memberId, $nic, $userName) {

        
        $sql = "SELECT `id`,`member_id`,`nic`,`fname`,`lname`,`address`,`mobile`,`email`,`member`.`status_id` FROM `member` JOIN `member_login` ON `member`.`id` = `member_login`.`memberId` WHERE 1";
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
        $rs = Database::search("SELECT `member_id`,`nic`,`fname`,`lname`,`address`,`mobile`,`email` FROM `member` INNER JOIN `member_login` ON `member`.`id` = `member_login`.`memberId` WHERE `member_id` = '$id'");
        return $rs;
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
    
    public static function loadMailDetails($member_id) {

        $id_result = Database::search("SELECT `id` FROM `member` INNER JOIN `member_login` ON `member`.`id` = `member_login`.`memberId` WHERE `member_id` = '$member_id'");

        if ($id_result->num_rows == 0) {
            return false;
        }

        $id_data = $id_result->fetch_assoc();
        $id = $id_data['id'];
        $rs = Database::search("SELECT `id`,`fname`,`lname`,`email` FROM `member` WHERE `id` = '$id'");
        return $rs;
    }
    
}
