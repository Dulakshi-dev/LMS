<?php

require_once config::getdbPath();

class PaymentModel
{
    public static function registerPayment($transaction_id, $id, $fee)
    {
       
            Database::insert("INSERT INTO `payment` (`amount`, `transaction_id`, `payed_at`, `next_due_date`, `memberId`) 
VALUES ('$fee', '$transaction_id', NOW(), DATE_ADD(NOW(), INTERVAL 1 YEAR), '$id');");
            return true;
        
    }

    public static function renewPayment($transaction_id, $member_id, $fee)
    {
        $result = Database::search("SELECT `id` FROM `member` INNER JOIN `member_login` ON `member`.`id`=`member_login`.`memberId` WHERE `member_id` = '$member_id'");
        $row = $result->fetch_assoc();
        $id = $row['id'];

        
        if ($result->num_rows > 0) {
            $result2 = Database::search("SELECT `next_due_date` FROM `payment` WHERE `memberId` = '$id' ORDER BY `payed_at` DESC LIMIT 1");
            $row = $result2->fetch_assoc();
            $due_date = $row['next_due_date'];
            $next_due_date = "DATE_ADD('$due_date', INTERVAL 1 YEAR)";

            Database::insert("INSERT INTO `payment` (`amount`, `transaction_id`, `payed_at`, `next_due_date`, `memberId`) 
VALUES ('$fee', '$transaction_id', NOW(), $next_due_date, '$id');");


            return true;
        }else{
            return false;
        } 
    }

    public static function sendMembershipReminder()
    {
        $resultOneWeek = Database::search("SELECT member.email, payment.next_due_date
FROM member
JOIN payment ON member.id = payment.memberId
WHERE DATE(payment.next_due_date) = CURDATE() + INTERVAL 7 DAY 
   OR DATE(payment.next_due_date) = CURDATE() + INTERVAL 1 MONTH;");

        while ($row = $resultOneWeek->fetch_assoc()) {

            $email = $row['email'];
            $expirationDate = $row['next_due_date'];

            self::sendExpirationReminder($email, $expirationDate);
        }
    }

    public static function sendExpirationReminder($email, $expirationDate)
    {
        $rs = Database::search("SELECT `member_id`.`fname`,`lname` FROM `member` INNER JOIN `member_login` ON `member`.`id` = `member_login`.`memberId` WHERE `email` = '$email'");
        $row = $rs->fetch_assoc();

        require_once Config::getServicePath('emailService.php');

        $id = $row["member_id"];
        $name = $row["fname"] . " " . $row["lname"];
        $subject = 'Annual membership fee reminder';

        $body = '<h1 style="padding-top: 30px;">Shelf Loom</h1>
        <p style="font-size: 30px; color: black; font-weight: bold; text-align: center;">Welcome!</p> 
            <p>Dear ' . $name . ',</p>
        <div style="max-width: 600px; margin: 0 auto; padding: 20px; text-align: left;">
            <p>We are pleased to connect with you! Here’s some important information:</p>
            <h2>Your membership is set to expire on' . $expirationDate . '<br>Please make the payment soon to continue enjoying our services.</h2>
            <div style="margin-bottom: 10px;">
            
                <a href="http://localhost/LMS/public/member/index.php?action=renewmembership&id=' . $id . '">Click here to reset your password</a>
            </div><p>If you have any questions or issues, please reach out to us.</p>
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

    public static function checkOverduePayments()
    {
        $today = date('Y-m-d');

        $result = Database::search("SELECT `memberId` FROM `payment` JOIN `member` ON `payment`.`memberId` = `member`.`id` WHERE DATE(`payment`.`next_due_date`) < '$today';");
        
        while ($row = $result->fetch_assoc()) {
            $member_id = $row['memberId'];
            self::deactivateMembership($member_id);        }
    }

    private static function deactivateMembership($id)
    {
        Database::ud("UPDATE `member` SET `status_id` = '5' WHERE `id` = '$id'");
    }
}
