<?php 
session_start();
include "connection.php"; 

use PHPMailer\PHPMailer\PHPMailer; 
use PHPMailer\PHPMailer\SMTP; 
use PHPMailer\PHPMailer\Exception; 

require "mail/PHPMailer.php"; 
require "mail/SMTP.php"; 
require "mail/Exception.php"; 

// $name = $_POST["name"]; 
$email = $_POST["email"]; 
$subject = $_POST["subject"]; 
$msg = $_POST["message"];

$mail = new PHPMailer(true); 

try { 
    // SMTP server configuration
    $mail->isSMTP(); 
    $mail->Host = 'smtp.gmail.com'; 
    $mail->SMTPAuth = true; 
    $mail->Username = 'marosadilove@gmail.com'; 
    $mail->Password = 'ffgjxvcvmfdowsnj'; // Use app password
    $mail->SMTPSecure = PHPMailer::ENCRYPTION_SMTPS; 
    $mail->Port = 465; 

    // Sender and recipient settings
    $mail->setFrom('marosadilove@gmail.com', 'Malanka Tharula'); 
    $mail->addAddress($email); 

    // Email content
    $mail->isHTML(true); 
    $mail->Subject = ''.$subject.''; 
    $mail->Body = '
        <h1 style="padding-top: 30px;">Shelf Loom</h1>
        <p style="font-size: 30px; color: black; font-weight: bold; text-align: center;">Welcome!</p> 

        <div style="max-width: 600px; margin: 0 auto; padding: 20px; text-align: left;">
            <p>We are pleased to connect with you! Here’s some important information:</p>
            <p>'.$msg.'</p>
            <p>If you have any questions or issues, please reach out to us.</p>
            <p>Call:[tel_num]</p>

            <div style="margin-top: 20px;">
                <p>Best regards,</p>
                <p>Shelf Loom Team</p>
            </div>
        </div>';

    // Send the email
    $mail->send(); 
    echo 'success'; 
} catch (Exception $e) { 
    echo "Message could not be sent. Mailer Error: {$mail->ErrorInfo}"; 
} 
