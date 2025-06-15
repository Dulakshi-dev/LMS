<?php
require_once __DIR__ . '/../../../main.php';

class CirculationController extends Controller
{
    private $circulationModel;

    public function __construct()
    {
        require_once Config::getModelPath('staff', 'circulationmodel.php');
        $this->circulationModel = new CirculationModel();
    }

    public function getAllBorrowBooks()
    {
        $resultsPerPage = 10;

        if ($this->isPost()) {
            $page = (int)$this->getPost('page', 1);
            $bookid = $this->getPost('bookid');
            $memberid = $this->getPost('memberid');
            $status = $this->getPost('status', 'status1'); // default to "All"

            if (!empty($bookid) || !empty($memberid) || $status !== 'status1') {
                $bookData = CirculationModel::searchBorrowData($bookid, $memberid, $status, $page, $resultsPerPage);
              
            } else {
                $bookData = CirculationModel::getAllBorrowData($page, $resultsPerPage);
               
            }

            $issuebooks = $bookData['results'] ?? [];
            $total = $bookData['total'] ?? 0;
            $totalPages = ceil($total / $resultsPerPage);

            $this->jsonResponse([
                'issuebooks' => $issuebooks,
                'total' => $total,
                'totalPages' => $totalPages,
                'currentPage' => $page
            ]);
        } else {
            Logger::warning("Invalid request method in getAllBorrowBooks", ['method' => $_SERVER['REQUEST_METHOD']]);
            $this->jsonResponse(["message" => "Invalid request."], false);
        }
    }

    public function loadBookDetails()
    {
        if ($this->isPost()) {
            $book_id = $this->getPost('book_id');

            $result1 = CirculationModel::getBookDetails($book_id);

            if ($result1) {
                $bookData = $result1->fetch_assoc();
                $this->jsonResponse([
                    "isbn" => $bookData['isbn'],
                    "title" => $bookData['title'],
                    "author" => $bookData['author'],
                ]);
            } else {
                Logger::warning("Book not found in loadBookDetails", ['book_id' => $book_id]);
                $this->jsonResponse(["message" => "Book not found."], false);
            }
        } else {
            Logger::warning("Invalid request method in loadBookDetails", ['method' => $_SERVER['REQUEST_METHOD']]);
            $this->jsonResponse(["message" => "Invalid request."], false);
        }
    }

    public function loadMemberDetails()
    {
        if ($this->isPost()) {
            $member_id = $this->getPost('member_id');
            $result = CirculationModel::getMemberDetails($member_id);

            if ($result) {
                $memberData = $result->fetch_assoc();
                $this->jsonResponse([
                    "nic" => $memberData['nic'],
                    "name" => $memberData['fname'] . " " . $memberData['lname'],
                    "email" => $memberData['email'],
                ]);
            } else {
                Logger::warning("Member not found in loadMemberDetails", ['member_id' => $member_id]);
                $this->jsonResponse(["message" => "Member not found."], false);
            }
        } else {
            Logger::warning("Invalid request method in loadMemberDetails", ['method' => $_SERVER['REQUEST_METHOD']]);
            $this->jsonResponse(["message" => "Invalid request."], false);
        }
    }

    public function issueBook()
    {
        if ($this->isPost()) {
            $book_id = $this->getPost('book_id');
            $member_id = $this->getPost('member_id');
            $issue_date = $this->getPost('borrow_date');
            $due_date = $this->getPost('due_date');
            $email = $this->getPost('email');
            $title = $this->getPost('title');
            $name = $this->getPost('memName');

            $result = CirculationModel::issueBook($book_id, $member_id, $issue_date, $due_date);

            if ($result["success"]) {
                Logger::info("Book issued successfully", [
                    'book_id' => $book_id,
                    'member_id' => $member_id,
                    'issue_date' => $issue_date,
                    'due_date' => $due_date,
                    'email' => $email
                ]);
                $this->sendIssueBookEmail($email, $name, $title, $issue_date, $due_date, $book_id);
                $this->jsonResponse(["message" => $result["message"]], true);
            } else {
                Logger::error("Failed to issue book", [
                    'book_id' => $book_id,
                    'member_id' => $member_id,
                    'error_message' => $result["message"]
                ]);
                $this->jsonResponse(["message" => $result["message"]], false);
            }
        } else {
            Logger::warning("Invalid request method in issueBook", ['method' => $_SERVER['REQUEST_METHOD']]);
            $this->jsonResponse(["message" => "Invalid request method."], false);
        }
    }

