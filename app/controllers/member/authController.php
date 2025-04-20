<?php
require_once __DIR__ . '/../../../main.php';
require_once Config::getControllerPath('system', 'controller.php');

class AuthController extends Controller
{
    private $authModel;
    private $homeModel;
    private const REMEMBER_ME_EXPIRY_DAYS = 30; 

    public function __construct()
    {
        require_once Config::getModelPath('member', 'authmodel.php');
        require_once Config::getModelPath('member', 'homemodel.php');
        $this->authModel = new AuthModel();
        $this->homeModel = new HomeModel();
        $this->handleAutoLogin();

    }

    
    private function handleAutoLogin()
    {
        if (!isset($_SESSION['member']) && isset($_COOKIE['member_remember_token'])) {
            $token = $_COOKIE['member_remember_token'];
            $userDetails = $this->authModel->getUserByRememberToken($token); // Changed to instance method
            
            if ($userDetails && password_verify($token, $userDetails['remember_token'])) {
                $this->createSession($userDetails);
                
                $newToken = bin2hex(random_bytes(32));
                $hashedToken = password_hash($newToken, PASSWORD_BCRYPT);
                
                $this->authModel->updateRememberToken($userDetails['member_id'], $hashedToken);
                $this->setSecureCookie("member_remember_token", $newToken, 60 * 60 * 24 * self::REMEMBER_ME_EXPIRY_DAYS);
                
                header("Location: index.php?action=dashboard");
                exit;
            } else {
                $this->clearCookie("member_remember_token");
            }
        }
    }

    public function login()
    {
        if ($this->isPost()) {

            // Retrieve login credentials from the POST request
            $memberid = $this->getPost('memberid');
            $memberpw = $this->getPost('memberpw');
            $rememberme = $this->getPost('rememberme', 0);

            // Validate the Member ID and Password using the AuthModel
            $userDetails = $this->authModel->validateLogin($memberid, $memberpw);

            // Check if the user is inactive
            if (isset($userDetails['status_id']) && $userDetails['status_id'] == '2') {
                $this->jsonResponse(["message" => 'deactivated'], false);
            } else if (isset($userDetails['status_id']) && $userDetails['status_id'] == '5') {
                $this->jsonResponse(["message" => 'expired'], false);
            } else {
                // Check if login was successful
                if ($userDetails) {
                    $this->createSession($userDetails);

                    if ($rememberme) {
                        // Generate and store new token
                        $token = bin2hex(random_bytes(32));
                        $hashedToken = password_hash($token, PASSWORD_BCRYPT);
                        
                        $this->authModel->storeRememberToken($userDetails['member_id'], $hashedToken);
                        $this->setSecureCookie("member_remember_token", $token, 60 * 60 * 24 * self::REMEMBER_ME_EXPIRY_DAYS);
                    } else {
                        // Clear any existing tokens if "Remember Me" not checked
                        $this->authModel->clearRememberToken($userDetails['member_id']);
                        $this->clearCookie("member_remember_token");
                    }


                    // Return a success response in JSON format
                    $this->jsonResponse(["message" => "Login successful!"]);
                } else {
                    // If login fails, return an error response in JSON format
                    $this->jsonResponse(["message" => "Invalid Member ID or Password."], false);
                }
            }
        }
    }

    private function createSession($userDetails)
    {
        session_regenerate_id(true);

        $_SESSION['member'] = [
            'member_id' => $userDetails['member_id'],
            'id' => $userDetails['id'],
            'profile_img' => $userDetails['profile_img'],
            'lname' => $userDetails['lname'],
            'fname' => $userDetails['fname'],
            'last_activity' => time() // Track last activity
        ];
        
        // Only log non-sensitive data
        error_log("Session initialized for member_id: " . $userDetails['member_id']);
    }

    private function setSecureCookie($name, $value, $duration)
    {
        $secure = isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on';
        $options = [
            'expires' => time() + $duration,
            'path' => '/',
            'domain' => '',
            'secure' => $secure,
            'httponly' => true,
            'samesite' => 'Strict'
        ];
        
        setcookie($name, $value, $options);
    }

    private function clearCookie($name)
    {
        setcookie($name, "", [
            'expires' => time() - 3600,
            'path' => '/',
            'domain' => '',
            'secure' => isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on',
            'httponly' => true
        ]);
    }

