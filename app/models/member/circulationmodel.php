<?php

require_once config::getdbPath();
require_once config::getControllerPath('staff', 'notificationController.php');
/**
 * CirculationModel
 * Handles operations related to circulation of books, including reminders for due books.
 */

class CirculationModel
{   /**
 * CirculationModel
 * Handles operations related to circulation of books, including reminders for due books.
 */

    public static function getBooksAboutToDue()
    {
        $today = date('Y-m-d');
        $threeDaysAhead = date('Y-m-d', strtotime('+3 days'));
        $oneDayAhead = date('Y-m-d', strtotime('+1 day'));
        $yesterday  = date('Y-m-d', strtotime('-1 day'));

        $query = "SELECT i.*, m.email, m.fname, m.lname, b.title, b.book_id
              FROM `borrow` i
              JOIN `member` m ON i.borrow_member_id = m.id
              JOIN `book` b ON i.borrow_book_id = b.book_id
              WHERE i.due_date IN (?, ?, ?, ?)";

        $params = [$threeDaysAhead, $oneDayAhead, $today, $yesterday];
        $types = "ssss";

        $results = Database::search($query, $params, $types);

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
            error_log("No due books found or query failed.");
        }
    }


        /**
     * sendDueBookReminder
     * Sends an email reminder and notification for a borrowed book about to be due.
     * @param string $email Member's email
     * @param string $name Member's name
     * @param string $title Book title
     * @param string $dueDate Due date of the book
     * @param string $bookid Book ID
     */
    public static function sendDueBookReminder($email, $name, $title, $dueDate, $bookid)
    {
        require_once Config::getServicePath('emailService.php');
        $emailService = new EmailService();
        $emailTemplate = new EmailTemplate();
        $notificationController = new NotificationController();

        // Escape all variables for safe HTML output
        $safeName = htmlspecialchars($name, ENT_QUOTES, 'UTF-8');
        $safeTitle = htmlspecialchars($title, ENT_QUOTES, 'UTF-8');
        $safeDueDate = htmlspecialchars($dueDate, ENT_QUOTES, 'UTF-8');
        $safeBookId = htmlspecialchars($bookid, ENT_QUOTES, 'UTF-8');

        $subject = "Return Book Reminder";

        $specificMessage = "<h4>This is a reminder that <strong>$safeTitle ($safeBookId)</strong> is due on <strong>$safeDueDate</strong>.</h4>";

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
}
