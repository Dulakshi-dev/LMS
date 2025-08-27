<?php

require_once __DIR__ . '/../../../main.php';
require_once Config::getControllerPath('system', 'controller.php');
require_once Config::getMailPath('emailTemplate.php');
require_once Config::getServicePath('emailService.php');

class AuthController extends Controller
{
    private $authModel;
    private const REMEMBER_ME_EXPIRY_DAYS = 7; // Reduced from 30 to 7 days

    public function __construct()
    {
        require_once Config::getModelPath('staff', 'authmodel.php');
        $this->authModel = new AuthModel();
        $this->handleAutoLogin();
    }

    private function handleAutoLogin()
    {
        if (!isset($_SESSION['staff']) && isset($_COOKIE['staff_remember_token'])) {
            $token = $_COOKIE['staff_remember_token'];

            Logger::info('Attempting auto-login via remember me token', ['token_present' => true]);

            $userDetails = AuthModel::getUserByRememberToken($token);

            if ($userDetails && password_verify($token, $userDetails['staff_remember_token'])) {
                $this->createSession($userDetails);

                // Token rotation - generate new token after each use
                $newToken = bin2hex(random_bytes(32));
                $hashedToken = password_hash($newToken, PASSWORD_BCRYPT);

                AuthModel::updateRememberToken($userDetails['staff_id'], $hashedToken);
                $this->setSecureCookie("staff_remember_token", $newToken, 60 * 60 * 24 * self::REMEMBER_ME_EXPIRY_DAYS);

                $this->loadModules($userDetails["role_id"]);

                Logger::info('Auto-login successful', ['staff_id' => $userDetails['staff_id']]);

                header("Location: index.php?action=dashboard");
                exit;
            } else {
                Logger::warning('Invalid remember me token during auto-login', ['token' => $token]);
                // Invalid token - clear it
                $this->clearCookie("staff_remember_token");
            }
        }
    }

    public function login()
    {
        if (!$this->isPost()) {
            Logger::warning('Invalid request method for login', ['method' => $_SERVER['REQUEST_METHOD']]);
            $this->jsonResponse(["message" => "Invalid request"], false);
            return;
        }

        $staffid = trim($this->getPost('staffid'));
        $password = $this->getPost('password');
        $rememberme = $this->getPost('rememberme', 0);

        Logger::info('Login attempt', ['staffid' => $staffid, 'remember_me' => (bool)$rememberme]);

        $userDetails = $this->authModel->validateLogin($staffid, $password);

        if ($userDetails) {
            $this->createSession($userDetails);

            if ($rememberme) {
                // Generate and store new token
                $token = bin2hex(random_bytes(32));
                $hashedToken = password_hash($token, PASSWORD_BCRYPT);

                $this->authModel->storeRememberToken($userDetails['staff_id'], $hashedToken);
                $this->setSecureCookie("staff_remember_token", $token, 60 * 60 * 24 * self::REMEMBER_ME_EXPIRY_DAYS);
            } else {
                // Clear any existing tokens if "Remember Me" not checked
                $this->authModel->clearRememberToken($userDetails['staff_id']);
                $this->clearCookie("staff_remember_token");
            }

            $this->loadModules($userDetails["role_id"]);

            Logger::info('Login successful', ['staff_id' => $userDetails['staff_id']]);
            $this->jsonResponse(["message" => "Login successful!"]);
        } else {
            Logger::warning('Failed login attempt', ['staffid' => $staffid]);
            sleep(1); // Slow down brute force attempts
            $this->jsonResponse(["message" => "Invalid Staff ID or Password."], false);
        }
    }

    private function createSession($userDetails)
    {
        session_regenerate_id(true);

        $_SESSION['staff'] = [
            'staff_id' => $userDetails['staff_id'],
            'id' => $userDetails['id'],
            'role_name' => $userDetails['role_name'],
            'role_id' => $userDetails['role_id'],
            'profile_img' => $userDetails['profile_img'],
            'lname' => $userDetails['lname'],
            'fname' => $userDetails['fname'],
            'last_activity' => time(),
            'ip' => $_SERVER['REMOTE_ADDR'],
            'user_agent' => $_SERVER['HTTP_USER_AGENT']
        ];

        // Log session initialization with non-sensitive data only
        Logger::info("Session initialized for staff_id: " . $userDetails['staff_id']);
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
        Logger::info("Set secure cookie", ['cookie_name' => $name]);
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
        Logger::info("Cleared cookie", ['cookie_name' => $name]);
    }

    public function loadModules($role)
    {
        $userModules = AuthModel::getUserModules($role);
        if ($userModules) {
            $_SESSION["modules"] = $userModules;
            Logger::info("Loaded modules for role", ['role' => $role, 'modules_count' => count($userModules)]);
        } else {
            Logger::warning("No modules found for role", ['role' => $role]);
        }
    }

