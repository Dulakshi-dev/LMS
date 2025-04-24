<?php

require_once config::getdbPath();

class AuthModel
{

    private $db;

    public function __construct()
    {
        $this->db = new Database();
    }


    public static function validateLogin($staffid, $password)
    {
        // Use prepared statements to prevent SQL injection
        $query = "SELECT * FROM `staff`
        JOIN `staff_login` ON `staff`.`id` = `staff_login`.`staffId`
        JOIN `role` ON `staff`.`role_id` = `role`.`role_id`
        WHERE `staff_id` = '$staffid'";

$result = Database::search($query);

        if ($result && $result->num_rows > 0) {
            $user = $result->fetch_assoc();

            if (password_verify($password, $user['password'])) {
                return $user;
            }
        }

        return false;
    }

    public static function getUserByRememberToken($token)
    {
        // Get all potential tokens (we'll verify with password_verify)
        $query = "SELECT * FROM `staff`
                  JOIN `staff_login` ON `staff`.`id` = `staff_login`.`staffId`
                  JOIN `role` ON `staff`.`role_id` = `role`.`role_id`
                  WHERE `remember_token` IS NOT NULL";
        
        $result = Database::search($query);

        if ($result && $result->num_rows > 0) {
            while ($user = $result->fetch_assoc()) {
                // Verify the token against the hashed version in DB
                if (password_verify($token, $user['remember_token'])) {
                    return $user;
                }
            }
        }

        return false;
    }

    public static function storeRememberToken($staffid, $hashedToken)
    {
        Database::ud("UPDATE `staff_login` SET `remember_token` = '$hashedToken' WHERE `staff_id` = '$staffid'");
    }

    public static function updateRememberToken($staffid, $hashedToken)
    {
        return self::storeRememberToken($staffid, $hashedToken);
    }

    public static function clearRememberToken($staffid)
    {
        Database::ud("UPDATE `staff_login` SET `remember_token` = NULL WHERE `staff_id` = '$staffid'");
    }





    public static function getUserModules($role_id)
    {
        // SQL query to fetch module names assigned to the given role
        $query = "SELECT `module_name`,`module_icon` FROM `module` 
        JOIN `role_has_module` ON `module`.`module_id` = `role_has_module`.`module_id` WHERE `role_id` = '$role_id'";
        $result = Database::search($query);

        if ($result && $result->num_rows > 0) {
            $modules = [];
            while ($row = $result->fetch_assoc()) {
                $modules[] = [
                    "name" => $row["module_name"],
                    "icon" => $row["module_icon"]
                ];
            }
            return $modules; // Return all module names as an array
        } else {
            return false;
        }
    }

    public static function generateStaffID()
    {
        // Query to get the latest staff_id
        $result = Database::search("SELECT staff_id FROM `staff_login` ORDER BY login_id DESC LIMIT 1");

        if ($result->num_rows > 0) {
            $row = $result->fetch_assoc();
            $lastStaffID = $row['staff_id'];

            $number = (int)substr($lastStaffID, 2);
            $newNumber = $number + 1;

            $newStaffID = "S-" . str_pad($newNumber, 6, "0", STR_PAD_LEFT);
        } else {
            $newStaffID = "S-000001";
        }
        return $newStaffID;
    }

    public static function validateRegDetails($nic, $email)
    {
        $nicCheck = Database::search("SELECT * FROM `staff` WHERE `nic` = '$nic'");
        $emailCheck = Database::search("SELECT * FROM `staff` WHERE `email` = '$email'");

        if ($nicCheck->num_rows > 0 && $emailCheck->num_rows > 0) {
            return "Both NIC and Email are already registered.";
        } elseif ($nicCheck->num_rows > 0) {
            return "NIC is already registered.";
        } elseif ($emailCheck->num_rows > 0) {
            return "Email is already registered.";
        } else {
            return true;  // No match for both NIC and Email
        }
    }

    public static function register($fname, $lname, $address, $phone, $email, $nic, $role_id, $password, $key)
    {
        $keyValidation = self::validateKey($email, $key, $role_id);

        if ($keyValidation) {
            $id = Database::insert("INSERT INTO `staff`(`nic`,`fname`,`lname`,`mobile`,`address`,`email`,`status_id`,`role_id`) 
 VALUES ('$nic','$fname','$lname','$phone','$address','$email','1','$role_id')");
            $staffID = self::generateStaffID();

            $hashedPassword = password_hash($password, PASSWORD_DEFAULT);

            Database::insert("INSERT INTO `staff_login`(`staff_id`, `password`, `staffId`) VALUES ('$staffID', '$hashedPassword', '$id')");
            self::sendMail($id, $staffID);

            return true;
        } else {
            return false;
        }
    }


    public static function validateKey($email, $key, $role_id)
    {

        $query = "SELECT * FROM staff_key WHERE email = '$email' AND key_value = '$key' AND role_id = '$role_id'";
        $result = Database::search($query);

        if ($result->num_rows > 0) {
            return true;
        } else {
            return false;
        }
    }



    public static function sendMail($id, $staff_id)
    {
        $rs = Database::search("SELECT * FROM `staff` WHERE `id` = '$id'");
        $row = $rs->fetch_assoc();

        require_once Config::getServicePath('emailService.php');
        $name = $row["fname"] . " " . $row["lname"];
        $email = $row["email"];
        $subject = 'Staff ID';

        $specificMessage = '<h2>Your Staff ID is ' . $staff_id . '</h2>';

        $emailTemplate = new EmailTemplate();
        $body = $emailTemplate->getEmailBody($name, $specificMessage);

        $emailService = new EmailService();
        $emailSent = $emailService->sendEmail($email, $subject, $body);
    
        
        if ($emailSent) {
            return true;
        } else {
            return false;
        }
    }

    public static function validateEmail($email, $vcode)
    {
        $rs = Database::search("SELECT * FROM `staff` WHERE `email` = '$email'");

        if ($rs->num_rows > 0) {
            $row = $rs->fetch_assoc();
            $id = $row["id"];
            Database::insert("UPDATE `staff` SET `vcode` ='$vcode' WHERE `id`='$id'");
            return true;
        } else {
            return false;
        }
    }

    public static function changePassword($password, $vcode)
    {
        $vcode = trim($vcode);
        $hashedPassword = password_hash($password, PASSWORD_DEFAULT);

        $rs = Database::search("SELECT * FROM `staff` WHERE `vcode` = '$vcode'");

        if ($rs->num_rows > 0) {

            $row = $rs->fetch_assoc();
            $id = $row["id"];

            Database::ud("UPDATE `staff_login` SET `password` ='$hashedPassword' WHERE `staffId`='$id'");
            Database::ud("UPDATE `staff` SET `vcode` = NULL WHERE `id`='$id'");

            return true;
        } else {
            return false;
        }
    }
}
