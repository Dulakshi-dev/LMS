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

                // // Insert the new member details into the database
                // Database::iud("INSERT INTO `member`(`member_id`, `nic`, `fname`, `lname`, `mobile`, `address`, `email`, `password`) VALUES 
                // ('$memID', '$nic', '$fname', '$lname', '$phoneNumber', '$address', '$email', '$hashedPassword')");
                // echo("success");
                $result = Database::iud("INSERT INTO `member`(`member_id`, `nic`, `fname`, `lname`, `mobile`, `address`, `email`, `password`) VALUES 
                ('$memID', '$nic', '$fname', '$lname', '$phoneNumber', '$address', '$email', '$hashedPassword')");
                
                if ($result === true) {
                    echo("success");
                } else {
                    echo("Error: " . $result); // Display the actual error message
                }
            
       
?>
