<?php

require_once __DIR__ . '/../../../main.php';
require_once Config::getControllerPath('system', 'controller.php');

class NotificationController extends Controller
{
    private $notificationModel;

    public function __construct()
    {
        require_once Config::getModelPath('staff', 'notificationmodel.php');
        $this->notificationModel = new NotificationModel();
    }

    public function insertNotification($email, $msg)
    {
        $result = NotificationModel::insertNotification($email, $msg);
        
        if ($result) {
            Logger::info("Notification inserted successfully", ['email' => $email]);
            return true;
        } else {
            Logger::error("Failed to insert notification", ['email' => $email]);
            return false;
        }
    }
}
