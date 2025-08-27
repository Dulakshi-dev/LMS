<?php
// Include main configuration and base controller
require_once __DIR__ . '/../../../main.php';
require_once Config::getControllerPath('system', 'controller.php');

class AuthController extends Controller
{
    private $authModel;  // To handle authentication-related database operations
    private $homeModel;  // To handle home-related data retrieval
    private const REMEMBER_ME_EXPIRY_DAYS = 30;  // Remember-me token valid for 30 days

    public function __construct()
    {   // Load required models
        require_once Config::getModelPath('member', 'authmodel.php');
        require_once Config::getModelPath('member', 'homemodel.php');
        $this->authModel = new AuthModel();
        $this->homeModel = new HomeModel();
        $this->handleAutoLogin();
    }
        // Automatically login user if remember-me cookie exists
    private function handleAutoLogin()
    {   
        // If session is not active but remember-me cookie is present
        if (!isset($_SESSION['member']) && isset($_COOKIE['member_remember_token'])) {
            $token = $_COOKIE['member_remember_token'];
        // Fetch user details using token
            $userDetails = $this->authModel->getUserByRememberToken($token);
        
         // If token matches
            if ($userDetails && password_verify($token, $userDetails['remember_token'])) {
                $this->createSession($userDetails);  // Create new session
        
                $newToken = bin2hex(random_bytes(32)); // Generate and save a new remember token for security
                $hashedToken = password_hash($newToken, PASSWORD_BCRYPT);

                $this->authModel->updateRememberToken($userDetails['member_id'], $hashedToken);
                $this->setSecureCookie("member_remember_token", $newToken, 60 * 60 * 24 * self::REMEMBER_ME_EXPIRY_DAYS);

                header("Location: index.php?action=dashboard");  // Redirect to dashboard
                exit;
            } else {
                $this->clearCookie("member_remember_token"); // If token is invalid, clear the cookie
            }
        }
    }

    public function login()
    {   // Only process login if form is submitted (POST)
        if ($this->isPost()) {
            $memberid = $this->getPost('memberid'); // Get member ID
            $memberpw = $this->getPost('memberpw'); // Get password
            $rememberme = $this->getPost('rememberme', 0); // Check if remember-me is selected

            Logger::info('Login attempt received', ['member_id' => $memberid]);

            $userDetails = $this->authModel->validateLogin($memberid, $memberpw); // Validate user login

            if (isset($userDetails['status_id']) && $userDetails['status_id'] == '2') {  // Check account status
                Logger::warning('Deactivated account tried to login', ['member_id' => $memberid]);
                $this->jsonResponse(["message" => 'deactivated'], false); // Account deactivated
            } elseif (isset($userDetails['status_id']) && $userDetails['status_id'] == '5') {
                Logger::warning('Expired account tried to login', ['member_id' => $memberid]);
                $this->jsonResponse(["message" => 'expired'], false);  // Account expired
            } elseif ($userDetails) {
                $this->createSession($userDetails); // If login is successful, create session

                if ($rememberme) { // Handle remember-me option
                    $token = bin2hex(random_bytes(32)); // Generate new token and save in database + cookie
                    $hashedToken = password_hash($token, PASSWORD_BCRYPT);

                    $this->authModel->storeRememberToken($userDetails['member_id'], $hashedToken);
                    $this->setSecureCookie("member_remember_token", $token, 60 * 60 * 24 * self::REMEMBER_ME_EXPIRY_DAYS);

                    Logger::info('Remember me token set', ['member_id' => $userDetails['member_id']]);
                } else {
                    $this->authModel->clearRememberToken($userDetails['member_id']); // Clear remember-me if not selected
                    $this->clearCookie("member_remember_token");
                    Logger::info('Remember me token cleared', ['member_id' => $userDetails['member_id']]);
                }

                Logger::info('User successfully logged in', ['member_id' => $userDetails['member_id']]);
                $this->jsonResponse(["message" => "Login successful!"]);// Login success response
            } else {
                Logger::error('Invalid login attempt', ['member_id' => $memberid]); // Invalid credentials
                $this->jsonResponse(["message" => "Invalid Member ID or Password."], false);
            }
        }
    }

