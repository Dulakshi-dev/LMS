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
            $username = $_POST['username'];
            $password = $_POST['password'];
            $rememberme = isset($_POST['rememberme']) ? true : false;
    
            $userDetails = LoginModel::validateLogin($username, $password);
    
            if ($userDetails) {

                $_SESSION['staff'] = [
                    'staff_id' => $userDetails['user_id'],
                    'id' => $userDetails['id'],
                    'role_name' => $userDetails['role_name'],
                    'role_id' => $userDetails['role_id'],
                    'profile_img' => $userDetails['profile_img'],
                    'lname' => $userDetails['lname'],
                    'fname' => $userDetails['fname']
                ];

                self::loadModules($_SESSION["staff"]["role_id"]);
    
                if ($rememberme) {
                    setcookie("username", $username, time() + (60 * 60 * 24 * 365), "/");
                    setcookie("password", $password, time() + (60 * 60 * 24 * 365), "/");
                } else {
                    setcookie("username", "", time() - 3600, "/");
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
    

    public static function loadModules($role)
    {
        $userModules = LoginModel::getUserModules($role);

        if ($userModules) {

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
                header("Location: index.php");
                exit();
            } else {
                $error = "Unsuccessful Registration";
            }
        }
    }

    public static function forgotPassword()
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $email = $_POST['email'];
            $vcode = uniqid(); 

            $result = LoginModel::validateEmail($email ,$vcode);

            if ($result) {
                self::sendResetLink($email ,$vcode);
                exit();
            } else {

                $error = "Invalid Email";
                exit(); 
            }
        }
    }

    public static function sendResetLink($email ,$vcode)
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
                        <a href="http://localhost/LMS/public/staff/index.php?action=showresetpw&vcode='.$vcode.'">Click here to reset your password</a>
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
            echo("Invalid Request");
        }
    }

    public static function resetPassword()
    {
    
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $password = $_POST['password'];
            $vcode = $_POST['vcode'];
         
            $result = LoginModel::changePassword($password ,$vcode);
            if ($result) {
                header("Location: index.php");
            } else {
                echo("error");
            }
        }
    }
}
