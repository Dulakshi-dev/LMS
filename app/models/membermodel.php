<?php
// UserModel.php
require_once __DIR__ . '../../../database/connection.php'; 

class MemberModel {
    public static function getAllMembers($page)
    {
        $rs = Database::search("SELECT `id`,`member_id`,`nic`,`fname`,`lname`,`address`,`mobile`,`email`,`member`.`status_id` FROM `member`JOIN `member_login` ON `member`.`id` = `member_login`.`memberId` WHERE `status_id`='1'");
        $num = $rs->num_rows;
        $resultsPerPage = 10;
        $pageResults = ($page - 1) * $resultsPerPage;

        $rs2 = Database::search("SELECT `id`,`member_id`,`nic`,`fname`,`lname`,`address`,`mobile`,`email`,`member`.`status_id` FROM `member`JOIN `member_login` ON `member`.`id` = `member_login`.`memberId` WHERE `status_id`='1' LIMIT $resultsPerPage OFFSET $pageResults");
        return [
            'total' => $num,
            'results' => $rs2
        ];
    }

    public static function getPendingMembers($page)
    {
        $rs = Database::search("SELECT `id`,`nic`,`fname`,`lname`,`address`,`mobile`,`email`,`member`.`status_id` FROM `member` WHERE `status_id`='3'");
        $num = $rs->num_rows;
        $resultsPerPage = 10;
        $pageResults = ($page - 1) * $resultsPerPage;

        $rs2 = Database::search("SELECT `id`,`nic`,`fname`,`lname`,`address`,`mobile`,`email`,`member`.`status_id` FROM `member` WHERE `status_id`='3' LIMIT $resultsPerPage OFFSET $pageResults");
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

    public static function approveMembership($id) {
        Database::ud("UPDATE `member` SET `status_id`='1' WHERE `id`='$id'");
        $memberID = self::generateMemberID();
        $password = self::generatePassword();

        self::sendMail($id, $memberID ,$password);
        Database::insert("INSERT INTO `member_login`(`member_id`,`password`,`memberId`) VALUES('$memberID','$password','$id');");

        return true;
    }

    public static function sendMail($id, $memberID, $password, $vcode = null)
    {
        $rs = Database::search("SELECT * FROM `member` WHERE `id` = '$id'");
        $row = $rs->fetch_assoc();
    
        require_once Config::getServicePath('emailService.php');
    
        $name = $row["fname"] . " " . $row["lname"];
        $email = $row["email"];
        $subject = 'Member ID';
    
        $resetLink = '';
        if (!empty($vcode)) {
            $resetLink = '<div style="margin-bottom: 10px;">
                <a href="http://localhost/LMS/public/member/index.php?action=showresetpw&vcode=' . $vcode . '">Click here to reset your password</a>
            </div>';
        }else{
            $resetLink = '<div style="margin-bottom: 10px;">
                <a href="http://localhost/LMS/public/member/index.php?action=showresetpw&id='.$id.'">Click here to reset your password</a>
            </div>';
        }
    
        $body = '<h1 style="padding-top: 30px;">Shelf Loom</h1>
            <p style="font-size: 30px; color: black; font-weight: bold; text-align: center;">Welcome!</p> 
            <p>Dear ' . $name . ',</p>
            <div style="max-width: 600px; margin: 0 auto; padding: 20px; text-align: left;">
                <p>We are pleased to connect with you! Here’s some important information:</p>
                <h2>Your Member ID is ' . $memberID . '</h2>
                <h2>Your temporary password is ' . $password . '</h2>
                ' . $resetLink . '
                <p>If you have any questions or issues, please reach out to us.</p>
                <p>Call:[tel_num]</p>
                <div style="margin-top: 20px;">
                    <p>Best regards,</p>
                    <p>Shelf Loom Team</p>
                </div>
            </div>';
    
        $emailService = new EmailService();
        $emailSent = $emailService->sendEmail($email, $subject, $body);
    
        return $emailSent;
    }
    

    public static function generateMemberID()
    {
        // Query to get the latest member_id
        $result = Database::search("SELECT member_id FROM `member_login` ORDER BY member_login_id DESC LIMIT 1");

        if ($result->num_rows > 0) {
            $row = $result->fetch_assoc();
            $lastMemberID = $row['member_id'];

            $number = (int)substr($lastMemberID, 2);
            $newNumber = $number + 1;

            $newMemberID = "M-" . str_pad($newNumber, 6, "0", STR_PAD_LEFT);
        } else {
            $newMemberID = "M-000001";
        }
        return $newMemberID;
    }

    public static function generatePassword($length = 12) {
        $uppercase = 'ABCDEFGHIJKLMNOPQRSTUVWXYZ';
        $lowercase = 'abcdefghijklmnopqrstuvwxyz';
        $numbers = '0123456789';
        $specialChars = '!@#$%^&*()-_=+[]{}|;:,.<>?';
    
        $allCharacters = $uppercase . $lowercase . $numbers . $specialChars;
    
        // Ensure the password contains at least one character from each category
        $password = $uppercase[rand(0, strlen($uppercase) - 1)] .
                    $lowercase[rand(0, strlen($lowercase) - 1)] .
                    $numbers[rand(0, strlen($numbers) - 1)] .
                    $specialChars[rand(0, strlen($specialChars) - 1)];
    
        // Generate the remaining random characters
        for ($i = 4; $i < $length; $i++) {
            $password .= $allCharacters[rand(0, strlen($allCharacters) - 1)];
        }
    
        // Shuffle the password to make it more random
        return str_shuffle($password);
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
