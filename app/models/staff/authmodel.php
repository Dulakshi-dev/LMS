<?php

require_once config::getdbPath();

/**
 * AuthModel
 * Handles staff authentication, registration, password management, and role/module access.
 */
class AuthModel
{

    private $db;

    public function __construct()
    {
        $this->db = new Database();
    }
 
    
    /**
     * validateLogin
     * Validates staff login credentials.
     */

    public static function validateLogin($staffid, $password)
    {
        $query = "SELECT * FROM `staff`
        JOIN `staff_login` ON `staff`.`id` = `staff_login`.`staffId`
        JOIN `role` ON `staff`.`role_id` = `role`.`role_id`
        WHERE `staff_id` = ?";
        $params = [$staffid];
        $types = "s";
        $result = Database::search($query, $params, $types);

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
        $query = "UPDATE `staff_login` SET `remember_token` = ? WHERE `staff_id` = ?";
        $params = [$hashedToken, $staffid];
        $types = "ss";
        Database::ud($query, $params, $types);
    }

    public static function updateRememberToken($staffid, $hashedToken)
    {
        return self::storeRememberToken($staffid, $hashedToken);
    }

    public static function clearRememberToken($staffid)
    {
        $query = "UPDATE `staff_login` SET `remember_token` = NULL WHERE `staff_id` = ?";
        $params = [$staffid];
        $types = "s";
        Database::ud($query, $params, $types);
    }

    public static function getUserModules($role_id)
    {
        // SQL query to fetch module names assigned to the given role

        $query = "SELECT `module_name`,`module_icon` FROM `module` 
        JOIN `role_has_module` ON `module`.`module_id` = `role_has_module`.`module_id` WHERE `role_id` = ?";
        $params = [$role_id];
        $types = "i";
        $result = Database::search($query, $params, $types);

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
        $query = "SELECT staff_id FROM `staff_login` ORDER BY login_id DESC LIMIT 1";
        $result = Database::search($query);

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
        $query = "SELECT * FROM `staff` WHERE `nic` = ?";
        $params = [$nic];
        $types = "s";
        $nicCheck = Database::search($query, $params, $types);

        $query = "SELECT * FROM `staff` WHERE `email` = ?";
        $params = [$email];
        $types = "s";
        $emailCheck = Database::search($query, $params, $types);

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

       /**
     * register
     * Registers a new staff member after validating key.
     */
    public static function register($fname, $lname, $address, $phone, $email, $nic, $role_id, $password, $key)
    {
        $keyValidation = self::validateKey($email, $key, $role_id);

        if ($keyValidation) {

            $query = "INSERT INTO `staff`(`nic`,`fname`,`lname`,`mobile`,`address`,`email`,`status_id`,`role_id`) 
 VALUES (?,?,?,?,?,?,'1',?)";
            $params = [$nic, $fname, $lname, $phone, $address, $email, $role_id];
            $types = "ssssssi";
            $id = Database::insert($query, $params, $types);

            $staffID = self::generateStaffID();

            $hashedPassword = password_hash($password, PASSWORD_DEFAULT);

            $query = "INSERT INTO `staff_login`(`staff_id`, `password`, `staffId`) VALUES (?, ?, ?)";
            $params = [$staffID, $hashedPassword, $id];
            $types = "ssi";
            Database::insert($query, $params, $types);
            self::sendMail($id, $staffID);

            return true;
        } else {
            return false;
        }
    }


    public static function validateKey($email, $key, $role_id)
    {

        $query = "SELECT * FROM staff_key WHERE email = ? AND key_value = ? AND role_id = ?";
        $params = [$email, $key, $role_id];
        $types = "ssi";
        $result = Database::search($query, $params, $types);

        if ($result->num_rows > 0) {
            return true;
        } else {
            return false;
        }
    }


    public static function sendMail($id, $staff_id)
    {
        // Secure query with placeholders
        $query = "SELECT * FROM `staff` WHERE `id` = ?";
        $params = [$id];
        $types = "i";

        $rs = Database::search($query, $params, $types);

        $row = $rs->fetch_assoc();

        require_once Config::getServicePath('emailService.php');

        // Escape dynamic content
        $name = htmlspecialchars($row["fname"] . " " . $row["lname"], ENT_QUOTES, 'UTF-8');
        $email = filter_var($row["email"], FILTER_SANITIZE_EMAIL);
        $staff_id = htmlspecialchars($staff_id, ENT_QUOTES, 'UTF-8');

        $subject = 'Staff ID';
        $specificMessage = '<h2>Your Staff ID is ' . $staff_id . '</h2>';

        $emailTemplate = new EmailTemplate();
        $body = $emailTemplate->getEmailBody($name, $specificMessage);

        $emailService = new EmailService();
        $emailSent = $emailService->sendEmail($email, $subject, $body);

        return $emailSent ? true : false;
    }


    public static function validateEmail($email, $vcode)
    {
        $query = "SELECT * FROM `staff` WHERE `email` = ?";
        $params = [$email];
        $types = "s";
        $rs = Database::search($query, $params, $types);

        if ($rs->num_rows > 0) {
            $row = $rs->fetch_assoc();
            $id = $row["id"];

            $query = "UPDATE `staff` SET `vcode` =? WHERE `id`=?";
            $params = [$vcode, $id];
            $types = "si";
            Database::ud($query, $params, $types);

            return true;
        } else {
            return false;
        }
    }

    public static function changePassword($password, $vcode)
    {
        $vcode = trim($vcode);
        $hashedPassword = password_hash($password, PASSWORD_DEFAULT);

        $query = "SELECT * FROM `staff` WHERE `vcode` = ?";
        $params = [$vcode];
        $types = "s";
        $rs = Database::search($query, $params, $types);

        if ($rs->num_rows > 0) {

            $row = $rs->fetch_assoc();
            $id = $row["id"];

            $query = "UPDATE `staff_login` SET `password` =? WHERE `staffId`=?";
            $params = [$hashedPassword, $id];
            $types = "si";
            Database::ud($query, $params, $types);

            $query = "UPDATE `staff` SET `vcode` = NULL WHERE `id`=?";
            $params = [$id];
            $types = "i";
            Database::ud($query, $params, $types);

            return true;
        } else {
            return false;
        }
    }
}
