<?php
require_once __DIR__ . '/../../main.php';

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require_once Config::getMailPath("PHPMailer.php");
require_once Config::getMailPath("SMTP.php");
require_once Config::getMailPath("Exception.php");
require_once Config::getMailPath("emailTemplate.php");

require_once Config::getModelPath('member', 'homemodel.php');

class EmailService
{
    public static function sendEmail($recipientEmail, $subject, $body, $fromEmail = null, $fromName = null)
    {
        $mail = new PHPMailer(true);

        try {
            $libraryData = HomeModel::getLibraryInfo();
            $libraryName = $libraryData['name'];
            $libraryEmail = $libraryData['email'];

            $mail->isSMTP();
            $mail->Host = 'smtp.gmail.com';
            $mail->SMTPAuth = true;
            $mail->Username = $libraryEmail;
            $mail->Password = 'wchzlcndajnjfsyi'; // Your app password
            $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
            $mail->Port = 587;

            $mail->SMTPDebug = 2;
            $mail->Debugoutput = function ($str, $level) {
                error_log("PHPMailer [$level]: $str");
            };

            // Use passed sender info or default to library
            $mail->setFrom($fromEmail ?? $libraryEmail, $fromName ?? $libraryName);
            $mail->addAddress($recipientEmail);
            $mail->isHTML(true);
            $mail->Subject = $subject;
            $mail->Body = $body;

            $mail->send();
            return true;
        } catch (Exception $e) {
            error_log("Email failed: " . $mail->ErrorInfo);
            return false;
        }
    }
}
