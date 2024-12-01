<?php 
include "connection.php"; 
session_start(); 

///$user = $_SESSION["user"]; 
// $userId = $user["id"]; 
$error = ''; 

if (isset($_POST["membership_fee"])) { 
    $merchantId = "1227820"; 
    $merchantSecret = "MjkxNDc2MDQyOTIzOTM4NDQ5NDUzNTY2NzU3ODU0MTAxMTY3Njg1OA=="; 
    $membershipFee = 500; // Fixed annual membership fee
    $currency = "LKR"; 
    $orderId = uniqid(); 

    $hash = strtoupper(md5($merchantId . $orderId . number_format($membershipFee, 2, '.', '') . $currency . strtoupper(md5($merchantSecret)))); 

    $payment = []; 
    $payment["sandbox"] = true; 
    $payment["merchant_id"] = $merchantId; 
    $payment["return_url"] = "http://localhost/library/membership-success.php"; 
    $payment["cancel_url"] = "http://localhost/library/membership-cancel.php"; 
    $payment["notify_url"] = "http://localhost/library/membership-notify.php"; 
    $payment["order_id"] = $orderId; 
    $payment["items"] = "Annual Membership Fee"; 
    $payment["amount"] = number_format($membershipFee, 2, '.', ''); 
    $payment["currency"] = $currency; 
    $payment["hash"] = $hash; 
    $payment["first_name"] = "d"; 
    $payment["last_name"] = "d"; 
    $payment["email"] = "d@gmail.com"; 
    $payment["phone"] = "099"; 
    $payment["address"] = "d"; 
    $payment["city"] = "d"; 
    $payment["country"] = "Sri Lanka"; 

    $json = ["status" => "success", "payment" => $payment]; 
} else { 
    $json = ["status" => "error", "error" => "Invalid Request"]; 
} 

echo json_encode($json); 





