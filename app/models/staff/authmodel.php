<?php

require_once config::getdbPath();



class AuthModel
{

    private $db;

    public function __construct()
    {
        $this->db = new Database();
    }

    public static function validateLogin($username, $password)
    {
        // SQL query to retrieve user details by matching user ID and password
        $query = "SELECT * FROM `staff`
                JOIN `staff_login` ON `staff`.`id` = `staff_login`.`staffId`
                JOIN `role` ON `staff`.`role_id` = `role`.`role_id` WHERE `staff_id` = '$username' AND `password` = '$password' ;";
        $result = Database::search($query);

        // Check if a user record is found
        if ($result && $result->num_rows > 0) {
            $user = $result->fetch_assoc();
            return $user;
        }
        return false;
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
        $nicCheck = Database::search("SELECT * FROM `member` WHERE `nic` = '$nic'");
        $emailCheck = Database::search("SELECT * FROM `member` WHERE `email` = '$email'");

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

            Database::insert("INSERT INTO `staff_login`(`staff_id`, `password`, `staffId`) VALUES ('$staffID', '$password', '$id')");
            self::sendMail($id, $staffID);

            return true;
        }else{
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

        $body = '<h1 style="padding-top: 30px;">Shelf Loom</h1>
        <p style="font-size: 30px; color: black; font-weight: bold; text-align: center;">Welcome!</p> 
            <p>Dear ' . $name . ',</p>
        <div style="max-width: 600px; margin: 0 auto; padding: 20px; text-align: left;">
            <p>We are pleased to connect with you! Here’s some important information:</p>
            <h2>Your Staff ID is ' . $staff_id . '</h2>
            <p>If you have any questions or issues, please reach out to us.</p>
            <p>Call:[tel_num]</p>

            <div style="margin-top: 20px;">
                <p>Best regards,</p>
                <p>Shelf Loom Team</p>
            </div>
        </div>';

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

        $rs = Database::search("SELECT * FROM `staff` WHERE `vcode` = '$vcode'");

        if ($rs->num_rows > 0) {

            $row = $rs->fetch_assoc();
            $id = $row["id"];

            Database::ud("UPDATE `staff_login` SET `password` ='$password' WHERE `staffId`='$id'");
            Database::ud("UPDATE `staff` SET `vcode` = NULL WHERE `id`='$id'");

            return true;
        } else {
            return false;
        }
    }
}
