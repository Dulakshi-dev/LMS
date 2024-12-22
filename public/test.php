view-><?php
require_once "../main.php";
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Shelf Loom || Staff Login</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            background-image: url('<?php echo Config::getImagePath("stafflog.jpg"); ?>');
            background-size: cover;
            background-position: center;
            background-repeat: no-repeat;
            background-attachment: fixed;
            overflow-x: hidden;
        }

        .login-form {
            background: rgba(0, 0, 0, 0.8);
            padding: 30px;
            border-radius: 10px;
        }
</style>

    </style>
</head>

<body class="x">
    <?php
    include "header.php";
    ?>


    <div class="container-fluid login-container">
        <h1 class="text-dark text-center m-4">Hi! Welcome Back</h1>
        <div class="row p-3 justify-content-center align-items-center">
            <div class="col-lg-4 col-md-6 text-white login-form">
                <h1 class="text-center"> Staff Login</h1>
                    
                        <label for="username">Username:</label><br>
                        <input class="form-control mt-2" type="text" name="username" id="username" placeholder="Enter Staff ID"><br><br>

                        <label for="password">Password:</label><br>
                        <input class="form-control mt-2" type="password" name="password" id="password" placeholder="Enter Password"><br><br>

                        <div class="mt-1 bg-danger-subtle p-1 rounded-3" id="errormsgdiv">
                            <p id="errormsg" class="text-danger text-center mt-1"></p>
                        </div>

                        <div class="row">
                            <div class="text-end ">
                                <a href="forgot-password.php" class="text-decoration-none">Forgot Password?</a>
                            </div>
                        </div>

                        <button class="btn btn-primary w-100" onclick="staffLogin();" >Login</button>
                   
            </div>
        </div>
    </div>

    <?php
    include "footer-noscroll.php";
    ?>

<script src="<?php echo Config::getJsPath("login.js"); ?>"></script>
</body>

</html>

js-> function staffLogin() {
    var username = document.getElementById('username').value.trim();
    var password = document.getElementById('password').value.trim();
    var errorMsgDiv = document.getElementById('errormsgdiv');
    var errorMsg = document.getElementById('errormsg');
    if (username === '') {
        errorMsg.innerText = 'Username is required.';
    } else if (password === '') {
        errorMsg.innerText = 'Password is required.';
    } else {
        var formData = new FormData();
        formData.append("username", username);
        formData.append("password", password);
        fetch("index.php", {
            method: "POST",
            body: formData,
        })
        .then(response => response.json())  
        .then(resp => {
            if (resp.success) {
                window.location = "dashboard.php"; 
            } else {
                errorMsg.innerText = resp.message; 
            }
        })
        .catch(error => {
            console.error('Error:', error);
            errorMsg.innerText = "An error occurred while processing your request.";
        });
    }
}

index.php-> 
<?php
require_once "../main.php";
require_once Config::getControllerPath("logincontroller.php");

$loginController = new LoginController();
$loginController->login();  


?>


controller-> <?php
session_start();
require_once __DIR__ . '/../../main.php'; 

class LoginController {

    private $loginModel;

    public function __construct() {
        require_once Config::getModelPath('loginmodel.php');
        $this->loginModel = new LoginModel();
    }

    public function login() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $username = $_POST['username'];
            $password = $_POST['password'];

            $userDetails = LoginModel::validateLogin($username, $password); 

            if ($userDetails) {
                $_SESSION["user"] = $userDetails;
                $this->loadModules($_SESSION['user']['role_id']);

                echo json_encode(['success' => true, 'message' => 'Login successful']);
            } else {
                echo json_encode(['success' => false, 'message' => 'Invalid username or password']);
            }
        }
    }

    public function loadModules($role) {
        $userModules = LoginModel::getUserModules($role);
    
        if ($userModules) {
            
            $_SESSION["modules"] = $userModules;
        } else {
            echo "No modules found for this role.";
        }
    
        
    }
}
?>


model.php-> <?php
require_once config::getdbPath(); 

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception;

require_once __DIR__ . "/../../mail/PHPMailer.php"; 
require_once __DIR__ . "/../../mail/SMTP.php"; 
require_once __DIR__ . "/../../mail/Exception.php";


class LoginModel {

    private $db;
    
    public function __construct()
    {
        $this->db = new Database();
    }

    public static function validateLogin($username, $password) {
        $query = "SELECT * FROM `user_details` WHERE `user_id` = '$username' AND `password` = '$password' ;";
        $result = Database::search($query);
    
        if ($result && $result->num_rows > 0) {
            $user = $result->fetch_assoc();            
                return $user; 
            
        }
        return false;  
 
    }
    
    public static function getUserModules($role_id) {
        $query = "SELECT `module_name` 
                  FROM `module` 
                  JOIN `role_has_module` ON `module`.`module_id` = `role_has_module`.`module_id` 
                  WHERE `role_id` = '$role_id'";
        $result = Database::search($query);
    
        if ($result && $result->num_rows > 0) {
            $modules = [];
            while ($row = $result->fetch_assoc()) {
                $modules[] = $row["module_name"];
            }
            return $modules; // Return all module names as an array
        } else {
            return false;
        }
    }

    public static function getUserByEmail($email) {
        $result = Database::search("SELECT * FROM `user` WHERE `email` = ?", [$email]);
        return $result->fetch_assoc();
    }

    // Update verification code for the user
        public static function updateVerificationCode($userId, $vcode) {
            try {
                Database::iud("UPDATE `user` SET `vcode` = ? WHERE `id` = ?", [$vcode, $userId]);
            } catch (Exception $e) {
                error_log("Error updating verification code: " . $e->getMessage());
            }
        }
            

    // Fetch user by verification code
    public static function getUserByVerificationCode($vcode) {
        $result = Database::search("SELECT * FROM `user` WHERE `vcode` = ?", [$vcode]);
        return $result->fetch_assoc();
    }

    // Update user password
    public static function updatePassword($userId, $password) {
        Database::iud("UPDATE `login` SET `password` = ? WHERE `userId` = ?", [$password, $userId]);
    }

    // Clear verification code after successful reset
    public static function clearVerificationCode($userId) {
        Database::iud("UPDATE `user` SET `vcode` = NULL WHERE `id` = ?", [$userId]);
    }

}
?>

