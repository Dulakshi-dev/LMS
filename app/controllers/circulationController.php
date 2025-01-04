<?php
require_once __DIR__ . '/../../main.php';

class CirculationController{

    private $circulationModel;

    public function __construct()
    {
        require_once Config::getModelPath('circulationmodel.php');
        $this->circulationModel = new CirculationModel();
    }

    public function showCirculationManagement()
    {
        require_once Config::getViewPath("staff","circulation-management.php");
    }

    public function showIssueBook()
    {
        require_once Config::getViewPath("staff","issue-book.php");
    }

    public function loadBookDetails()
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $book_id = $_POST['book_id'];

            $result1 = CirculationModel::getBookDetails($book_id);

            if ($result1) {
                $bookData = $result1->fetch_assoc();
                echo json_encode([
                    "success" => true,
                    "isbn" => $bookData['isbn'],
                    "title" => $bookData['title'],
                    "author" => $bookData['author'],
            
                ]);
            } else {
                echo json_encode(["success" => false, "message" => "Book not found."]);
            }

        } else {
            echo json_encode(["success" => false, "message" => "Invalid request."]);
        }
    }

    public function loadMemberDetails()
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $member_id = $_POST['member_id'];
            $result = CirculationModel::getMemberDetails($member_id);


            if ($result) {
                $bookData = $result->fetch_assoc();
                echo json_encode([
                    "success" => true,
                    "nic" => $bookData['nic'],
                    "name" => $bookData['fname']." ".$bookData['lname'],
            
                ]);
            } else {
                echo json_encode(["success" => false, "message" => "Member not found."]);
            }

        } else {
            echo json_encode(["success" => false, "message" => "Invalid request."]);
        }
    }

    public function issueBook()
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $book_id = $_POST['book_id'];
            $member_id = $_POST['member_id'];
            $borrow_date = $_POST['borrow_date'];
            $due_date = $_POST['due_date'];
          
            $result = CirculationModel::issueBook($book_id, $member_id, $borrow_date, $due_date);
            if($result){
                header("Location: index.php?action=bookcirculation");
            }else{
                echo("error");

            }
  
        } else {
            echo json_encode(["success" => false, "message" => "Invalid request."]);
        }
    }

    public static function sendIssueBookReciept($email)
    {
        require_once Config::getServicePath('emailService.php');


        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $email = $_POST["email"];
            $subject = "Reset Password";
            
            $body = '
               <h1 style="padding-top: 30px;">Reset your password</h1>
               <p style = "font-size: 30px; color: black; font-weight: bold; text-align: center;">Shelf Loom</p> 

               <div style="max-width: 600px; margin: 0 auto; padding: 20px; text-align: left;">
                  <p>Dear Member,</p>
                  <p>We received a request to reset the password for your account. If you initiated this request, please click the button below to create a new password.</p>
                  <div style="margin-bottom: 10px;">
                        <a href="http://localhost/LMS/public/index.php?action=showresetpw&vcode='.$vcode.'">Click here to reset your password</a>
                  </div>
                  <div>
                        <p style="margin: 0px;">If you have problems or questions regarding your account, please contact us.</p>
                        <p style="margin: 0px;">Call: [tel_num]</p>
                  </div>

                  <div>
                        <p style="margin-bottom: 0px;">Best regards,</p>
                        <p style="margin: 0px;">Shelf Loom</p>
                  </div>
               </div>';

            $emailService = new EmailService();
            $emailSent = $emailService->sendEmail($email, $subject, $body);

            if ($emailSent) {
                echo ("Email for reset sent successfully! Check Your email address");

            } else {
                echo ("Failed to send email.");
            }
        } else {
            echo("Invalid Request");
        }
    }


    public function getAllBorrowBooks()
    {
        $page = isset($_GET['page']) ? (int)$_GET['page'] : 1; 
        $data = CirculationModel::getAllBorrowData($page);

        $totalBooks = $data['total']; 
        $booksResult = $data['results']; 

        $books = [];
        while ($row = $booksResult->fetch_assoc()) {
            $books[] = $row;
        }

        $resultsPerPage = 1;
        $totalPages = ceil($totalBooks / $resultsPerPage); 

        require_once Config::getViewPath("staff", 'view-issue-book.php');
    }

    public function searchBorrowBooks()
    {
        $books = [];

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            // Retrieve input from the POST request
            $memberid = $_POST['memberid'] ?? null;
            $bookid = $_POST['bookid'] ?? null;

            if (empty($memberid) && empty($bookid)) {
                $books = CirculationModel::getAllBorrowData(1);
                require_once Config::getViewPath("staff", 'view-issue-book.php');
            } else {
                $books =  CirculationModel::searchBorrowBooks($memberid, $bookid);
                require_once Config::getViewPath("staff", 'view-issue-book.php');
            }
        } else {
            return []; // Return an empty array or an appropriate error response
        }
    }

}

