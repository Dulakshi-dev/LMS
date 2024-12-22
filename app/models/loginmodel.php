<?php
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

