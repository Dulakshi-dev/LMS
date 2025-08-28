<?php

// Load the main project setup file
require_once __DIR__ . '/../../../main.php';

// Load the base Controller class from system folder
require_once Config::getControllerPath('system', 'controller.php');

// Controller that manages notifications
class NotificationController extends Controller
{
    // Holds the notification model object
    private $notificationModel;

    // Constructor: load the notification model when the controller is created
    public function __construct()
    {
        require_once Config::getModelPath('staff', 'notificationmodel.php');
        $this->notificationModel = new NotificationModel();
    }

    // Insert a new notification into the system
    public function insertNotification($email, $msg)
    {
        // Call model function to insert notification
        $result = NotificationModel::insertNotification($email, $msg);
        
        if ($result) {
            // Log success if notification was inserted
            Logger::info("Notification inserted successfully", ['email' => $email]);
            return true;
        } else {
            // Log error if insertion failed
            Logger::error("Failed to insert notification", ['email' => $email]);
            return false;
        }
    }
}