    public function sendIssueBookEmail($email, $name, $title, $issue_date, $due_date, $book_id)
    {
        require_once Config::getServicePath('emailService.php');

        $subject = 'Book Issued';

        $specificMessage = '<h4>This is to confirm that the book ' . $title . '('.$book_id.') has been successfully issued to your account on ' . $issue_date . '.
                            <br><br>Please return the book before ' . $due_date . ' to avoid any late fees.</h4>';

        $emailTemplate = new EmailTemplate();
        $body = $emailTemplate->getEmailBody($name, $specificMessage);

        $emailService = new EmailService();
        $emailSent = $emailService->sendEmail($email, $subject, $body);

        Logger::info("Issue book email sent", [
            'email' => $email,
            'book_id' => $book_id,
            'sent' => $emailSent ? 'success' : 'failure'
        ]);

        $notificationController = new NotificationController();
        $notification = $notificationController->insertNotification($email, strip_tags($specificMessage));
    }

    public function returnBook()
    {
        if ($this->isPost()) {
            $borrow_id = $this->getPost('borrowId');
            $book_id = $this->getPost('bookId');
            $memberId = $this->getPost('memberId');
            $return_date = $this->getPost('returnDate');
            $fines = $this->getPost('fines');
            $name = $this->getPost('name');
            $title = $this->getPost('title');
            $email = $this->getPost('email');

            $result = CirculationModel::returnBook($borrow_id, $return_date, $book_id, $fines, $memberId);

            if ($result) {
                Logger::info("Book returned successfully", [
                    'borrow_id' => $borrow_id,
                    'book_id' => $book_id,
                    'member_id' => $memberId,
                    'return_date' => $return_date,
                    'fines' => $fines,
                ]);
                CirculationModel::notifyNextWaitlistMember($book_id);
                $this->sendReturnBookEmail($email, $return_date, $name, $title, $book_id);
                $this->jsonResponse(["message" => "Book Returned."]);
            } else {
                Logger::error("Failed to return book", [
                    'borrow_id' => $borrow_id,
                    'book_id' => $book_id,
                    'member_id' => $memberId,
                ]);
                $this->jsonResponse(["message" => "Failed to return book."], false);
            }
        } else {
            Logger::warning("Invalid request method in returnBook", ['method' => $_SERVER['REQUEST_METHOD']]);
            $this->jsonResponse(["message" => "Invalid request."], false);
        }
    }

    public function sendReturnBookEmail($email, $return_date, $name, $title, $book_id)
    {
        require_once Config::getServicePath('emailService.php');

        $subject = 'Book Returned';

        $specificMessage = '<h4>This is to confirm that the book ' . $title . '('.$book_id.') has been successfully returned on ' . $return_date . '.';

        $emailTemplate = new EmailTemplate();
        $body = $emailTemplate->getEmailBody($name, $specificMessage);

        $emailService = new EmailService();
        $emailSent = $emailService->sendEmail($email, $subject, $body);

        Logger::info("Return book email sent", [
            'email' => $email,
            'book_id' => $book_id,
            'sent' => $emailSent ? 'success' : 'failure'
        ]);

        $notificationController = new NotificationController();
        $notification = $notificationController->insertNotification($email, strip_tags($specificMessage));
    }
}
