<?php

require_once __DIR__ . '/../../../main.php';
require_once Config::getControllerPath('system', 'controller.php');

class AuthController extends Controller
{
    private $authModel;

    public function __construct()
    {
        require_once Config::getModelPath('staff', 'authmodel.php');
        $this->authModel = new AuthModel();
    }

    public function login()
    {
        if ($this->isPost()) {
            $staffid = $this->getPost('staffid');
            $password = $this->getPost('password');
            $rememberme = $this->getPost('rememberme', 0);

            $userDetails = AuthModel::validateLogin($staffid, $password);

            if ($userDetails) {
                session_regenerate_id(true);

                $_SESSION['staff'] = [
                    'staff_id' => $userDetails['staff_id'],
                    'id' => $userDetails['id'],
                    'role_name' => $userDetails['role_name'],
                    'role_id' => $userDetails['role_id'],
                    'profile_img' => $userDetails['profile_img'],
                    'lname' => $userDetails['lname'],
                    'fname' => $userDetails['fname'],
                    'last_activity' => time()
                ];

                $this->loadModules($userDetails["role_id"]);

                if ($rememberme) {
                    setcookie("staffid", $staffid, time() + (60 * 60 * 24 * 365), "/");
                    setcookie("staffpw", $password, time() + (60 * 60 * 24 * 365), "/");
                } else {
                    setcookie("staffid", "", time() - 3600, "/");
                    setcookie("staffpw", "", time() - 3600, "/");
                }

                $this->jsonResponse(["message" => "Login successful!"]);
            } else {
                $this->jsonResponse(["message" => "Invalid username or password."], false);
            }
        }
    }

    public function loadModules($role)
    {
        $userModules = AuthModel::getUserModules($role);
        if ($userModules) {
            $_SESSION["modules"] = $userModules;
        }
    }

    public function validatedetails()
    {
        if ($this->isPost()) {
            $nic = $this->getPost('nic');
            $email = $this->getPost('email');

            $result = AuthModel::validateRegDetails($nic, $email);

            if ($result === true) {
                $this->jsonResponse(["message" => "Success."]);
            } else {
                $this->jsonResponse(["message" => $result], false);
            }
        }
    }


    public function register()
    {
        if ($this->isPost()) {
            $fname = $this->getPost('fname');
            $lname = $this->getPost('lname');
            $address = $this->getPost('address');
            $phone = $this->getPost('phone');
            $email = $this->getPost('email');
            $nic = $this->getPost('nic');
            $role = $this->getPost('role');
            $password = $this->getPost('password');
            $key = $this->getPost('key');

            if ($role === 'Librarian') {
                $role_id = 1;
            } else {
                $role_id = 2;
            }


            $result = AuthModel::register($fname, $lname, $address, $phone, $email, $nic, $role_id, $password, $key);

            if ($result) {
                $this->jsonResponse(["message" => "Successfully Registered. Check the email for your staff ID"]);
            } else {
                $this->jsonResponse(["message" => "Invalid enrollment details. Please check your email, role, or enrollment key."], false);
            }
        }
    }

    public function forgotPassword()
    {
        if ($this->isPost()) {
            $email = $this->getPost('email');
            $vcode = uniqid();

            $result = AuthModel::validateEmail($email, $vcode);

            if ($result) {
                $this->sendResetLink($email, $vcode);
            } else {
                $this->jsonResponse(["message" => "Account not found."], false);
            }
        }
    }

    public function sendResetLink($email, $vcode)

    {
        require_once Config::getServicePath('emailService.php');

        $subject = "Reset Password";
        $body = '
           <h4 style="font-size: 30px; color: black; font-weight: bold; text-align: center;">Reset Your password</h4> 

            <div style="max-width: 600px; margin: 0 auto; padding: 20px; text-align: left;">
                <p>Dear Staff Member,</p>
                <p>We received a request to reset the password for your account. If you initiated this request, please click the button below to create a new password.</p>
                <div style="margin-bottom: 10px;">
                    <a href="http://localhost/LMS/public/staff/index.php?action=showresetpw&vcode=' . $vcode . '">Reset Password</a>
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
            $this->jsonResponse(["message" => "Password reset link sent! Check your email."]);
        } else {
            $this->jsonResponse(["message" => "Failed to send email."], false);
        }
    }

    public function resetPassword()
    {
        if ($this->isPost()) {
            $password = $this->getPost('password');
            $vcode = $this->getPost('vcode');

            $result = AuthModel::changePassword($password, $vcode);
            if ($result) {
                $this->jsonResponse(["message" => "Password reset successfully"]);
            } else {
                $this->jsonResponse(["message" => "Failed to reset password"], false);
            }
        }
    }

    public function logout()
    {
        session_start();
        session_unset();
        session_destroy();

        // Clear "Remember Me" cookies
        if (isset($_COOKIE['staffid'])) {
            setcookie("staffid", "", time() - 3600, "/");
            unset($_COOKIE['staffid']);
        }

        if (isset($_COOKIE['staffpw'])) {
            setcookie("staffpw", "", time() - 3600, "/");
            unset($_COOKIE['staffpw']);
        }

        header("Location: index.php?action=login");
        exit();
    }
}
