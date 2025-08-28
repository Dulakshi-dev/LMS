<?php

require_once __DIR__ . '/../../../main.php';
require_once Config::getControllerPath('system', 'controller.php');
require_once Config::getMailPath('emailTemplate.php');
require_once Config::getServicePath('emailService.php');

class AuthController extends Controller
{
    private $authModel;
    private const REMEMBER_ME_EXPIRY_DAYS = 7; // "Remember Me" cookie will last 7 days

    public function __construct()
    {
        // load staff authentication model
        require_once Config::getModelPath('staff', 'authmodel.php');
        $this->authModel = new AuthModel();
        // check and handle auto login if cookie is present
        $this->handleAutoLogin();
    }

    private function handleAutoLogin()
    {
        // only try if session is not already active and cookie exists
        if (!isset($_SESSION['staff']) && isset($_COOKIE['staff_remember_token'])) {
            $token = $_COOKIE['staff_remember_token'];

            // log that system is checking auto login
            Logger::info('Attempting auto-login via remember me token', ['token_present' => true]);

            // get staff user details by token
            $userDetails = AuthModel::getUserByRememberToken($token);

            // verify token and log user in
            if ($userDetails && password_verify($token, $userDetails['staff_remember_token'])) {
                $this->createSession($userDetails);

                // rotate token for extra security
                $newToken = bin2hex(random_bytes(32));
                $hashedToken = password_hash($newToken, PASSWORD_BCRYPT);

                AuthModel::updateRememberToken($userDetails['staff_id'], $hashedToken);
                $this->setSecureCookie("staff_remember_token", $newToken, 60 * 60 * 24 * self::REMEMBER_ME_EXPIRY_DAYS);

                // load modules allowed for user role
                $this->loadModules($userDetails["role_id"]);

                Logger::info('Auto-login successful', ['staff_id' => $userDetails['staff_id']]);

                // redirect to dashboard after auto login success
                header("Location: index.php?action=dashboard");
                exit;
            } else {
                // if token is invalid, clear it
                Logger::warning('Invalid remember me token during auto-login', ['token' => $token]);
                $this->clearCookie("staff_remember_token");
            }
        }
    }

    public function login()
    {
        // only allow POST method for login
        if (!$this->isPost()) {
            Logger::warning('Invalid request method for login', ['method' => $_SERVER['REQUEST_METHOD']]);
            $this->jsonResponse(["message" => "Invalid request"], false);
            return;
        }

        // get posted login data
        $staffid = trim($this->getPost('staffid'));
        $password = $this->getPost('password');
        $rememberme = $this->getPost('rememberme', 0);

        Logger::info('Login attempt', ['staffid' => $staffid, 'remember_me' => (bool)$rememberme]);

        // check staff id and password
        $userDetails = $this->authModel->validateLogin($staffid, $password);

        if ($userDetails) {
            // create session on success
            $this->createSession($userDetails);

            if ($rememberme) {
                // generate remember me token and save it
                $token = bin2hex(random_bytes(32));
                $hashedToken = password_hash($token, PASSWORD_BCRYPT);

                $this->authModel->storeRememberToken($userDetails['staff_id'], $hashedToken);
                $this->setSecureCookie("staff_remember_token", $token, 60 * 60 * 24 * self::REMEMBER_ME_EXPIRY_DAYS);
            } else {
                // clear old token if remember me is not selected
                $this->authModel->clearRememberToken($userDetails['staff_id']);
                $this->clearCookie("staff_remember_token");
            }

            // load staff modules based on role
            $this->loadModules($userDetails["role_id"]);

            Logger::info('Login successful', ['staff_id' => $userDetails['staff_id']]);
            $this->jsonResponse(["message" => "Login successful!"]);
        } else {
            // invalid login attempt
            Logger::warning('Failed login attempt', ['staffid' => $staffid]);
            sleep(1); // delay to slow brute force attempts
            $this->jsonResponse(["message" => "Invalid Staff ID or Password."], false);
        }
    }

    private function createSession($userDetails)
    {
        // regenerate session id for security
        session_regenerate_id(true);

        // save staff details into session
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

        Logger::info("Session initialized for staff_id: " . $userDetails['staff_id']);
    }

    private function setSecureCookie($name, $value, $duration)
    {
        // set cookie with secure and httponly flags
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
        // remove cookie by setting expired time
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
        // get allowed modules based on user role
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
        // only allow POST method
        if (!$this->isPost()) {
            Logger::warning('Invalid request method for validatedetails', ['method' => $_SERVER['REQUEST_METHOD']]);
            $this->jsonResponse(["message" => "Invalid request"], false);
            return;
        }

        // get nic and email from user
        $nic = $this->getPost('nic');
        $email = $this->getPost('email');

        Logger::info('Validating registration details', ['nic' => $nic, 'email' => $email]);

        // check if nic or email already exists
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
        // only allow POST method
        if (!$this->isPost()) {
            Logger::warning('Invalid request method for register', ['method' => $_SERVER['REQUEST_METHOD']]);
            $this->jsonResponse(["message" => "Invalid request"], false);
            return;
        }

        // get new staff details from form
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

        // assign role id based on role name
        $role_id = ($role === 'Librarian') ? 1 : 2;

        // try to register new staff member
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
        // only allow POST method
        if (!$this->isPost()) {
            Logger::warning('Invalid request method for forgotPassword', ['method' => $_SERVER['REQUEST_METHOD']]);
            $this->jsonResponse(["message" => "Invalid request"], false);
            return;
        }

        $email = $this->getPost('email');

        Logger::info('Forgot password request', ['email' => $email]);

        // generate unique verification code
        $vcode = uniqid();

        // check if email exists and save vcode
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
        // email subject and body
        $subject = "Reset Password";
        $specificMessage = '<p>We received a request to reset the password for your account. If you initiated this request, please click the button below to create a new password.</p>
                <div style="margin-bottom: 10px;">
                    <a href="http://localhost/LMS/public/staff/index.php?action=showresetpw&vcode=' . $vcode . '">Reset Password</a>
                 </div>';

        // create email template with custom message
        $emailTemplate = new EmailTemplate();
        $body = $emailTemplate->getEmailBody("Staff Member", $specificMessage);

        // send email using email service
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
        // only allow POST method
        if (!$this->isPost()) {
            Logger::warning('Invalid request method for resetPassword', ['method' => $_SERVER['REQUEST_METHOD']]);
            $this->jsonResponse(["message" => "Invalid request"], false);
            return;
        }

        $password = $this->getPost('password');
        $vcode = $this->getPost('vcode');

        Logger::info('Reset password attempt', ['vcode' => $vcode]);

        // change password based on verification code
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

        // clear session and destroy it
        session_start();
        session_unset();
        session_destroy();

        // clear cookies
        $this->clearCookie("staff_remember_token");
        $this->clearCookie("staffid");

        // redirect to login page
        header("Location: index.php?action=login");
        exit();
    }
}
