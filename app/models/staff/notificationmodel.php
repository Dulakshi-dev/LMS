<?php

require_once config::getdbPath();

class NotificationModel
{
    public static function insertNotification($email, $subject)
    {
        // Search for the member_id
        $rs = Database::search("SELECT `member_id` FROM `member` 
                                INNER JOIN `member_login` ON `member`.`id` = `member_login`.`memberId` 
                                WHERE email = '$email'");

        if ($rs->num_rows > 0) {
            $row = $rs->fetch_assoc();
            $member_id = $row["member_id"];

            Database::insert("INSERT INTO `notification` (`message`, `created_at`, `status`, `receiver_id`) 
                                        VALUES ('$subject', NOW(), 'unread', '$member_id')");

            return true;
        } else {
            return false;
        }
    }
}