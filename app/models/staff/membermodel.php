<?php

require_once config::getdbPath();
require_once Config::getServicePath('emailService.php');
require_once Config::getMailPath("emailTemplate.php");

class MemberModel
{

    public static function getAllMembers($page, $resultsPerPage, $status = 'Active')
    {
        if ($status === 'Active') {
            $statusId = 1;
        } else {
            // For deactive status, check for both status_id 2 and 5
            $statusId = "2,5";
        }

        $pageResults = ($page - 1) * $resultsPerPage;
        $totalMembers = self::getTotalMembers($statusId);

        // Adjust query based on the status


        $query = "SELECT `id`,`member_id`,`nic`,`fname`,`lname`,`address`,`mobile`,`email`,`status_id` 
                                FROM `member`
                                JOIN `member_login` ON `member`.`id` = `member_login`.`memberId` 
                                WHERE FIND_IN_SET(`status_id`, ?) 
                                LIMIT ? OFFSET ?";
        $params = [$statusId, $resultsPerPage, $pageResults];
        $types = "sii";
        $rs = Database::search($query, $params, $types);

        $members = [];

        while ($row = $rs->fetch_assoc()) {
            $members[] = $row;
        }

        return [
            'total' => $totalMembers,
            'results' => $members
        ];
    }

    private static function getTotalMembers($statusId)
    {
        $query = "SELECT COUNT(*) AS total 
                                    FROM `member`JOIN `member_login` ON `member`.`id` = `member_login`.`memberId` WHERE FIND_IN_SET(`status_id`, ?)";
        $params = [$statusId];
        $types = "s";
        $result = Database::search($query, $params, $types);

        $row = $result->fetch_assoc();
        return $row['total'] ?? 0;
    }

    public static function searchMembers($memberId, $nic, $userName, $status = 'Active', $page, $resultsPerPage)
    {
        $statusId = ($status === 'Active') ? 1 : 2;

        $pageResults = ($page - 1) * $resultsPerPage;
        $totalSearch = self::getTotalSearchResults($memberId, $nic, $userName, $statusId);

        $sql = "SELECT `id`, `member_id`, `nic`, `fname`, `lname`, `address`, `mobile`, `email`, `member`.`status_id`
            FROM `member`
            JOIN `member_login` ON `member`.`id` = `member_login`.`memberId`
            WHERE `status_id` = ?";

        $params = [$statusId];
        $types = "i";

        if (!empty($memberId)) {
            $sql .= " AND `member_id` LIKE ?";
            $params[] = "%$memberId%";
            $types .= "s";
        }
        if (!empty($nic)) {
            $sql .= " AND `nic` LIKE ?";
            $params[] = "%$nic%";
            $types .= "s";
        }
        if (!empty($userName)) {
            $sql .= " AND (`fname` LIKE ? OR `lname` LIKE ?)";
            $params[] = "%$userName%";
            $params[] = "%$userName%";
            $types .= "ss";
        }

        $sql .= " LIMIT ? OFFSET ?";
        $params[] = $resultsPerPage;
        $params[] = $pageResults;
        $types .= "ii";

        $rs = Database::search($sql, $params, $types);

        $users = [];
        while ($row = $rs->fetch_assoc()) {
            $users[] = $row;
        }

        return ['results' => $users, 'total' => $totalSearch];
    }


    private static function getTotalSearchResults($memberId, $nic, $userName, $statusId)
    {
        $countQuery = "SELECT COUNT(*) as total 
                   FROM `member` 
                   JOIN `member_login` ON `member`.`id` = `member_login`.`memberId`
                   WHERE `status_id` = ?";

        $params = [$statusId];
        $types = "i";

        if (!empty($memberId)) {
            $countQuery .= " AND `member_id` LIKE ?";
            $params[] = "%$memberId%";
            $types .= "s";
        }
        if (!empty($nic)) {
            $countQuery .= " AND `nic` LIKE ?";
            $params[] = "%$nic%";
            $types .= "s";
        }
        if (!empty($userName)) {
            $countQuery .= " AND (`fname` LIKE ? OR `lname` LIKE ?)";
            $params[] = "%$userName%";
            $params[] = "%$userName%";
            $types .= "ss";
        }

        $result = Database::search($countQuery, $params, $types);
        $row = $result->fetch_assoc();
        return $row['total'] ?? 0;
    }


