<?php
session_start();

// Store OTP and expiration time in session
$userotp = $_POST['userotp'];

if (isset($_SESSION['otp'])) {
    $otp = $_SESSION['otp'];
    
} 

// To validate OTP
if ($userotp == $_SESSION['otp']) {
    echo 'success';
} else {
    echo 'Invalid OTP';
}

?>
