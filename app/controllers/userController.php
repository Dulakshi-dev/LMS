<?php
require_once __DIR__ . '/../../main.php';

class UserController
{
    private $userModel;

    public function __construct()
    {
        require_once Config::getModelPath('usermodel.php');
        $this->userModel = new UserModel();
    }

    public function getAllUsers()
    {
        $users = [];
        // Retrieve all users from the model
        $users  = $this->userModel->getAllUsers();
        require_once Config::getViewPath("staff", 'user-management.php');

    }

    public function searchUsers()
    {
        $users = [];
        
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            // Retrieve input from the POST request
            $memberId = $_POST['memberId'] ?? null;
            $nic = $_POST['nic'] ?? null;
            $userName = $_POST['userName'] ?? null;

            if (empty($memberId) && empty($nic) && empty($userName)) {
                $users = $this->getAllUsers();
                require_once Config::getViewPath("staff", 'user-management.php');

            }else{
                $users =  $this->userModel->searchUsers($memberId, $nic, $userName);
            require_once Config::getViewPath("staff", 'user-management.php');
            }
        } else {
            return []; // Return an empty array or an appropriate error response
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
                    "user_id" => $userData['user_id'],
                    "nic" => $userData['nic'],
                    "fname" => $userData['fname'],
                    "lname" => $userData['lname'],
                    "email" => $userData['email'],
                    "mobile" => $userData['mobile'],
                    "address" => $userData['address']
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
                    "name" => $mailData['fname']." ".$mailData['lname'],
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
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $name = $_POST['name'];
            $email = $_POST['email'];
            $subject = $_POST['subject'];
            $message = $_POST['message'];

            $result = UserModel::loadMailDetails($name);

            if ($result) {
                $mailData = $result->fetch_assoc();
                echo json_encode([
                    "success" => true,
                    "name" => $mailData['fname']." ".$mailData['lname'],
                    "email" => $mailData['email'],

                ]);
            } else {
                echo json_encode(["success" => false, "message" => "User not found."]);
            }
        } else {
            echo json_encode(["success" => false, "message" => "Invalid request."]);
        }
    }

}
