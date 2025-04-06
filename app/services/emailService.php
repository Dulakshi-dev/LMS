<?php
require_once __DIR__ . '/../../main.php';
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require_once Config::getMailPath("PHPMailer.php");
require_once Config::getMailPath("SMTP.php");
require_once Config::getMailPath("Exception.php");
require_once Config::getMailPath("emailTemplate.php");


require_once Config::getModelPath('member','homemodel.php');


class EmailService
{
    public static function sendEmail($recipientEmail, $subject, $body)
    {
        $mail = new PHPMailer(true);

        try {
            // Fetch library email details
            $libraryData = HomeModel::getLibraryInfo();
            $libraryName = $libraryData['name'];
            $libraryEmail = $libraryData['email'];

            // SMTP server settings
            $mail->isSMTP();
            $mail->Host = 'smtp.gmail.com'; 
            $mail->SMTPAuth = true;
            $mail->Username = $libraryEmail; 
            $mail->Password = 'ovvrqsxjkwcqmqdv'; // Replace with actual App Password
            $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS; // Use TLS instead of SSL
            $mail->Port = 587; // Use Port 587 (more stable than 465)

            // Debugging logs (enable for troubleshooting)
            $mail->SMTPDebug = 2;  // Change to 0 for production
            $mail->Debugoutput = function ($str, $level) {
                error_log("PHPMailer [$level]: $str");
            };

            // Email details
            $mail->setFrom($libraryEmail, $libraryName);
            $mail->addAddress($recipientEmail);
            $mail->isHTML(true);
            $mail->Subject = $subject;
            $mail->Body = $body;

            // Send the email
            $mail->send();
            return true;
        } catch (Exception $e) {
            error_log("Email failed: " . $mail->ErrorInfo);
            return false;
        }
    }
}
?>
