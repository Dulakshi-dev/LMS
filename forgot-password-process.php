<?php 
include "connection.php"; 
 
use PHPMailer\PHPMailer\PHPMailer; 
use PHPMailer\PHPMailer\SMTP; 
use PHPMailer\PHPMailer\Exception; 
 
require "mail/PHPMailer.php"; 
require "mail/SMTP.php"; 
require "mail/Exception.php"; 
 
$email = $_GET["email"]; 
 
if(empty($email)){ 
    echo("Please enter your email address"); 
}else{ 

    $rs = Database::search("SELECT * FROM `user` WHERE `email`='$email'"); 
    $num = $rs->num_rows; 
 
    if($num > 0){ 
 
        $row = $rs->fetch_assoc(); 
        $vcode = uniqid(); 
        $name = $row["fname"]." ".$row["lname"];

        Database::iud("UPDATE `user` SET `vcode` = '$vcode' WHERE `id`='".$row["id"]."'"); 
        $mail = new PHPMailer(true); 
        try { 
            $mail->isSMTP(); 
            $mail->Host = 'smtp.gmail.com'; 
            $mail->SMTPAuth = true; 
            $mail->Username = 'dulakshigamma@gmail.com'; 
            $mail->Password = 'ovvrqsxjkwcqmqdv';//app password 
            $mail->SMTPSecure = PHPMailer::ENCRYPTION_SMTPS; 
            $mail->Port = 465; 
            $mail->setFrom('dulakshigamma@gmail.com', 'Dulakshi Gammanpila'); 
            $mail->addAddress($email); 
            $mail->isHTML(true); 
            $mail->Subject = 'Reset Your account password'; 
            $mail->Body = ' 


               <h1 style="padding-top: 30px;">Reset your password</h1>
               <p style = "font-size: 30px; color: black; font-weight: bold; text-align: center;">Shelf Loom</p> 

               <div style="max-width: 600px; margin: 0 auto; padding: 20px; text-align: left;">
                  <p>Dear '.$name.',</p>
                  <p>We received a request to reset the password for your account. If you initiated this request, please click the button below to create a new password.</p>
                  <div style="margin-bottom: 10px;">
                        <a href="http://localhost/LMS/reset-password.php?code='.$vcode.'">Click here to reset your password</a>
                  </div>
                  <div>
                        <p style="margin: 0px;">If you have problems or questions regarding your account, please contact us.</p>
                        <p style="margin: 0px;">Call: [tel_num]</p>
                  </div>

                  <div>
                        <p style="margin-bottom: 0px;">Best regards,</p>
                        <p style="margin: 0px;">Shelf Loom</p>
                  </div>
               </div>';

 
            $mail->send(); 
            echo 'Check the email to reset your password'; 
        } catch (Exception $e) { 
            echo "Message could not be sent. Mailer Error: {$mail->ErrorInfo}"; 
        } 
    }else { 
        echo("User not found with the given email"); 
    } 
}