    public function sendOTP()
    {
        require_once Config::getServicePath('emailService.php');

        if ($this->isPost()) {
            $email = $this->getPost('email');
            $result = self::validateemail($email);

            if ($result) {
                $otp = rand(100000, 999999);

                $_SESSION['otp'] = $otp;
                $_SESSION['otp_expiry'] = time() + (5 * 60); // Current time + 5 minutes
                $_SESSION['otp_email'] = $email; // Store email for verification

                $subject = "Email Verification";

                $body = '
                <h4 style="font-size: 30px; color: black; font-weight: bold; text-align: center;">ONE Time Passcode</h4> 

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
                    $this->jsonResponse(["message" => "OTP sent. Check your email!"]);
                } else {
                    $this->jsonResponse(["message" => "Failed to send email."], false);
                }
            }else{
                $this->jsonResponse(["message" => "This email is already registered."], false);

            }
        } else {
            $this->jsonResponse(["message" => "Invalid Request"], false);
        }
    }

    public function validateemail()
    {
        if ($this->isPost()) {
            $email = $this->getPost('email');

            $result = AuthModel::validateEmail($email);

            if ($result) {
                return true;
            } else {
                return false;
            }
        }
    }

    public function validatenic()
    {
        if ($this->isPost()) {
            $nic = $this->getPost('nic');

            $result = AuthModel::validateNIC($nic);

            if ($result) {
                $this->jsonResponse(["message" => "Success."]);
            } else {
                $this->jsonResponse(["message" => "NIC already registered."], false);
            }
        }
    }

    public function verifyOTP()
    {
        if ($this->isPost()) {
            $enteredOTP = $this->getPost('userotp');

            if (isset($_SESSION['otp']) && isset($_SESSION['otp_expiry']) && time() < $_SESSION['otp_expiry']) {
                if ($enteredOTP == $_SESSION['otp']) {
                    $this->jsonResponse(["message" => "OTP verified successfully."]);

                    unset($_SESSION['otp']);
                    unset($_SESSION['otp_expiry']);
                    unset($_SESSION['otp_email']);
                } else {
                    $this->jsonResponse(["message" => "Invalid OTP."], false);
                }
            } else {
                $this->jsonResponse(["message" => "OTP expired or not found. Please request a new OTP."], false);
            }
        }
    }

    public function registerMember()
    {
        $libraryData = HomeModel::getLibraryInfo();
        $fee = $libraryData['membership_fee'];

        if ($this->isPost()) {
            $nic = $this->getPost('nic');
            $address = $this->getPost('address');
            $mobile = $this->getPost('mobile');
            $email = $this->getPost('email');
            $fname = $this->getPost('fname');
            $lname = $this->getPost('lname');
            $transactionId = $this->getPost('transactionId');

            $id = AuthModel::registerMember($nic, $address, $mobile, $email, $fname, $lname);
            $result = PaymentModel::registerPayment($transactionId, $id, $fee);

            if ($result) {
                $this->jsonResponse(["message" => "Thank you for registering! Your login credentials will be issued by the library. This process may take some time. Please check your email"]);
            } else {
                $this->jsonResponse(["message" => "Registration Failed!"], false);
            }
        }
    }

    public function forgotPassword()
    {
        if ($this->isPost()) {
            $email = $this->getPost('email');
            $vcode = uniqid();

            $result = AuthModel::verifyEmail($email, $vcode);

            if ($result) {
                self::sendResetLink($email, $vcode);
            } else {
                $this->jsonResponse(["message" => "Account not found!"], false);
            }
        }
    }



    public static function sendResetLink($email, $vcode)
    {
        require_once Config::getServicePath('emailService.php');

        $subject = "Reset Password";

        $body = '
            <h4 style="font-size: 30px; color: black; font-weight: bold; text-align: center;">Reset Your password</h4> 

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
            echo json_encode(["success" => true, "message" => "Email for reset password sent! Check your email"]);
        } else {
            echo json_encode(["success" => false, "message" => "Failed to send email."]);
        }
    }

    public function resetPassword()
    {
        if ($this->isPost()) {
            $password = $this->getPost('password');
            $vcode = $this->getPost('vcode', '');
            $id = $this->getPost('id', '');

            if (empty($vcode)) {
                $result = AuthModel::changePasswordwithid($password, $id);
            } else {
                $result = AuthModel::changePasswordwithvcode($password, $vcode);
            }

            if ($result) {
                $this->jsonResponse(["message" => "Password reset successfully."]);
            } else {
                $this->jsonResponse(["message" => "Password reset failed."], false);
            }
        }
    }

    public function logout()
    {
        session_start();
        session_unset();
        session_destroy();

        $this->clearCookie("member_remember_token");
        $this->clearCookie("memberid");

        header("Location: index.php?action=login");
        exit();
    }
}
