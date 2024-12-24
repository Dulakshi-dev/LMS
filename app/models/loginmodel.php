<?php

require_once config::getdbPath();



class LoginModel
{

    private $db;

    public function __construct()
    {
        $this->db = new Database();
    }

    public static function validateLogin($username, $password)
    {
        $query = "SELECT * FROM `user_details` WHERE `user_id` = '$username' AND `password` = '$password' ;";
        $result = Database::search($query);

        if ($result && $result->num_rows > 0) {
            $user = $result->fetch_assoc();
            return $user;
        }
        return false;
    }

    public static function getUserModules($role_id)
    {
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

    public static function generateStaffID()
    {
        // Query to get the latest staff_id
        $result = Database::search("SELECT user_id FROM `login` ORDER BY login_id DESC LIMIT 1");

        if ($result->num_rows > 0) {
            $row = $result->fetch_assoc();
            $lastStaffID = $row['user_id'];

            $number = (int)substr($lastStaffID, 2);
            $newNumber = $number + 1;

            $newStaffID = "S-" . str_pad($newNumber, 6, "0", STR_PAD_LEFT);
        } else {
            $newStaffID = "S-000001";
        }
        return $newStaffID;
    }

    public static function register($fname, $lname, $address, $phone, $email, $nic, $role, $password)
    {
        if ($role == "Librarian") {
            Database::insert("INSERT INTO `user`(`nic`,`fname`,`lname`,`mobile`,`address`,`email`,`status_id`,`role_id`) VALUES ('$nic','$fname','$lname','$phone','$address','$email','1','1')");
            return true;
            //handle security issues
        } else {
            $id = Database::insert("INSERT INTO `user`(`nic`,`fname`,`lname`,`mobile`,`address`,`email`,`status_id`,`role_id`) VALUES ('$nic','$fname','$lname','$phone','$address','$email','1','2')");
            $staffID = self::generateStaffID();

            if ($id) {
                Database::insert("INSERT INTO `login`(`user_id`, `password`, `userId`) VALUES ('$staffID', '$password', '$id')");
                self::sendMail($id, $staffID);
                return true;
            } else {
                return false;
            }
        }
    }

    public static function sendMail($id, $user_id)
    {
        $rs = Database::search("SELECT * FROM `user` WHERE `id` = '$id'");
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
            <h2>Your Staff ID is ' . $user_id . '</h2>
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

    public static function validateEmail($email,$vcode){
        $rs = Database::search("SELECT * FROM `user` WHERE `email` = '$email'");

        if($rs->num_rows > 0 ){
            $row = $rs->fetch_assoc();
            $id = $row["id"];
            Database::insert("UPDATE `user` SET `vcode` ='$vcode' WHERE `id`='$id'");
            return true;
        }else{
            return false;
        }
    }

    public static function changePassword($password, $vcode){
        $vcode =trim($vcode);

        $rs = Database::search("SELECT * FROM `user` WHERE `vcode` = '$vcode'");

        if($rs->num_rows > 0 ){

            $row = $rs->fetch_assoc();
            $id = $row["id"];

            Database::ud("UPDATE `login` SET `password` ='$password' WHERE `userId`='$id'");
            Database::ud("UPDATE `user` SET `vcode` = NULL WHERE `id`='$id'");

            return true;
        }else{
            return false;
        }

    }

}
