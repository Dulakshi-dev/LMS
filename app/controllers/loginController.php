<?php
session_start();
require_once __DIR__ . '/../../main.php'; 

class LoginController {

    private $loginModel;

    public function __construct() {
        require_once Config::getModelPath('loginmodel.php');
        $this->loginModel = new LoginModel();
    }

    public function showLogin(){
        require_once Config::getViewPath("staff",'login.php');

    }

    public function login() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $username = $_POST['username'];
            $password = $_POST['password'];

            $userDetails = LoginModel::validateLogin($username, $password); 

            if ($userDetails) {
                $_SESSION["user"] = $userDetails;
                $this->loadModules($_SESSION['user']['role_id']);
                require_once Config::getViewPath("staff",'dashboard.php');

            } else {

                $error = "Invalid username or password.";
                require_once Config::getViewPath("staff", 'login.php');

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

    public function showDashboard(){
        require_once Config::getViewPath("staff",'dashboard.php');

    }
}
?>