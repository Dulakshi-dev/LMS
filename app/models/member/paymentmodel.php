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

    public static function getMembersToRenewMembership()
    {
        $resultOneWeek = Database::search("SELECT member.email, payment.next_due_date, member_login.member_id, fname, lname
        FROM member
        JOIN payment ON member.id = payment.memberId
        JOIN member_login ON member_login.memberId = member.id
        WHERE DATE(payment.next_due_date) = CURDATE() + INTERVAL 7 DAY 
        OR DATE(payment.next_due_date) = CURDATE() + INTERVAL 1 MONTH;");

        while ($row = $resultOneWeek->fetch_assoc()) {

            $email = $row['email'];
            $expirationDate = $row['next_due_date'];
            $name = $row['fname'].' '.$row['lname'];
            $member_id = $row['email'];


            self::sendExpirationReminderEmail($email, $expirationDate, $name, $member_id);
        }
    }

    public static function sendExpirationReminderEmail($email, $expirationDate, $name, $member_id)
    {
        require_once Config::getServicePath('emailService.php');
        $emailService = new EmailService();
        $emailTemplate = new EmailTemplate();
        $notificationController = new NotificationController();

        $subject = 'Renew Your Membership';

        $specificMessage = '<h4>Your membership is set to expire on' . $expirationDate . '<br>Please make the annual membership payment to continue enjoying our services.</h4>
                <p><a href="http://localhost/LMS/public/member/index.php?action=renewmembership&id=' . $member_id . '">Click here to renew the membership.</a></p>';
    
        $body = $emailTemplate->getEmailBody($name, $specificMessage);

        $emailSent = $emailService->sendEmail($email, $subject, $body);
        $notification = $notificationController->insertNotification($email, strip_tags($specificMessage));
   
        if (!$emailSent) {
            error_log("Failed to send email to: " . $email);
        }

        if (!$notification) {
            error_log("Failed to send notification to: $name ");
        }
    }

    public static function checkOverduePayments()
    {
        $today = date('Y-m-d');

        $result = Database::search("SELECT `memberId`,`email`,`fname`,`lname` FROM `payment` JOIN `member` ON `payment`.`memberId` = `member`.`id` WHERE DATE(`payment`.`next_due_date`) < '$today';");
        
        while ($row = $result->fetch_assoc()) {
            $member_id = $row['memberId'];
            $email = $row['email'];
            $name = $row['fname'] . ' ' . $row['lname'];


            self::deactivateMembership($member_id);    
            self::sendMembershipExpiredMail($email,$name);        
        

        }
    }

    private static function deactivateMembership($id)
    {
        Database::ud("UPDATE `member` SET `status_id` = '5' WHERE `id` = '$id'");
    }

    private static function sendMembershipExpiredMail($email, $name)
    {
        require_once Config::getServicePath('emailService.php');
        $emailService = new EmailService();
        $emailTemplate = new EmailTemplate();

        $subject = 'Membership Expired';

        $specificMessage = '<h4>Your membership expired today.<br>Please make the annual membership payment to continue enjoying our services.</h4>
                <p><a href="http://localhost/LMS/public/member/index.php?action=renewmembership&id=' . $id . '">Click here to renew the membership.</a></p>';
    
        $body = $emailTemplate->getEmailBody($name, $specificMessage);

        $emailSent = $emailService->sendEmail($email, $subject, $body);

        if (!$emailSent) {
            error_log("Failed to send email to: " . $email);
        }
    }
}
