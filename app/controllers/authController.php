<?php
require_once __DIR__ . '/../../main.php';

class AuthController
{
    private $authModel;

    public function __construct()
    {
        require_once Config::getModelPath('authmodel.php');
        $this->authModel = new AuthModel();
    }

    public static function login()
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {

            // Retrieve login credentials from the POST request
            $memberid = $_POST['memberid'];
            $memberpw = $_POST['memberpw'];
            $rememberme = isset($_POST['rememberme']) ? $_POST['rememberme'] : 0;

            // Validate the Member ID and Password using the AuthModel
            $userDetails = AuthModel::validateLogin($memberid, $memberpw);

            if ($userDetails) {
                session_regenerate_id(true); // Prevent session fixation

                // Store user details in the session upon successful login
                $_SESSION['member'] = [
                    'member_id' => $userDetails['member_id'],
                    'id' => $userDetails['id'],
                    'profile_img' => $userDetails['profile_img'],
                    'lname' => $userDetails['lname'],
                    'fname' => $userDetails['fname'],
                    'last_activity' => time() // Track last activity

                ];

                // If "Remember Me" is checked, set cookies 
                if ($rememberme) {
                    setcookie("memberid", $memberid, time() + (365 * 24 * 60 * 60), "/");
                    setcookie("memberpw", $memberpw, time() + (365 * 24 * 60 * 60), "/");
                } else {
                    setcookie("memberid", "", time() - 3600, "/");
                    setcookie("memberpw", "", time() - 3600, "/");
                }

                // Return a success response in JSON format
                echo json_encode(["success" => true, "message" => "Login successful!"]);
                exit;
            } else {

                // If login fails, return an error response in JSON format
                echo json_encode(["success" => false, "message" => "Invalid Member ID or Password."]);
                exit;
            }
        }
    }

    public static function sendOTP()
    {
        require_once Config::getServicePath('emailService.php');

        if ($_SERVER["REQUEST_METHOD"] == "POST") {
            $email = $_POST['email'];

            $otp = rand(100000, 999999);

            $_SESSION['otp'] = $otp;
            $_SESSION['otp_expiry'] = time() + (5 * 60); // Current time + 5 minutes
            $_SESSION['otp_email'] = $email; // Store email for verification

            $subject = "Email Verification";

            $body = '
                <h1 style="padding-top: 30px;">Email Verification</h1>
               <p style = "font-size: 30px; color: black; font-weight: bold; text-align: center;">Shelf Loom</p> 

               <div style="max-width: 600px; margin: 0 auto; padding: 20px; text-align: left;">
                 
                  <p>Here is the OTP to verify your email address.</p>
                  <h1>' . $otp . '</h1>
        
                  
                  <div>
                        <p style="margin: 0px;">If you have problems or questions regarding your account, please contact us.</p>
                        <p style="margin: 0px;">Call: [tel_num]</p>
                  </div>

                  <div>
                        <p style="margin-bottom: 0px;">Best regards,</p>
                        <p style="margin: 0px;">Shelf Loom</p>
                  </div>
               </div>';


            $emailService = new EmailService();
            $emailSent = $emailService->sendEmail($email, $subject, $body);
            if ($emailSent) {

                echo json_encode(["success" => true, "message" => "OTP sent. Check your email!"]);
            } else {
                echo json_encode(["success" => false, "message" => "Failed to send email."]);
            }
        } else {
            echo json_encode(["success" => false, "message" => "Invalid Request"]);
        }
    }

    public static function verifyOTP()
    {
        if ($_SERVER["REQUEST_METHOD"] == "POST") {
            $enteredOTP = $_POST['userotp'];

            if (isset($_SESSION['otp']) && isset($_SESSION['otp_expiry']) && time() < $_SESSION['otp_expiry']) {
                if ($enteredOTP == $_SESSION['otp']) {
                    echo json_encode(["success" => true, "message" => "OTP verified successfully."]);


                    unset($_SESSION['otp']);
                    unset($_SESSION['otp_expiry']);
                    unset($_SESSION['otp_email']);
                } else {
                    echo json_encode(["success" => false, "message" => "Invalid OTP."]);
                }
            } else {
                echo json_encode(["success" => false, "message" => "OTP expired or not found. Please request a new OTP."]);
            }
        }
    }

    public static function registerMember()
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $nic = $_POST['nic'];
            $address = $_POST['address'];
            $mobile = $_POST['mobile'];
            $email = $_POST['email'];
            $fname = $_POST['fname'];
            $lname = $_POST['lname'];
            $password = $_POST['password'];
            $transactionId = $_POST['transactionId'];


            $id = AuthModel::registerMember($nic, $address, $mobile, $email, $fname, $lname);
            $result = PaymentModel::insertPayment($transactionId, $id);

            if ($result) {
                echo json_encode(["success" => true, "message" => "Thank you for registering! Your Library Membership ID will be issued by the library. This process may take some time. Please check your email"]);
                exit();
            } else {
                echo json_encode(["success" => false, "message" => "Registration Failed!"]);
                exit();
            }
        }
    }

    public static function forgotPassword()
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $email = $_POST['email'];
            $vcode = uniqid();

            $result = AuthModel::validateEmail($email, $vcode);

            if ($result) {
                self::sendResetLink($email, $vcode);
                exit();
            } else {

                echo json_encode(["success" => false, "message" => "Account not found!"]);
                exit();
            }
        }
    }

    public static function sendResetLink($email, $vcode)
    {
        require_once Config::getServicePath('emailService.php');


        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $email = $_POST["email"];
            $subject = "Reset Password";

            $body = '
               <h1 style="padding-top: 30px;">Reset your password</h1>
               <p style = "font-size: 30px; color: black; font-weight: bold; text-align: center;">Shelf Loom</p> 

               <div style="max-width: 600px; margin: 0 auto; padding: 20px; text-align: left;">
                  <p>Dear Member,</p>
                  <p>We received a request to reset the password for your account. If you initiated this request, please click the button below to create a new password.</p>
                  <div style="margin-bottom: 10px;">
                        <a href="http://localhost/LMS/public/member/index.php?action=showresetpw&vcode=' . $vcode . '">Click here to reset your password</a>
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

            $emailService = new EmailService();
            $emailSent = $emailService->sendEmail($email, $subject, $body);

            if ($emailSent) {
                echo json_encode(["success" => true, "message" => "Email for reset password sent! Check Your email"]);
            } else {
                echo json_encode(["success" => false, "message" => "Failed to send email."]);
            }
        } else {

            echo json_encode(["success" => false, "message" => "Invalid Request"]);
        }
    }


    public static function resetPassword()
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $password = $_POST['password'];
            $vcode = isset($_POST['vcode']) ? trim($_POST['vcode']) : '';
            $id = isset($_POST['id']) ? trim($_POST['id']) : '';


            if (empty($vcode)) {
                $result = AuthModel::changePasswordwithid($password, $id);
                return;
            }

            if (empty($id)) {
                $result = AuthModel::changePasswordwithvcode($password, $vcode);
            }
        }
        if ($result) {
            echo json_encode(["success" => true, "message" => "Password reset successfully."]);
            exit();
        } else {
            echo json_encode(["success" => false, "message" => "Password reset failed."]);
        }
    }
}
