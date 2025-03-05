<?php

require_once config::getdbPath();



class AuthModel
{

    private $db;

    public function __construct()
    {
        $this->db = new Database();
    }

    public static function validateLogin($memid, $password)
    {
        $query = "SELECT * FROM `member`JOIN `member_login` ON `member`.`id` = `member_login`.`memberId` WHERE `member_id` = '$memid' AND `password` = '$password' ;";
        $result = Database::search($query);

        if ($result && $result->num_rows > 0) {
            $user = $result->fetch_assoc();
            return $user;
        }
        return false;
    }

    public static function generateMemberID()
    {
        // Query to get the latest member_id
        $result = Database::search("SELECT member_id FROM `member_login` ORDER BY member_login_id DESC LIMIT 1");

        if ($result->num_rows > 0) {
            $row = $result->fetch_assoc();
            $lastMemberID = $row['member_id'];

            $number = (int)substr($lastMemberID, 2);
            $newNumber = $number + 1;

            $newMemberID = "M-" . str_pad($newNumber, 6, "0", STR_PAD_LEFT);
        } else {
            $newMemberID = "M-000001";
        }
        return $newMemberID;
    }

    public static function registerMember($nic,  $address, $phone, $email, $fname, $lname, $password)
    {

        $id = Database::insert("INSERT INTO `member`(`nic`,`fname`,`lname`,`mobile`,`address`,`email`,`status_id`) VALUES ('$nic','$fname','$lname','$phone','$address','$email','1')");
        $memberID = self::generateMemberID();

        if ($id) {
            Database::insert("INSERT INTO `member_login`(`member_id`, `password`, `memberId`) VALUES ('$memberID', '$password', '$id')");
            self::sendMail($id, $memberID);
            return true;
        } else {
            return false;
        }
    }


    public static function sendMail($id, $user_id)
    {
        $rs = Database::search("SELECT * FROM `member` WHERE `id` = '$id'");
        $row = $rs->fetch_assoc();

        require_once Config::getServicePath('emailService.php');

        $name = $row["fname"] . " " . $row["lname"];
        $email = $row["email"];
        $subject = 'Member ID';

        $body = '<h1 style="padding-top: 30px;">Shelf Loom</h1>
        <p style="font-size: 30px; color: black; font-weight: bold; text-align: center;">Welcome!</p> 
            <p>Dear ' . $name . ',</p>
        <div style="max-width: 600px; margin: 0 auto; padding: 20px; text-align: left;">
            <p>We are pleased to connect with you! Here’s some important information:</p>
            <h2>Your Member ID is ' . $user_id . '</h2>
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
}
