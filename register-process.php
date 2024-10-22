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

<<<<<<< Updated upstream
                $result = Database::iud("INSERT INTO `member`(`member_id`, `nic`, `fname`, `lname`, `mobile`, `address`, `email`, `password`) VALUES 
                ('$memID', '$nic', '$fname', '$lname', '$phoneNumber', '$address', '$email', '$hashedPassword')");
                
                if ($result === true) {
                    echo("success");
                } else {
                    echo("Error: " . $result); // Display the actual error message
                }
            
=======
                    
                Database::iud("INSERT INTO `member`(`member_id`, `nic`, `fname`, `lname`, `mobile`, `address`, `email`, `password`) VALUES 
                ('U-1111-2222', '200180300611', 'Dulakshi', 'Gammanpila', '0706789123', '134, Ernest place, Moratuwa', 'iddcgammanpila@gamil.com', 'Dcg$11029');");
                echo("success");     
>>>>>>> Stashed changes
       
?>
