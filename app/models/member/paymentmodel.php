<?php

require_once config::getdbPath();

class PaymentModel
{
    public static function registerPayment($transaction_id, $id, $fee)
    {
        $query = "INSERT INTO `payment` (`amount`, `transaction_id`, `payed_at`, `next_due_date`, `memberId`) 
              VALUES (?, ?, NOW(), DATE_ADD(NOW(), INTERVAL 1 YEAR), ?)";

        $params = [$fee, $transaction_id, $id];
        $types = "dsi";

        Database::insert($query, $params, $types);
        return true;
    }


    public static function renewPayment($transaction_id, $member_id, $fee)
    {
        $query = "SELECT `id` FROM `member` INNER JOIN `member_login` ON `member`.`id`=`member_login`.`memberId` WHERE `member_id` = ?";
        $params = [$member_id];
        $types = "s";
        $result = Database::search($query, $params, $types);


        $row = $result->fetch_assoc();
        $id = $row['id'];

        if ($result->num_rows > 0) {
            $query = "SELECT `next_due_date` FROM `payment` WHERE `memberId` = ? ORDER BY `payed_at` DESC LIMIT 1";
            $params = [$id];
            $types = "i";
            $result2 = Database::search($query, $params, $types);

            $row = $result2->fetch_assoc();
            $due_date = $row['next_due_date'];
            $next_due_date = date('Y-m-d H:i:s', strtotime($due_date . ' +1 year'));

            $query = "INSERT INTO `payment` (`amount`, `transaction_id`, `payed_at`, `next_due_date`, `memberId`) 
              VALUES (?, ?, NOW(), ?, ?)";
            $params = [$fee, $transaction_id, $next_due_date, $id];
            $types = "dssi";
            Database::insert($query, $params, $types);

            return true;
        } else {
            return false;
        }
    }

    public static function getMembersToRenewMembership()
    {
        $query = "SELECT member.email, payment.next_due_date, member_login.member_id, fname, lname
        FROM member
        JOIN payment ON member.id = payment.memberId
        JOIN member_login ON member_login.memberId = member.id
        WHERE DATE(payment.next_due_date) = CURDATE() + INTERVAL 7 DAY 
        OR DATE(payment.next_due_date) = CURDATE() + INTERVAL 1 MONTH;";
        $resultOneWeek = Database::search($query);


        while ($row = $resultOneWeek->fetch_assoc()) {

            $email = $row['email'];
            $expirationDate = $row['next_due_date'];
            $name = $row['fname'] . ' ' . $row['lname'];
            $member_id = $row['member_id'];

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

        // Escape variables for safe HTML output
        $safeName = htmlspecialchars($name, ENT_QUOTES, 'UTF-8');
        $safeExpirationDate = htmlspecialchars($expirationDate, ENT_QUOTES, 'UTF-8');
        $safeMemberId = htmlspecialchars($member_id, ENT_QUOTES, 'UTF-8');

        $safeMemberIdUrl = urlencode($safeMemberId);

        $specificMessage = '<h4>Your membership is set to expire on ' . $safeExpirationDate . '<br>Please make the annual membership payment to continue enjoying our services.</h4>
            <p><a href="http://localhost/LMS/public/member/index.php?action=renewmembership&id=' .  $safeMemberIdUrl . '">Click here to renew the membership.</a></p>';

        $body = $emailTemplate->getEmailBody($safeName, $specificMessage);

        $emailSent = $emailService->sendEmail($email, $subject, $body);
        $notification = $notificationController->insertNotification($email, strip_tags($specificMessage));

        if (!$emailSent) {
            error_log("Failed to send email to: " . $email);
        }

        if (!$notification) {
            error_log("Failed to send notification to: $safeName");
        }
    }


    public static function checkOverduePayments()
    {
        $today = date('Y-m-d');

        $query = "SELECT `memberId`,`email`,`fname`,`lname` FROM `payment` JOIN `member` ON `payment`.`memberId` = `member`.`id` WHERE DATE(`payment`.`next_due_date`) < ?;";
        $params = [$today];
        $types = "s";
        $result = Database::search($query, $params, $types);

        while ($row = $result->fetch_assoc()) {
            $member_id = $row['memberId'];
            $email = $row['email'];
            $name = $row['fname'] . ' ' . $row['lname'];


            self::deactivateMembership($member_id);
            self::sendMembershipExpiredMail($email, $name, $member_id);
        }
    }

    private static function deactivateMembership($id)
    {

        $query = "UPDATE `member` SET `status_id` = '5' WHERE `id` = ?";
        $params = [$id];
        $types = "i";
        Database::ud($query, $params, $types);
    }

    private static function sendMembershipExpiredMail($email, $name, $id)
    {
        require_once Config::getServicePath('emailService.php');
        $emailService = new EmailService();
        $emailTemplate = new EmailTemplate();

        $subject = 'Membership Expired';

        // Escape variables for safe HTML output
        $safeName = htmlspecialchars($name, ENT_QUOTES, 'UTF-8');
        $safeMemberId = htmlspecialchars($id, ENT_QUOTES, 'UTF-8');

        $specificMessage = '<h4>Your membership expired today.<br>Please make the annual membership payment to continue enjoying our services.</h4>
            <p><a href="http://localhost/LMS/public/member/index.php?action=renewmembership&id=' . $safeMemberId . '">Click here to renew the membership.</a></p>';

        $body = $emailTemplate->getEmailBody($safeName, $specificMessage);

        $emailSent = $emailService->sendEmail($email, $subject, $body);

        if (!$emailSent) {
            error_log("Failed to send email to: " . $email);
        }
    }
}