    public function validatedetails()
    {
        if (!$this->isPost()) {
            Logger::warning('Invalid request method for validatedetails', ['method' => $_SERVER['REQUEST_METHOD']]);
            $this->jsonResponse(["message" => "Invalid request"], false);
            return;
        }

        $nic = $this->getPost('nic');
        $email = $this->getPost('email');

        Logger::info('Validating registration details', ['nic' => $nic, 'email' => $email]);

        $result = AuthModel::validateRegDetails($nic, $email);

        if ($result === true) {
            $this->jsonResponse(["message" => "Success."]);
        } else {
            $this->jsonResponse(["message" => $result], false);
            Logger::warning('Registration validation failed', ['reason' => $result]);
        }
    }

    public function register()
    {
        if (!$this->isPost()) {
            Logger::warning('Invalid request method for register', ['method' => $_SERVER['REQUEST_METHOD']]);
            $this->jsonResponse(["message" => "Invalid request"], false);
            return;
        }

        $fname = $this->getPost('fname');
        $lname = $this->getPost('lname');
        $address = $this->getPost('address');
        $phone = $this->getPost('phone');
        $email = $this->getPost('email');
        $nic = $this->getPost('nic');
        $role = $this->getPost('role');
        $password = $this->getPost('password');
        $key = $this->getPost('key');

        Logger::info('Register attempt', [
            'fname' => $fname,
            'lname' => $lname,
            'email' => $email,
            'nic' => $nic,
            'role' => $role
        ]);

        $role_id = ($role === 'Librarian') ? 1 : 2;

        $result = AuthModel::register($fname, $lname, $address, $phone, $email, $nic, $role_id, $password, $key);

        if ($result) {
            Logger::info('Registration successful', ['email' => $email]);
            $this->jsonResponse(["message" => "Successfully Registered. Check the email for your staff ID"]);
        } else {
            Logger::warning('Registration failed', ['email' => $email]);
            $this->jsonResponse(["message" => "Invalid enrollment details. Please check your email, role, or enrollment key."], false);
        }
    }

    public function forgotPassword()
    {
        if (!$this->isPost()) {
            Logger::warning('Invalid request method for forgotPassword', ['method' => $_SERVER['REQUEST_METHOD']]);
            $this->jsonResponse(["message" => "Invalid request"], false);
            return;
        }

        $email = $this->getPost('email');

        Logger::info('Forgot password request', ['email' => $email]);

        $vcode = uniqid();

        $result = AuthModel::validateEmail($email, $vcode);

        if ($result) {
            Logger::info('Valid email found for password reset', ['email' => $email]);
            $this->sendResetLink($email, $vcode);
        } else {
            Logger::warning('Password reset requested for non-existing email', ['email' => $email]);
            $this->jsonResponse(["message" => "Account not found."], false);
        }
    }

    public function sendResetLink($email, $vcode)
    {
        $subject = "Reset Password";
        $specificMessage = '<p>We received a request to reset the password for your account. If you initiated this request, please click the button below to create a new password.</p>
                <div style="margin-bottom: 10px;">
                    <a href="http://localhost/LMS/public/staff/index.php?action=showresetpw&vcode=' . $vcode . '">Reset Password</a>
                 </div>';

        $emailTemplate = new EmailTemplate();
        $body = $emailTemplate->getEmailBody("Staff Member", $specificMessage);

        $emailService = new EmailService();
        $emailSent = $emailService->sendEmail($email, $subject, $body);

        if ($emailSent) {
            Logger::info('Password reset email sent', ['email' => $email]);
            $this->jsonResponse(["message" => "Password reset link sent! Check your email."]);
        } else {
            Logger::error('Failed to send password reset email', ['email' => $email]);
            $this->jsonResponse(["message" => "Failed to send email."], false);
        }
    }

    public function resetPassword()
    {
        if (!$this->isPost()) {
            Logger::warning('Invalid request method for resetPassword', ['method' => $_SERVER['REQUEST_METHOD']]);
            $this->jsonResponse(["message" => "Invalid request"], false);
            return;
        }

        $password = $this->getPost('password');
        $vcode = $this->getPost('vcode');

        Logger::info('Reset password attempt', ['vcode' => $vcode]);

        $result = AuthModel::changePassword($password, $vcode);

        if ($result) {
            Logger::info('Password reset successful', ['vcode' => $vcode]);
            $this->jsonResponse(["message" => "Password reset successfully"]);
        } else {
            Logger::warning('Password reset failed', ['vcode' => $vcode]);
            $this->jsonResponse(["message" => "Failed to reset password"], false);
        }
    }

    public function logout()
    {
        Logger::info('Logging out user', ['staff_id' => $_SESSION['staff']['staff_id'] ?? 'unknown']);

        session_start();
        session_unset();
        session_destroy();

        $this->clearCookie("staff_remember_token");
        $this->clearCookie("staffid");

        header("Location: index.php?action=login");
        exit();
    }
}