    public static function getAllMemberRequests($page, $resultsPerPage, $status = 'Pending')
    {
        $statusId = ($status === 'Pending') ? 3 : 4;

        $pageResults = ($page - 1) * $resultsPerPage;
        $totalrequests = self::getTotalRequests($statusId);

        $query = "SELECT `id`,`nic`,`fname`,`lname`,`address`,`mobile`,`email`,`member`.`status_id` FROM `member` WHERE `status_id`=? LIMIT ? OFFSET ?";
        $params = [$statusId, $resultsPerPage, $pageResults];
        $types = "iii";
        $rs = Database::search($query, $params, $types);


        $requests = [];

        while ($row = $rs->fetch_assoc()) {
            $requests[] = $row;
        }
        return [
            'total' => $totalrequests,
            'results' => $requests
        ];
    }

    private static function getTotalRequests($statusId)
    {

        $query = "SELECT COUNT(*) AS total FROM `member` WHERE `status_id`='$statusId';";
        $params = [$statusId];
        $types = "i";
        $result = Database::search($query, $params, $types);

        $row = $result->fetch_assoc();
        return $row['total'] ?? 0;
    }

    public static function searchMemberRequests($nic, $userName, $status = 'Pending', $page, $resultsPerPage)
    {
        $statusId = ($status === 'Pending') ? 3 : 4;

        $pageResults = ($page - 1) * $resultsPerPage;
        $totalSearch = self::getTotalSearchMemberRequest($nic, $userName, $statusId);

        $sql = "SELECT `id`,`nic`,`fname`,`lname`,`address`,`mobile`,`email`,`member`.`status_id` 
            FROM `member` 
            WHERE `status_id` = ?";

        $params = [$statusId];
        $types = "i";

        if (!empty($nic)) {
            $sql .= " AND `nic` LIKE ?";
            $params[] = "%$nic%";
            $types .= "s";
        }
        if (!empty($userName)) {
            $sql .= " AND (`fname` LIKE ? OR `lname` LIKE ?)";
            $params[] = "%$userName%";
            $params[] = "%$userName%";
            $types .= "ss";
        }

        $sql .= " LIMIT ? OFFSET ?";
        $params[] = $resultsPerPage;
        $params[] = $pageResults;
        $types .= "ii";

        $rs = Database::search($sql, $params, $types);

        $users = [];
        while ($row = $rs->fetch_assoc()) {
            $users[] = $row;
        }

        return ['results' => $users, 'total' => $totalSearch];
    }


    private static function getTotalSearchMemberRequest($nic, $userName, $statusId)
    {
        $countQuery = "SELECT COUNT(*) AS total FROM `member` WHERE `status_id` = ?";
        $params = [$statusId];
        $types = "i";

        if (!empty($nic)) {
            $countQuery .= " AND `nic` LIKE ?";
            $params[] = "%$nic%";
            $types .= "s";
        }

        if (!empty($userName)) {
            $countQuery .= " AND (`fname` LIKE ? OR `lname` LIKE ?)";
            $params[] = "%$userName%";
            $params[] = "%$userName%";
            $types .= "ss";
        }

        $result = Database::search($countQuery, $params, $types);
        $row = $result->fetch_assoc();

        return $row['total'] ?? 0;
    }

    public static function loadMemberDetails($id)
    {

        $query = "SELECT `member_id`,`nic`,`fname`,`lname`,`address`,`mobile`,`email` FROM `member` 
        INNER JOIN `member_login` ON `member`.`id` = `member_login`.`memberId` WHERE `member_id` = ?";
        $params = [$id];
        $types = "s";
        $rs = Database::search($query, $params, $types);



        return $rs;
    }

    public static function approveMembership($id, $memberID, $password)
    {
        $query = "UPDATE `member` SET `status_id`='1' WHERE `id`=?";
        $params = [$id];
        $types = "i";
        Database::ud($query, $params, $types);
        $hashedPassword = password_hash($password, PASSWORD_DEFAULT);

        $query = "INSERT INTO `member_login`(`member_id`,`password`,`memberId`) VALUES(?,?,?);";
        $params = [$memberID, $hashedPassword, $id];
        $types = "ssi";
        Database::insert($query, $params, $types);
        return true;
    }

    // public static function sendPasswordResetMail($id, $memberID, $password, $vcode = null)
    // {
    //     $rs = Database::search("SELECT * FROM `member` WHERE `id` = '$id'");
    //     $row = $rs->fetch_assoc();

    //     require_once Config::getServicePath('emailService.php');

    //     $name = $row["fname"] . " " . $row["lname"];
    //     $email = $row["email"];
    //     $subject = 'Library Membership Approved – Your Login Details';

    //     $resetLink = '';
    //     if (!empty($vcode)) {
    //         $resetLink = '<div style="margin-bottom: 10px;">
    //             <a href="http://localhost/LMS/public/member/index.php?action=showresetpw&vcode=' . $vcode . '">Click here to reset your password</a>
    //         </div>';
    //     } else {
    //         $resetLink = '<div style="margin-bottom: 10px;">
    //             <a href="http://localhost/LMS/public/member/index.php?action=showresetpw&id=' . $id . '">Click here to reset your password</a>
    //         </div>';
    //     }


