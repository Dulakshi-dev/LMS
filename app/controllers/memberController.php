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
        $page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
        $data = MemberModel::getAllMembers($page);

        $totalUsers = $data['total'];
        $usersResult = $data['results'];

        $users = [];
        while ($row = $usersResult->fetch_assoc()) {
            $users[] = $row;
        }

        $resultsPerPage = 10;
        $totalPages = ceil($totalUsers / $resultsPerPage);

        require_once Config::getViewPath("staff", 'view-members.php');
    }

    public function getPendingMembers()
    {
        $page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
        $data = MemberModel::getPendingMembers($page);

        $totalUsers = $data['total'];
        $usersResult = $data['results'];

        $users = [];
        while ($row = $usersResult->fetch_assoc()) {
            $users[] = $row;
        }

        $resultsPerPage = 10;
        $totalPages = ceil($totalUsers / $resultsPerPage);

        require_once Config::getViewPath("staff", 'view-member-requests.php');
    }

    public function approveMembership()
    {

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $id = $_POST['id'];

            $result = MemberModel::approveMembership($id);

            if ($result) {
                echo json_encode(["success" => true, "message" => "Membership active"]);
            } else {
                echo json_encode(["success" => false, "message" => "User not found."]);
            }
        } else {
            echo json_encode(["success" => false, "message" => "Invalid request."]);
        }
    }

    public function searchUsers()
    {
        $users = [];

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            // Retrieve and sanitize input from the POST request
            $memberId = trim($_POST['memberId'] ?? '');
            $nic = trim($_POST['nic'] ?? '');
            $userName = trim($_POST['userName'] ?? '');

            if (empty($memberId) && empty($nic) && empty($userName)) {
                header("Location: index.php?action=staffmanagement");
            } else {
                $users = MemberModel::searchMembers($memberId, $nic, $userName);
            }
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
                echo json_encode(["success" => true, "message" => "User updated successfully."]);
            } else {
                echo json_encode(["success" => false, "message" => "User not found."]);
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
                echo json_encode(["success" => false, "message" => "User not found."]);
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

    public function changeMemnerStatus()
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $id = $_POST['id'];

            $result = MemberModel::toggleMemberStatus($id);
            if ($result) {
                echo json_encode(["success" => true, "message" => "User Status Changed"]);
            } else {
                echo json_encode(["success" => false, "message" => "User not found."]);
            }
        } else {
            echo json_encode(["success" => false, "message" => "Invalid request."]);
        }
    }
}
