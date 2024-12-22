<?php
require_once __DIR__ . '/../models/UserModel.php';
require_once __DIR__ . '/../../mail/PHPMailer.php';
require_once __DIR__ . '/../../mail/SMTP.php';
require_once __DIR__ . '/../../mail/Exception.php';

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

class ForgotPasswordController {

    // Handle Forgot Password request
    public static function forgotPassword($email) {
        if (empty($email)) {
            return "Please enter your email address.";
        }

        $user = UserModel::getUserByEmail($email);
        if ($user) {
            $vcode = uniqid();
            UserModel::updateVerificationCode($user['id'], $vcode); 

            // Send reset password email
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
                      <p>Dear,</p>
                      <p>We received a request to reset the password for your account. If you initiated this request, please click the button below to create a new password.</p>
                      <div style="margin-bottom: 10px;">
                            <a href="http://localhost/LMS - Copy/public/reset-password.php?code='.$vcode.'">Click here to reset your password</a>
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
                return "Check your email to reset your password.";
            } catch (Exception $e) {
                return "Message could not be sent. Mailer Error: " . $mail->ErrorInfo;
            }
        } else {
            return "User not found with the given email.";
        }
    }

    // Handle Reset Password request
    public static function resetPassword($vcode, $password) {
        if (empty($vcode)) {
            return "Invalid or missing verification code.";
        }

        $user = UserModel::getUserByVerificationCode($vcode);
        if ($user) {
            UserModel::updatePassword($user['id'], $password);
            UserModel::clearVerificationCode($user['id']);
            return "Your password has been reset successfully.";
        } else {
            return "Invalid verification code.";
        }
    }
}