    //     $body = '
    //         <h4 style="text-align: center;">Welcome to SHELFLOOM!</h4> 
    //         <p>Dear ' . $name . ',</p>
    //         <div style="max-width: 600px; margin: 0 auto; padding: 20px; text-align: left;">
    //             <p>You can now log in to the library management system using the credentials provided below:</p>
    //             <h2>Your Member ID is ' . $memberID . '</h2>
    //             <h2>Your temporary password is ' . $password . '</h2>
    //             <p>Please use the above credentials to log in to your account or if you want to change the password before login to the system below is the password reset link.</p><br>
    //             ' . $resetLink . '
    //             <p>If you have any questions or issues, please reach out to us.</p>
    //             <p>Call:[tel_num]</p>
    //             <div style="margin-top: 20px;">
    //                 <p>Best regards,</p>
    //                 <p>Shelf Loom Team</p>
    //             </div>
    //         </div>';

    //     $emailService = new EmailService();
    //     $emailSent = $emailService->sendEmail($email, $subject, $body);

    //     return $emailSent;
    // }

    public static function sendMemberMail($name, $email, $subject, $msg)
    {
        // Use the EmailTemplate to get the standardized email body
        $emailTemplate = new EmailTemplate();
        $body = $emailTemplate->getEmailBody($name, $msg);

        $emailService = new EmailService();
        $emailSent = $emailService->sendEmail($email, $subject, $body);
        return $emailSent;
    }


    public static function generateMemberID()
    {
        $query = "SELECT member_id FROM `member_login` ORDER BY member_login_id DESC LIMIT 1";
        $result = Database::search($query);



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

    public static function generatePassword($length = 12)
    {
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

    public static function UpdateMemberDetails($member_id, $fname, $lname, $email, $phone, $address, $nic)
    {

        $query = "UPDATE `member` INNER JOIN `member_login` ON `member`.`id` = `member_login`.`memberId` SET 
                `fname` = ?, 
                `lname` = ?, 
                `mobile` = ?,  
                `address` = ?, 
                `nic` = ?,
                `email` = ?
                WHERE `member_id` = ?";
        $params = [$fname, $lname, $phone, $address, $nic, $email, $member_id];
        $types = "sssssss";
        Database::ud($query, $params, $types);



        return true;
    }


    public static function loadMailDetails($member_id)
    {

        $query = "SELECT `id` FROM `member` INNER JOIN `member_login` ON `member`.`id` = `member_login`.`memberId` WHERE `member_id` = ?";
        $params = [$member_id];
        $types = "s";
        $id_result = Database::search($query, $params, $types);

        if ($id_result->num_rows == 0) {
            return false;
        }

        $id_data = $id_result->fetch_assoc();
        $id = $id_data['id'];

        $query = "SELECT `id`,`fname`,`lname`,`email` FROM `member` WHERE `id` = ?";
        $params = [$id];
        $types = "i";
        $rs = Database::search($query, $params, $types);

        return $rs;
    }

    public static function deactivateMember($id)
    {
        $query = "UPDATE `member` SET `status_id`='2' WHERE `id`=?";
        $params = [$id];
        $types = "i";
        Database::ud($query, $params, $types);

        return true;
    }

    public static function rejectMember($id)
    {
        $query = "UPDATE `member` SET `status_id`='4' WHERE `id`=?";
        $params = [$id];
        $types = "i";
        Database::ud($query, $params, $types);

        return true;
    }

    public static function activateMember($id)
    {
        $query = "UPDATE `member` SET `status_id`='1' WHERE `id`=?";
        $params = [$id];
        $types = "i";
        Database::ud($query, $params, $types);

        return true;
    }

    public static function activateRequest($id)
    {
        $query = "UPDATE `member` SET `status_id`='3' WHERE `id`=?";
        $params = [$id];
        $types = "i";
        Database::ud($query, $params, $types);

        return true;
    }

    public static function updateMembershipStatus($member_id)
    {
        $query = "SELECT `id` FROM `member` INNER JOIN `member_login` ON `member`.`id`=`member_login`.`memberId` WHERE `member_id` = ?";
        $params = [$member_id];
        $types = "s";
        $result = Database::search($query, $params, $types);

        $row = $result->fetch_assoc();
        $id = $row['id'];

        $query = "UPDATE `member` SET `status_id`='1' WHERE `id`=?";
        $params = [$id];
        $types = "i";
        Database::ud($query, $params, $types);

        return true;
    }
}
