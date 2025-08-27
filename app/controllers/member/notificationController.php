<?php

require_once __DIR__ . '/../../../main.php';

class NotificationController extends Controller
{
    private $notificationModel;

    public function __construct()
    { // Load the notification model which handles database operations for notifications
        require_once Config::getModelPath('member', 'notificationmodel.php');
        $this->notificationModel = new NotificationModel();
    }

    public function loadNotification()// Function to load all notifications for a logged-in member
    {
        if ($_SERVER['REQUEST_METHOD'] === 'GET') { // Only allow GET requests
            $member_id = $_SESSION["member"]["member_id"] ?? null;

            if (!$member_id) { // Check if member is logged in
                Logger::warning("Load notifications failed - member not logged in");
                $this->jsonResponse(["message" => "Member not logged in."], false);
                return;
            }
            // Call model to get notifications
            $notifications = NotificationModel::getNotifications($member_id);
             // Return notifications as JSON
            $this->jsonResponse(["notifications" => $notifications ?? []]);
        } else {
            Logger::warning("Load notifications failed - invalid request method", ['method' => $_SERVER['REQUEST_METHOD']]);  // Invalid request method
            $this->jsonResponse(["message" => "Invalid request."], false);
        }
    }

    public function markAsRead() // Function to mark a notification as read
    {
        if ($this->isPost()) {  // Only allow POST requests
            $notification_id = $this->getPost('notification_id');

            if (!$notification_id) {  // Check if notification ID is provided
                Logger::warning("Mark as read failed - no notification_id provided");
                $this->jsonResponse(["message" => "Notification ID is required."], false);
                return;
            }
            // Call model to mark notification as read
            $result = NotificationModel::markAsRead($notification_id);

            if ($result) {
                Logger::info("Notification marked as read", ['notification_id' => $notification_id]);
                $this->jsonResponse(["message" => "Notification marked as read."]);
            } else {
                Logger::error("Failed to mark notification as read", ['notification_id' => $notification_id]);
                $this->jsonResponse(["message" => "Failed to mark as read."], false);
            }
        } else {// Invalid request method
            Logger::warning("Mark as read failed - invalid request method", ['method' => $_SERVER['REQUEST_METHOD']]);
            $this->jsonResponse(["message" => "Invalid request."], false);
        }
    }

    public function getUnreadCount()// Function to get count of unread notifications for a member
    {
        if ($_SERVER['REQUEST_METHOD'] === 'GET') { // Only allow GET requests
            $member_id = $_SESSION["member"]["member_id"] ?? null;

            if (!$member_id) { // Check if member is logged in
                Logger::warning("Get unread count failed - member not logged in");
                $this->jsonResponse(["count" => 0], false);
                return;
            }
             // Call model to get unread notifications count
            $count = NotificationModel::getUnreadNotificationCount($member_id);

            $this->jsonResponse(["count" => $count]); // Return count as JSON
        } else {  // Invalid request method
            Logger::warning("Get unread count failed - invalid request method", ['method' => $_SERVER['REQUEST_METHOD']]);
            $this->jsonResponse(["message" => "Invalid request."], false);
        }
    }
}
