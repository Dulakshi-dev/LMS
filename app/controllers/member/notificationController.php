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
                $this->jsonResponse(["message" => "Member not logged in."], false);
            }

            $notifications = NotificationModel::getNotifications($member_id);

            $this->jsonResponse(["notifications" => $notifications ?? []]);
        } else {
            $this->jsonResponse(["message" => "Invalid request."], false);
        }
    }

    public function markAsRead()
    {
        if ($this->isPost()) {
            $notification_id = $this->getPost('notification_id');

            if (!$notification_id) {
                $this->jsonResponse(["message" => "Notification ID is required."], false);
            }

            $result = NotificationModel::markAsRead($notification_id);

            if ($result) {
                $this->jsonResponse(["message" => "Notification marked as read."]);
            } else {
                $this->jsonResponse(["message" => "Failed to mark as read."], false);
            }
        } else {
            $this->jsonResponse(["message" => "Invalid request."], false);
        }
    }

    public function getUnreadCount()
    {
        if ($_SERVER['REQUEST_METHOD'] === 'GET') {
            $member_id = $_SESSION["member"]["member_id"] ?? null;

            if (!$member_id) {
                $this->jsonResponse(["count" => 0], false);
            }

            $count = NotificationModel::getUnreadNotificationCount($member_id);
            $this->jsonResponse(["count" => $count]);
        } else {
            $this->jsonResponse(["message" => "Invalid request."], false);
        }
    }
}
