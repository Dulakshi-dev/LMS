<?php

use LDAP\Result;

require_once __DIR__ . '/../../main.php';

class UserController
{
    private $userModel;

    public function __construct()
    {
        require_once Config::getModelPath('usermodel.php');
        $this->userModel = new UserModel();
    }

    public function loadUsers()
    {
        $resultsPerPage = 10;
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $page = isset($_POST['page']) ? (int)$_POST['page'] : 1;
            $memberId = $_POST['memberid'] ?? null;
            $nic = $_POST['nic'] ?? null;
            $userName = $_POST['username'] ?? null;
            $status = $_POST['status'] ?? 'Active';

           
        if (!empty($memberId) || !empty($nic) || !empty($userName)) {
            $usersData = UserModel::searchUsers($memberId, $nic, $userName, $status, $page, $resultsPerPage);
        } else {
            $usersData = UserModel::getAllUsers($page, $resultsPerPage, $status);
        }
        
            $users = $usersData['results'] ?? [];
            $total = $usersData['total'] ?? 0;
            $totalPages = ceil($total / $resultsPerPage);
        
            echo json_encode([
                "success" => true,
                "users" => $users,
                "total" => $total,
                "totalPages" => $totalPages,
                "currentPage" => $page
            ]);

        }else{
            echo json_encode(["success" => false, "message" => "Invalid request."]);

        }
    }
    
    public function loadUserDetails()
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $user_id = $_POST['user_id'];

            $result = UserModel::loadUserDetails($user_id);

            if ($result) {
                $userData = $result->fetch_assoc();
                echo json_encode([
                    "success" => true,
                    "id" => $userData['id'],
                    "user_id" => $userData['user_id'],
                    "nic" => $userData['nic'],
                    "fname" => $userData['fname'],
                    "lname" => $userData['lname'],
                    "email" => $userData['email'],
                    "mobile" => $userData['mobile'],
                    "address" => $userData['address'],
                    "profile_img" => $userData['profile_img']
                ]);
            } else {
                echo json_encode(["success" => false, "message" => "User not found."]);
            }
        } else {
            echo json_encode(["success" => false, "message" => "Invalid request."]);
        }
    }

    public function updateUserDetails()
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $user_id = $_POST['userId'];
            $full_name = $_POST['username'];
            $email = $_POST["email"];
            $phone = $_POST["phone"];
            $address = $_POST["address"];
            $nic = $_POST["nic"];

            $name_parts = explode(" ", trim($full_name));

            $firstName = isset($name_parts[0]) ? $name_parts[0] : '';
            $lastName = isset($name_parts[1]) ? $name_parts[1] : '';

            $result = UserModel::UpdateUserDetails($user_id, $firstName, $lastName, $email, $phone, $address, $nic);

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
            $user_id = $_POST['user_id'];

            $result = UserModel::loadMailDetails($user_id);

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

    public function deactivateUser()
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $id = $_POST['user_id'];

            $result = UserModel::deactivateUser($id);

            if ($result) {
                echo json_encode(["success" => true, "message" => "Membership Deactivated"]);
            } else {
                echo json_encode(["success" => false, "message" => "User Not found."]);
            }
        } else {
            echo json_encode(["success" => false, "message" => "Invalid request."]);
        }
    }

    public function activateUser()
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $id = $_POST['user_id'];

            $result = UserModel::activateUser($id);

            if ($result) {
                echo json_encode(["success" => true, "message" => "Staff Member Activated Again"]);
            } else {
                echo json_encode(["success" => false, "message" => "Staff Member not found."]);
            }
        } else {
            echo json_encode(["success" => false, "message" => "Invalid request."]);
        }
    }

    public function sendEnrollmentKey()
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $email = $_POST['email'];

            $result = UserModel::sendEnrollmentKey($email);

            if ($result) {
                echo json_encode(["success" => true, "message" => "Enrollment Key Sent"]);
            } else {
                echo json_encode(["success" => false, "message" => "Failed to send enrollment key."]);
            }
        } else {
            echo json_encode(["success" => false, "message" => "Invalid request."]);
        }
    }


}
