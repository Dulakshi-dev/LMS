<?php

require_once config::getdbPath();

class NotificationModel
{
    function sendNotification($member_id, $message) {
    
        Database::insert("INSERT INTO `notification` (`message`, `created_at`, `status`, `receiver_id`) 
        VALUES ('$message', NOW(), 'unread', '$member_id')");
    return true;
    }




}