    private function createSession($userDetails)
    {
        session_regenerate_id(true);  // Regenerate session ID for security

        $_SESSION['member'] = [  // Store user details in session
            'member_id' => $userDetails['member_id'],
            'id' => $userDetails['id'],
            'profile_img' => $userDetails['profile_img'],
            'lname' => $userDetails['lname'],
            'fname' => $userDetails['fname'],
            'last_activity' => time() // Track last activity
        ];

        // Only log non-sensitive data
        Logger::info("Session initialized for member_id: " . $userDetails['member_id']);
    }

    private function setSecureCookie($name, $value, $duration)
    {   
         // Set secure cookie with HTTPOnly and SameSite protection
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
    {   // Expire cookie immediately
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
        require_once Config::getServicePath('emailService.php');// Load email service

        if ($this->isPost()) {
            $email = $this->getPost('email');
            $result = self::validateemail($email); // Check if email is valid for registration

            if ($result) {
                $otp = rand(100000, 999999); // Generate 6-digit OTP

                $_SESSION['otp'] = $otp;   // Save OTP in session with expiry (5 minutes)
                $_SESSION['otp_expiry'] = time() + (5 * 60);
                $_SESSION['otp_email'] = $email;

                $subject = "Email Verification"; // Email content
                $specificMessage = '
                <h4 style="text-align: center;">ONE Time Passcode</h4> 
                <p>Here is the OTP to verify your email address.</p>
                <h4>' . $otp . '</h4>';

                $emailTemplate = new EmailTemplate(); // Send email
                $body = $emailTemplate->getEmailBody("User", $specificMessage);

                $emailService = new EmailService();
                $emailSent = $emailService->sendEmail($email, $subject, $body);

                if ($emailSent) {
                    Logger::info("OTP sent to email", ['email' => $email]);
                    $this->jsonResponse(["message" => "OTP sent. Check your email!"]);
                } else {
                    Logger::error("Failed to send OTP email", ['email' => $email]);
                    $this->jsonResponse(["message" => "Failed to send email."], false);
                }
            } else {
                Logger::warning("Tried to send OTP to already registered email", ['email' => $email]);
                $this->jsonResponse(["message" => "This email is already registered."], false);// Email already registered
            }
        } else {
            Logger::warning("Invalid OTP request method"); // Invalid request type
            $this->jsonResponse(["message" => "Invalid Request"], false);
        }
    }


    public function validateemail()
    {
        if ($this->isPost()) {
            $email = $this->getPost('email');
         // Check if email is valid (not already in use)
            $result = AuthModel::validateEmail($email);

            if ($result) {
                return true;
            } else {
                return false;
            }
        }
    }

    public function validatenic()
    {    // Validate NIC (National Identity Card)
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
             // Check if OTP exists and is not expired
            if (isset($_SESSION['otp']) && isset($_SESSION['otp_expiry']) && time() < $_SESSION['otp_expiry']) {
                if ($enteredOTP == $_SESSION['otp']) {
                    Logger::info("OTP verified successfully", ['email' => $_SESSION['otp_email'] ?? 'unknown']);
                    $this->jsonResponse(["message" => "OTP verified successfully."]); // OTP matched

                    unset($_SESSION['otp'], $_SESSION['otp_expiry'], $_SESSION['otp_email']);// Clear OTP from session
                } else {
                    Logger::warning("Invalid OTP entered", ['email' => $_SESSION['otp_email'] ?? 'unknown']);
                    $this->jsonResponse(["message" => "Invalid OTP."], false);  // Wrong OTP
                } 
            } else {
                Logger::warning("OTP expired or not found");
                $this->jsonResponse(["message" => "OTP expired or not found. Please request a new OTP."], false);  // OTP expired or not set
            }
        }
    }


