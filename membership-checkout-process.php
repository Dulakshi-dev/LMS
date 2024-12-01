<?php 
include "connection.php"; 
session_start(); 

// $userId = $_SESSION["user"]["id"]; 

if (isset($_POST["payment"])) { 
    $payment = json_decode($_POST["payment"], true); 
    $date = new DateTime("now", new DateTimeZone("Asia/Colombo")); 
    $paymentDate = $date->format("Y-m-d H:i:s"); 

    // Record payment in the database
    //Database::iud("INSERT INTO `membership_payments` (`order_id`, `payment_date`, `amount`, `user_id`) VALUES ('".$payment["order_id"]."', '$paymentDate', '".$payment["amount"]."', '$userId')"); 

    // Update membership status
    //Database::iud("UPDATE `users` SET `membership_active` = 1 WHERE `id` = '$userId'"); 

    $json = ["status" => "success", "ohId" => $payment["order_id"]]; 
} else { 
    $json = ["status" => "error", "error" => "Invalid Request"]; 
} 

echo json_encode($json); 

