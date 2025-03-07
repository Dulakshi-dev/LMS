<?php
session_start();
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
            $memid = $_POST['memid'];
            $password = $_POST['password'];
            $rememberme = isset($_POST['rememberme']) ? true : false;

            $userDetails = AuthModel::validateLogin($memid, $password);

            if ($userDetails) {

                $_SESSION['member'] = [
                    'member_id' => $userDetails['member_id'],
                    'id' => $userDetails['id'],
                    'profile_img' => $userDetails['profile_img'],
                    'lname' => $userDetails['lname'],
                    'fname' => $userDetails['fname']
                ];


                if ($rememberme) {
                    setcookie("memberID", $memid, time() + (60 * 60 * 24 * 365), "/");
                    setcookie("password", $password, time() + (60 * 60 * 24 * 365), "/");
                } else {
                    setcookie("memberID", "", time() - 3600, "/");
                    setcookie("password", "", time() - 3600, "/");
                }
                header("Location: index.php?action=dashboard");
                exit;
            } else {
                $error = "Invalid username or password.";
                header("Location: index.php");
                exit;
            }
        }
    }

    public static function sendOTP()
    {
        require_once Config::getServicePath('emailService.php');

        if ($_SERVER["REQUEST_METHOD"] == "POST") {
            $email = $_POST['email'];

            // Generate a 6-digit OTP
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

                echo json_encode(["success" => true, "message" => "Email sent successfully!"]);
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

    // public static function proceedPayment()
    // {
    //     require_once Config::getServicePath('paymentService.php');

    //     if ($_SERVER["REQUEST_METHOD"] == "POST") {
    //         $paymentService = new PaymentService();
    //         $paymentService->createPayment();
    //     } else {
    //         echo json_encode(["success" => false, "message" => "Invalid Request"]);
    //     }
    // }

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

                $error = "Invalid Email";
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
                echo ("Email for reset sent successfully! Check Your email address");
            } else {
                echo ("Failed to send email.");
            }
        } else {
            echo ("Invalid Request");
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
            header("Location: index.php?action=login");
            exit();
        } else {
            echo "Error: Password reset failed.";
        }
    }
}
