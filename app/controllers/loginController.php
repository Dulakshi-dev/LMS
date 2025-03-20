<?php
session_start();
require_once __DIR__ . '/../../main.php';

class LoginController
{

    private $loginModel;

    public function __construct()
    {
        require_once Config::getModelPath('loginmodel.php');
        $this->loginModel = new LoginModel();
    }

    public static function login()
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            // Retrieve login details from POST request
            $staffid = $_POST['staffid'];
            $password = $_POST['password'];
            $rememberme = isset($_POST['rememberme']) ? $_POST['rememberme'] : 0;

            // Validate login credentials using the LoginModel
            $userDetails = LoginModel::validateLogin($staffid, $password);

            if ($userDetails) {
                // Store user details in the session
                $_SESSION['staff'] = [
                    'staff_id' => $userDetails['user_id'],
                    'id' => $userDetails['id'],
                    'role_name' => $userDetails['role_name'],
                    'role_id' => $userDetails['role_id'],
                    'profile_img' => $userDetails['profile_img'],
                    'lname' => $userDetails['lname'],
                    'fname' => $userDetails['fname']
                ];

                // Load role-specific modules
                self::loadModules($_SESSION["staff"]["role_id"]);

                // Handle the "Remember Me" feature using cookies
                if ($rememberme) {
                    setcookie("staffid", $staffid, time() + (60 * 60 * 24 * 365), "/");
                    setcookie("staffpw", $password, time() + (60 * 60 * 24 * 365), "/");
                } else {
                    setcookie("staffid", "", time() - 3600, "/"); // Delete cookie
                    setcookie("staffpw", "", time() - 3600, "/"); // Delete cookie
                }
                
                echo json_encode(["success" => true, "message" => "Login successful!"]);
                exit;
            } else {
                echo json_encode(["success" => false, "message" => "Invalid username or password."]);
                exit;
            }
        }
    }


    public static function loadModules($role)
    {    
        // Retrieve user-specific modules from the model
        $userModules = LoginModel::getUserModules($role);

        if ($userModules) {
            // Store the modules in the session
            $_SESSION["modules"] = $userModules;
        } else {
            echo "No modules found for this role.";
        }
    }

    public static function register()
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $fname = $_POST['firstName'];
            $lname = $_POST['lastName'];
            $address = $_POST['address'];
            $phone = $_POST['phone'];
            $email = $_POST['email'];
            $nic = $_POST['nic'];
            $role = $_POST['role'];
            $password = $_POST['password'];

            $result = LoginModel::register($fname, $lname, $address, $phone, $email, $nic, $role, $password);

            if ($result) {
                echo json_encode(["success" => true, "message" => "Successfully Registered."]);

                header("Location: index.php");
                exit();
            } else {
                echo json_encode(["success" => false, "message" => "Registration Failed."]);
            }
        }
    }

    public static function forgotPassword()
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $email = $_POST['email'];
            $vcode = uniqid();

            $result = LoginModel::validateEmail($email, $vcode);

            if ($result) {
                self::sendResetLink($email, $vcode);
                exit();
            } else {

                echo json_encode(["success" => false, "message" => "Account not found."]);
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
                        <a href="http://localhost/LMS/public/staff/index.php?action=showresetpw&vcode=' . $vcode . '">Click here to reset your password</a>
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
                echo json_encode(["success" => true, "message" => "Password reset link sent! Check Your email address"]);
            } else {
                echo json_encode(["success" => false, "message" => "Failed to send email."]);
            }
        } else {
            echo json_encode(["success" => false, "message" => "Invalid Request."]);
        }
    }

    public static function resetPassword()
    {

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $password = $_POST['password'];
            $vcode = $_POST['vcode'];

            $result = LoginModel::changePassword($password, $vcode);
            if ($result) {
                echo json_encode(["success" => true, "message" => "Password reset successfully"]);
            } else {
                echo json_encode(["Error" => false, "message" => "Failed to reset password"]);
            }
        }
    }
}
