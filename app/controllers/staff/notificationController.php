<?php

require_once __DIR__ . '/../../../main.php';

class NotificationController extends Controller
{
    private $notificationModel;

    public function __construct()
    {
        require_once Config::getModelPath('staff', 'notificationmodel.php');
        $this->notificationModel = new NotificationModel();
    }

    public function insertNotification()
    {
        if ($this->isPost()) {
            $email = $this->getPost('email');
            $subject = $this->getPost('subject');
            
            $result = NotificationModel::insertNotification($email, $subject);
            
            if($result){
                return true;
            }else{
                return false;
            }
        } else {
            return false;
        }    
    }
}
