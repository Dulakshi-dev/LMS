<?php

require_once __DIR__ . '/../../../main.php';

class NotificationController extends Controller
{
    private $notificationModel;

    public function __construct()
    {
        require_once Config::getModelPath('member', 'notificationmodel.php');
        $this->notificationModel = new NotificationModel();
    }

    public function loadNotification()
    {
        if ($_SERVER['REQUEST_METHOD'] === 'GET') {
            $member_id = $_SESSION["member"]["member_id"] ?? null;

            if (!$member_id) {
                Logger::warning("Load notifications failed - member not logged in");
                $this->jsonResponse(["message" => "Member not logged in."], false);
                return;
            }

            $notifications = NotificationModel::getNotifications($member_id);

            $this->jsonResponse(["notifications" => $notifications ?? []]);
        } else {
            Logger::warning("Load notifications failed - invalid request method", ['method' => $_SERVER['REQUEST_METHOD']]);
            $this->jsonResponse(["message" => "Invalid request."], false);
        }
    }

    public function markAsRead()
    {
        if ($this->isPost()) {
            $notification_id = $this->getPost('notification_id');

            if (!$notification_id) {
                Logger::warning("Mark as read failed - no notification_id provided");
                $this->jsonResponse(["message" => "Notification ID is required."], false);
                return;
            }

            $result = NotificationModel::markAsRead($notification_id);

            if ($result) {
                Logger::info("Notification marked as read", ['notification_id' => $notification_id]);
                $this->jsonResponse(["message" => "Notification marked as read."]);
            } else {
                Logger::error("Failed to mark notification as read", ['notification_id' => $notification_id]);
                $this->jsonResponse(["message" => "Failed to mark as read."], false);
            }
        } else {
            Logger::warning("Mark as read failed - invalid request method", ['method' => $_SERVER['REQUEST_METHOD']]);
            $this->jsonResponse(["message" => "Invalid request."], false);
        }
    }

    public function getUnreadCount()
    {
        if ($_SERVER['REQUEST_METHOD'] === 'GET') {
            $member_id = $_SESSION["member"]["member_id"] ?? null;

            if (!$member_id) {
                Logger::warning("Get unread count failed - member not logged in");
                $this->jsonResponse(["count" => 0], false);
                return;
            }

            $count = NotificationModel::getUnreadNotificationCount($member_id);

            $this->jsonResponse(["count" => $count]);
        } else {
            Logger::warning("Get unread count failed - invalid request method", ['method' => $_SERVER['REQUEST_METHOD']]);
            $this->jsonResponse(["message" => "Invalid request."], false);
        }
    }
}
