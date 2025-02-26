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

}