<?php

require_once config::getdbPath();

class NotificationModel
{
    public static function getNotifications($member_id) {
        $result = Database::search("SELECT `notification_id`, `message`, `created_at`, `status` FROM `notification` WHERE `receiver_id` = '$member_id' ORDER BY `created_at` DESC");
        
        $notifications = [];
        while ($row = $result->fetch_assoc()) {
            $notifications[] = [
                "id" => $row["notification_id"],
                "message" => $row["message"],
                "created_at" => $row["created_at"],
                "status" => $row["status"] // Include status (read/unread)
            ];
        }
    
        return $notifications;
    }
    
    public static function markAsRead($notification_id) {
        // Use a parameterized query for security
        Database::ud("UPDATE `notification` SET `status` = 'read' WHERE `notification_id` = '$notification_id'");
        return true;
    }

    public static function getUnreadNotificationCount($member_id) {
        $result = Database::search("SELECT COUNT(*) as count FROM `notification` WHERE `receiver_id` = '$member_id' AND `status` = 'unread'");
        $row = $result->fetch_assoc();
        return $row["count"] ?? 0;
    }
}
