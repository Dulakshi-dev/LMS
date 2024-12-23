<?php
require_once __DIR__ . '/../../main.php';
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;


require_once Config::getMailPath("PHPMailer.php");
require_once Config::getMailPath("SMTP.php");
require_once Config::getMailPath("Exception.php");

class EmailService
{
    public function sendEmail($recipientEmail, $subject, $body)
    {
        $mail = new PHPMailer(true);

        try {
            // Server settings
            $mail->isSMTP();
            $mail->Host = 'smtp.gmail.com'; // Replace with your SMTP server
            $mail->SMTPAuth = true;
            $mail->Username = 'marosadilove@gmail.com'; 
            $mail->Password = 'ffgjxvcvmfdowsnj'; // Use app password
            $mail->SMTPSecure = PHPMailer::ENCRYPTION_SMTPS; 
            $mail->Port = 465; 

            // Recipients
            $mail->setFrom('marosadilove@gmail.com', 'Malanka Tharula'); 
            $mail->addAddress($recipientEmail); 

            // Content
            $mail->isHTML(true);
            $mail->Subject = $subject;
            $mail->Body = $body;

            $mail->send();
            return true;
        } catch (Exception $e) {
            return false; // Log or handle error as needed
        }
    }
}
?>
