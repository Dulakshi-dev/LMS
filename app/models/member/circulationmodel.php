<?php

require_once config::getdbPath();
require_once config::getControllerPath('staff', 'notificationController.php');

class CirculationModel
{

    public static function getBooksAboutToDue()
    {
        $today = date('Y-m-d');
        $threeDaysBefore = date('Y-m-d', strtotime('+3 days'));
        $oneDayBefore = date('Y-m-d', strtotime('+1 day'));
        $oneDayAfter  = date('Y-m-d', strtotime('-1 day'));
    
        $query = "SELECT i.*, m.email, m.fname, m.lname, b.title, b.book_id
                  FROM `borrow` i
                  JOIN `member` m ON i.borrow_member_id = m.id
                  JOIN `book` b ON i.borrow_book_id = b.book_id
                  WHERE (
                      i.due_date = '$threeDaysBefore' OR 
                      i.due_date = '$oneDayBefore' OR 
                      i.due_date = '$today' OR
                      i.due_date = '$oneDayAfter'
                  )";
    
        $results = Database::search($query);
    
        if ($results && $results->num_rows > 0) {
            while ($row = $results->fetch_assoc()) {
                $email = $row['email'];
                $name = ($row['fname'] && $row['lname']) ? $row['fname'] . ' ' . $row['lname'] : "Member";
                $title = $row['title'] ?? "your borrowed book";
                $dueDate = $row['due_date'];
                $bookid = $row['book_id'];
    
                self::sendDueBookReminder($email, $name, $title, $dueDate, $bookid);
            }
        } else {
            error_log("No due books found or query failed.\nQuery: $query");
        }
    }
    
    public static function sendDueBookReminder($email, $name, $title, $dueDate, $bookid)
    {

        require_once Config::getServicePath('emailService.php');
        $emailService = new EmailService();
        $emailTemplate = new EmailTemplate();
        $notificationController = new NotificationController();


        $subject = "Return Book Reminder";

        $specificMessage = "<h4>This is a reminder that <strong>$title ($bookid)</strong> is due on <strong>$dueDate</strong>.</h4>";

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
}
