<?php
require_once __DIR__ . '/../../main.php';

class CirculationController{

    private $circulationModel;

    public function __construct()
    {
        require_once Config::getModelPath('circulationmodel.php');
        $this->circulationModel = new CirculationModel();
    }

    public function getAllBorrowBooks()
    {
        $resultsPerPage = 10;

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $page = isset($_POST['page']) ? (int)$_POST['page'] : 1; 
            $bookid = $_POST['bookid'] ?? null;
            $memberid = $_POST['memberid'] ?? null;

            if (!empty($bookid) || !empty($memberid)) {
                $bookData = CirculationModel::searchBorrowData($bookid, $memberid, $page, $resultsPerPage);
             }else {
                $bookData = CirculationModel::getAllBorrowData($page, $resultsPerPage);
            }

            $issuebooks = $bookData['results'] ?? [];
            $total = $bookData['total'] ?? 0;
            $totalPages = ceil($total / $resultsPerPage);
        
            echo json_encode([
                "success" => true,
                "issuebooks" => $issuebooks,
                "total" => $total,
                "totalPages" => $totalPages,
                "currentPage" => $page
            ]);

        }else{
            echo json_encode(["success" => false, "message" => "Invalid request."]);

        }
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

    public function returnBook()
{
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $borrow_id = $_POST['borrowId'];
        $book_id = $_POST['bookId'];
        $memberId = $_POST['memberId'];
        $return_date = $_POST['returnDate'];
        $fines = $_POST['fines'];

        // Call the returnBook method and get the result including fine amount
        $result = CirculationModel::returnBook($borrow_id, $return_date, $book_id, $fines, $memberId);

        // Check if the result was successful
        if ($result) {
            $fineAmount = $result['fine_amount'];  // Get the fine amount from the result

            // Output the fine amount for debugging
            echo "Fine Amount: " . $fineAmount; // This will display the fine amount on the page

            // Redirect after debugging (optional, can be removed if only debugging)
            header("Location: index.php?action=viewissuebooks"); 
        } else {
            echo "Error: " . $result['message']; // Show error message if there is a failure
        }
    } else {
        echo json_encode(["success" => false, "message" => "Invalid request."]);
    }
}


}