    public function registerMember()    // Get library membership fee

    {
        $libraryData = HomeModel::getLibraryInfo();
        $fee = $libraryData['membership_fee'];

        if ($this->isPost()) {  // Collect form details
            $nic = $this->getPost('nic');
            $address = $this->getPost('address');
            $mobile = $this->getPost('mobile');
            $email = $this->getPost('email');
            $fname = $this->getPost('fname');
            $lname = $this->getPost('lname');
            $transactionId = $this->getPost('transactionId');

            $id = AuthModel::registerMember($nic, $address, $mobile, $email, $fname, $lname); // Register member
            $result = PaymentModel::registerPayment($transactionId, $id, $fee);// Save payment

            if ($result) {
                Logger::info("New member registered", ['member_id' => $id, 'email' => $email]);
                $this->jsonResponse(["message" => "Thank you for registering! Your login credentials will be issued by the library."]);
            } else {
                Logger::error("Member registration failed", ['email' => $email]);
                $this->jsonResponse(["message" => "Registration Failed!"], false);
            }
        }
    }


    public function forgotPassword()
    {
        if ($this->isPost()) {
            $email = $this->getPost('email');
            $vcode = uniqid(); // Unique verification code

            $result = AuthModel::verifyEmail($email, $vcode);  // Verify if email exists

            if ($result) {
                Logger::info("Forgot password request", ['email' => $email]);
                self::sendResetLink($email, $vcode);  // Send password reset link
            } else {
                Logger::warning("Forgot password for unregistered email", ['email' => $email]);
                $this->jsonResponse(["message" => "Account not found!"], false);
            }
        }
    }

    public function sendResetLink($email, $vcode)
    {
        require_once Config::getServicePath('emailService.php'); // Load email service

         // Email subject and reset link
        $subject = "Reset Password";
        $specificMessage = '<p>We received a request to reset the password for your account. If you initiated this request, please click the button below to create a new password.</p>
                <div style="margin-bottom: 10px;">
                    <a href="http://localhost/LMS/public/member/index.php?action=showresetpw&vcode=' . $vcode . '">Reset Password</a>
                 </div>';

        $emailTemplate = new EmailTemplate();  // Send email
        $body = $emailTemplate->getEmailBody("Member", $specificMessage);

        $emailService = new EmailService();
        $emailSent = $emailService->sendEmail($email, $subject, $body);

        if ($emailSent) {
            $this->jsonResponse(["message" => "Password reset link sent! Check your email."]);
        } else {
            $this->jsonResponse(["message" => "Failed to send email."], false);
        }
    }

    public function resetPassword()
    {
        if ($this->isPost()) {
            $password = $this->getPost('password');
            $vcode = $this->getPost('vcode', '');
            $id = $this->getPost('id', '');

            if (empty($vcode)) { // Reset password using ID or verification code
                $result = AuthModel::changePasswordwithid($password, $id);
            } else {
                $result = AuthModel::changePasswordwithvcode($password, $vcode);
            }

            if ($result) {
                Logger::info("Password reset successful", ['user_id' => $id ?: 'by_vcode']);
                $this->jsonResponse(["message" => "Password reset successfully."]);
            } else {
                Logger::error("Password reset failed", ['user_id' => $id ?: 'by_vcode']);
                $this->jsonResponse(["message" => "Password reset failed."], false);
            }
        }
    }


    public function logout()
    {   // Log user logout
        if (isset($_SESSION['member']['member_id'])) {
            Logger::info("User logged out", ['member_id' => $_SESSION['member']['member_id']]);
        } else {
            Logger::info("Logout called with no active session");
        }
        // Destroy session
        session_start();
        session_unset();
        session_destroy();
        // Clear cookies
        $this->clearCookie("member_remember_token");
        $this->clearCookie("memberid");
        
        // Redirect to login page
        header("Location: index.php?action=login");
        exit();
    }
}
