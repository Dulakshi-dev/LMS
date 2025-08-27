<?php

require_once config::getdbPath();

class NotificationModel
{
    public static function insertNotification($email, $msg)
    {
        // Search for the member_id safely
        $searchQuery = "SELECT `member_id` 
                        FROM `member` 
                        INNER JOIN `member_login` ON `member`.`id` = `member_login`.`memberId` 
                        WHERE `email` = ?";
        $searchParams = [$email];
        $searchTypes = "s";

        $rs = Database::search($searchQuery, $searchParams, $searchTypes);

        if ($rs->num_rows > 0) {
            $row = $rs->fetch_assoc();
            $member_id = $row["member_id"];

            // Insert notification safely
            $insertQuery = "INSERT INTO `notification` (`message`, `created_at`, `status`, `receiver_id`) 
                            VALUES (?, NOW(), 'unread', ?)";
            $insertParams = [$msg, $member_id];
            $insertTypes = "si";

            Database::insert($insertQuery, $insertParams, $insertTypes);

            return true;
        } else {
            return false;
        }
    }
}
