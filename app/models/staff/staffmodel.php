<?php
require_once config::getdbPath();

class StaffModel
{
    public static function getAllStaff($page, $resultsPerPage, $status = 'Active')
    {
        // Determine the status_id based on the user-specified status
        $statusId = ($status === 'Active') ? 1 : 2;  // Assuming '1' is active, '2' is deactivated

        $pageResults = ($page - 1) * $resultsPerPage;
        $totalUsers = self::getTotalStaff($statusId);

        $rs = Database::search("SELECT * FROM `staff`
            JOIN `staff_login` ON `staff`.`id` = `staff_login`.`staffId`
            JOIN `role` ON `staff`.`role_id` = `role`.`role_id`
            WHERE `status_id` = '$statusId'
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

    private static function getTotalStaff($statusId)
    {

        // Get the total number of users based on the selected status
        $result = Database::search("SELECT COUNT(*) AS total FROM `staff`
            JOIN `staff_login` ON `staff`.`id` = `staff_login`.`staffId`
            JOIN `role` ON `staff`.`role_id` = `role`.`role_id`
            WHERE `status_id` = '$statusId'");

        $row = $result->fetch_assoc();
        return $row['total'] ?? 0;
    }

    public static function searchStaff($memberId, $nic, $userName, $status = 'Active', $page, $resultsPerPage)
    {
        $statusId = ($status === 'Active') ? 1 : 2;
        $pageResults = ($page - 1) * $resultsPerPage;
        $totalSearch = self::getTotalSearchResults($memberId, $nic, $userName, $statusId);

        $sql = "SELECT * FROM `staff` JOIN `staff_login` ON `staff`.`id` = `staff_login`.`staffId` WHERE `staff`.`status_id`='$statusId' AND 1";

        if (!empty($memberId)) {
            $sql .= " AND `staff_id` LIKE '%$memberId%'";
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

    private static function getTotalSearchResults($memberId, $nic, $userName, $statusId)
    {
        $countQuery = "SELECT COUNT(*) as total FROM `staff` JOIN `staff_login` ON `staff`.`id` = `staff_login`.`staffId` WHERE `staff`.`status_id`='$statusId' AND 1";

        if (!empty($memberId)) {
            $countQuery .= " AND `staff_id` LIKE '%$memberId%'";
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

    public static function loadStaffDetails($id)
    {
        $rs = Database::search("SELECT * FROM `staff` INNER JOIN `staff_login` ON `staff`.`id` = `staff_login`.`staffId` WHERE `staff_id` = '$id'");
        return $rs;
    }

    public static function getStaffbyID($user_id)
    {
        $rs = Database::search("SELECT * FROM `staff` JOIN `staff_login` ON `staff`.`id` = `staff_login`.`staffId` WHERE `staff_id` = '$user_id'");
        return $rs->fetch_assoc();
    }


    public static function UpdateStaffDetails($user_id, $fname, $lname, $email, $phone, $address, $nic)
    {

        Database::ud("UPDATE `staff` INNER JOIN `staff_login` ON `staff`.`id` = `staff_login`.`staffId` SET 
                `fname` = '$fname', 
                `lname` = '$lname', 
                `mobile` = '$phone',  
                `address` = '$address', 
                `nic` = '$nic',
                `email` = '$email' 
                WHERE `staff_id` = '$user_id'");
        return true;
    }


    public static function loadMailDetails($id)
    {
        $rs = Database::search("SELECT * FROM `staff` INNER JOIN `staff_login` ON `staff`.`id` = `staff_login`.`staffId` WHERE `staff_id` = '$id'");
        return $rs;
    }

    public static function deactivateStaff($id)
    {
        $rs = Database::ud("UPDATE `staff` SET `status_id`='2' WHERE `id`='$id'");
        return true;
    }

    public static function activateStaff($id)
    {
        $rs = Database::ud("UPDATE `staff` SET `status_id`='1' WHERE `id`='$id'");
        return true;
    }

    public static function generateKey($email, $role_id)
    {
        // Step 1: Generate a random key (e.g., 16 bytes long)
        $key = strtoupper(bin2hex(random_bytes(16)));  // Generates a 32-character key (16 bytes)

        Database::insert("INSERT INTO staff_key (`email`,`key_value`,`role_id`) VALUES ('$email','$key','$role_id')");
        return $key;
    }

    public static function sendEnrollmentKey($email, $role_id)
    {
        require_once Config::getServicePath('emailService.php');

        $key = self::generateKey($email, $role_id);
        $subject = 'Staff Enrollment Key';
        $body = '<h1 style="padding-top: 30px;">Shelf Loom</h1>
        <p style="font-size: 30px; color: black; font-weight: bold; text-align: center;">Welcome!</p> 
        <p>Mrs,</p>
        <div style="max-width: 600px; margin: 0 auto; padding: 20px; text-align: left;">
            <p>We are pleased to connect with you! Here’s some important information:</p>
            <h2>Your enrollment key is <br> ' . $key . '</h2>
           
            <p>If you have any questions or issues, please reach out to us.</p>
            <p>Call:[tel_num]</p>
            <div style="margin-top: 20px;">
                <p>Best regards,</p>
                <p>Shelf Loom Team</p>
            </div>
        </div>';

        $emailService = new EmailService();
        $emailSent = $emailService->sendEmail($email, $subject, $body);

        return true;
    }
}
