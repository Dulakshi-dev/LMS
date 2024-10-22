<?php
include "connection.php";

$memID = $_POST["memID"];
$nic = $_POST["nic"];
$address = $_POST["address"];
$phoneNumber = $_POST["phoneNumber"];
$email = $_POST["email"];
$fname = $_POST["fname"];
$lname = $_POST["lname"];
$password = $_POST["password"];
$hashedPassword = password_hash($password, PASSWORD_BCRYPT);

                    
                Database::iud("INSERT INTO `member`(`member_id`, `nic`, `fname`, `lname`, `mobile`, `address`, `email`, `password`) VALUES 
                ('$memID', '$nic', '$fname', '$lname', '$phoneNumber', '$address', '$email', '$hashedPassword');");
                echo("success");     
       
?>
