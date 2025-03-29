<?php
require_once __DIR__ . '/../../main.php';
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require_once Config::getMailPath("PHPMailer.php");
require_once Config::getMailPath("SMTP.php");
require_once Config::getMailPath("Exception.php");



class EmailService
{
    public static function sendEmail($recipientEmail, $subject, $body)
    {
        $mail = new PHPMailer(true);

        $libraryData = HomeModel::getLibraryInfo();

        $libraryName = $libraryData['name']; 
        $libraryEmail = $libraryData['email']; 

        try {
            // Server settings
            $mail->isSMTP();
            $mail->Host = 'smtp.gmail.com'; // Replace with your SMTP server
            $mail->SMTPAuth = true;
            $mail->Username = $libraryEmail; 
            $mail->Password = 'ovvrqsxjkwcqmqdv';//app password 
            $mail->SMTPSecure = PHPMailer::ENCRYPTION_SMTPS; 
            $mail->Port = 465; 

            // Recipients
            $mail->setFrom($libraryEmail, $libraryName); 
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
