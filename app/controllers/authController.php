<?php
session_start();
require_once __DIR__ . '/../../main.php';

class authController
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
                $_SESSION["member"] = $userDetails;

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

            // Store OTP in session with expiry time (5 minutes)
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

    public static function proceedPayment()
    {
        require_once Config::getServicePath('paymentService.php');

        if ($_SERVER["REQUEST_METHOD"] == "POST") {
            $paymentService = new PaymentService();
            $paymentService->createPayment();
        } else {
            echo json_encode(["success" => false, "message" => "Invalid Request"]);
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

            $result = AuthModel::registerMember($nic, $address, $mobile, $email, $fname, $lname, $password);

            if ($result) {
                echo json_encode(["success" => true, "message" => "Thank you for registering! Your Library Membership ID will be issued by the library. This process may take some time. Please check your email"]);
                exit();

            } else {
                echo json_encode(["success" => false, "message" => "Registration Failed!"]);
                exit();
            }
        }
    }
}
