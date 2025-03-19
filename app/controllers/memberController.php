<?php

require_once __DIR__ . '/../../main.php';

class MemberController
{
    private $memberModel;

    public function __construct()
    {
        require_once Config::getModelPath('membermodel.php');

        $this->memberModel = new MemberModel();
    }

    public function getAllMembers()
    {
        $resultsPerPage = 10;
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {

            $page = isset($_POST['page']) ? (int)$_POST['page'] : 1;
            $memberId = $_POST['memberid'] ?? null;
            $nic = $_POST['nic'] ?? null;
            $userName = $_POST['username'] ?? null;

            if (!empty($memberId) || !empty($nic) || !empty($userName)) {
                $membersData = MemberModel::searchMembers($memberId, $nic, $userName, $page, $resultsPerPage);
             }else {
                $membersData = MemberModel::getAllMembers($page, $resultsPerPage);
            }
        
            $members = $membersData['results'] ?? [];
            $total = $membersData['total'] ?? 0;
            $totalPages = ceil($total / $resultsPerPage);
        
            echo json_encode([
                "success" => true,
                "members" => $members,
                "total" => $total,
                "totalPages" => $totalPages,
                "currentPage" => $page
            ]);

        }else{
            echo json_encode(["success" => false, "message" => "Invalid request."]);

        }
    }

    public function getMemberRequests()
    {
        $resultsPerPage = 10;
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {

            $page = isset($_POST['page']) ? (int)$_POST['page'] : 1;
            $nic = $_POST['nic'] ?? null;
            $userName = $_POST['username'] ?? null;

            if (!empty($memberId) || !empty($nic) || !empty($userName)) {
                $requestData = MemberModel::searchMemberRequests( $nic, $userName, $page, $resultsPerPage);
             }else {
                $requestData = MemberModel::getAllMemberRequests($page, $resultsPerPage);
            }
        
            $requests = $requestData['results'] ?? [];
            $total = $requestData['total'] ?? 0;
            $totalPages = ceil($total / $resultsPerPage);
        
            echo json_encode([
                "success" => true,
                "requests" => $requests,
                "total" => $total,
                "totalPages" => $totalPages,
                "currentPage" => $page
            ]);

        }else{
            echo json_encode(["success" => false, "message" => "Invalid request."]);

        }
    }

    public function approveMembership()
    {

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $id = $_POST['id'];

            $result = MemberModel::approveMembership($id);

            if ($result) {
                echo json_encode(["success" => true, "message" => "Membership approved"]);
            } else {
                echo json_encode(["success" => false, "message" => "User not found."]);
            }
        } else {
            echo json_encode(["success" => false, "message" => "Invalid request."]);
        }
    }

    public function deactivateMember()
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $id = $_POST['id'];

            $result = MemberModel::deactivateMember($id);

            if ($result) {
                echo json_encode(["success" => true, "message" => "Membership deactivated"]);
            } else {
                echo json_encode(["success" => false, "message" => "User not found."]);
            }
        } else {
            echo json_encode(["success" => false, "message" => "Invalid request."]);
        }
    }

    public function rejectMember()
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $id = $_POST['id'];

            $result = MemberModel::rejectMember($id);

            if ($result) {
                echo json_encode(["success" => true, "message" => "Member Rejected"]);
            } else {
                echo json_encode(["success" => false, "message" => "User not found."]);
            }
        } else {
            echo json_encode(["success" => false, "message" => "Invalid request."]);
        }
    }

    public function loadMemberDetails()
    {


        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $member_id = $_POST['member_id'];

            $result = MemberModel::loadMemberDetails($member_id);

            if ($result) {
                $userData = $result->fetch_assoc();
                echo json_encode([
                    "success" => true,
                    "member_id" => $userData['member_id'],
                    "nic" => $userData['nic'],
                    "fname" => $userData['fname'],
                    "lname" => $userData['lname'],
                    "email" => $userData['email'],
                    "mobile" => $userData['mobile'],
                    "address" => $userData['address'],
                ]);
            } else {
                echo json_encode(["success" => false, "message" => "User not found."]);
            }
        } else {
            echo json_encode(["success" => false, "message" => "Invalid request."]);
        }
    }

    public function UpdateMemberDetails()
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $member_id = $_POST['userId'];
            $full_name = $_POST['name'];
            $email = $_POST["email"];
            $phone = $_POST["phone"];
            $address = $_POST["address"];
            $nic = $_POST["nic"];

            $name_parts = explode(" ", trim($full_name));

            $firstName = isset($name_parts[0]) ? $name_parts[0] : '';
            $lastName = isset($name_parts[1]) ? $name_parts[1] : '';

            $result = MemberModel::UpdateMemberDetails($member_id, $firstName, $lastName, $email, $phone, $address, $nic);

            if ($result) {
                echo json_encode(["success" => true, "message" => "Member updated successfully."]);
            } else {
                echo json_encode(["success" => false, "message" => "Member not found."]);
            }
        } else {
            echo json_encode(["success" => false, "message" => "Invalid request."]);
        }
    }

    public function loadMailData()
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $member_id = $_POST['member_id'];

            $result = memberModel::loadMailDetails($member_id);

            if ($result) {
                $mailData = $result->fetch_assoc();
                echo json_encode([
                    "success" => true,
                    "name" => $mailData['fname'] . " " . $mailData['lname'],
                    "email" => $mailData['email'],

                ]);
            } else {
                echo json_encode(["success" => false, "message" => "Member not found."]);
            }
        } else {
            echo json_encode(["success" => false, "message" => "Invalid request."]);
        }
    }

    public function sendMail()
    {
        require_once Config::getServicePath('emailService.php');


        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $name = $_POST['name'];
            $email = $_POST["email"];
            $subject = $_POST["subject"];
            $msg = $_POST["message"];

            $body = '<h1 style="padding-top: 30px;">Shelf Loom</h1>
        <p style="font-size: 30px; color: black; font-weight: bold; text-align: center;">Welcome!</p> 
            <p>Dear ' . $name . ',</p>
        <div style="max-width: 600px; margin: 0 auto; padding: 20px; text-align: left;">
            <p>We are pleased to connect with you! Here’s some important information:</p>
            <p>' . $msg . '</p>
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
                echo json_encode(["success" => true, "message" => "Email sent successfully!"]);
            } else {
                echo json_encode(["success" => false, "message" => "Failed to send email."]);
            }
        } else {
            echo json_encode(["success" => false, "message" => "Invalid Request"]);
        }
    }

    
}
