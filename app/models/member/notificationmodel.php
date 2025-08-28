<?php

require_once config::getdbPath();
/**
 * NotificationModel
 * Handles notifications for members including fetching, marking as read, and counting unread notifications.
 */

class NotificationModel
{
    public static function getNotifications($member_id) {
        $query = "SELECT `notification_id`, `message`, `created_at`, `status` FROM `notification` WHERE `receiver_id` = ? ORDER BY `created_at` DESC";
        $params = [$member_id];
        $types = "s";
        $result = Database::search($query, $params, $types);

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
        $query = "UPDATE `notification` SET `status` = 'read' WHERE `notification_id` = ?";
        $params = [$notification_id];
        $types = "i";
        Database::ud($query, $params, $types);

        return true;
    }
    /**
     * getUnreadNotificationCount
     * Counts all unread notifications for a member.
     * @param string $member_id Member ID
     * @return int Returns the number of unread notifications
     */
    public static function getUnreadNotificationCount($member_id) {
        
        
         $query = "SELECT COUNT(*) as count FROM `notification` WHERE `receiver_id` = ? AND `status` = 'unread'";
        $params = [$member_id];
        $types = "s";
        $result = Database::search($query, $params, $types);
        
        $row = $result->fetch_assoc();
        return $row["count"] ?? 0;
    }
}
