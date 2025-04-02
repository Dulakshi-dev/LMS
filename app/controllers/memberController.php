<?php

require_once __DIR__ . '/../../main.php';

class MemberController
{
    private $memberModel;
    private $notificationModel;

    public function __construct()
    {
        require_once Config::getModelPath('membermodel.php');
        require_once Config::getModelPath('notificationmodel.php');
        $this->memberModel = new MemberModel();
        $this->notificationModel = new NotificationModel();

    }

    public function getAllMembers()
    {
        $resultsPerPage = 10;
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {

            $page = isset($_POST['page']) ? (int)$_POST['page'] : 1;
            $memberId = $_POST['memberid'] ?? null;
            $nic = $_POST['nic'] ?? null;
            $userName = $_POST['username'] ?? null;
            $status = $_POST['status'] ?? 'Active';


            if (!empty($memberId) || !empty($nic) || !empty($userName)) {
                $membersData = MemberModel::searchMembers($memberId, $nic, $userName,$status, $page, $resultsPerPage);
             }else {
                $membersData = MemberModel::getAllMembers($page, $resultsPerPage, $status);
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
            $status = $_POST['status'] ?? 'Pending';


            if (!empty($memberId) || !empty($nic) || !empty($userName)) {
                $requestData = MemberModel::searchMemberRequests( $nic, $userName,$status, $page, $resultsPerPage);
             }else {
                $requestData = MemberModel::getAllMemberRequests($page, $resultsPerPage, $status);
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
                echo json_encode(["success" => true, "message" => "Member deactivated"]);
            } else {
                echo json_encode(["success" => false, "message" => "Member not found."]);
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
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $name = $_POST['name'];
            $email = $_POST["email"];
            $subject = $_POST["subject"];
            $msg = $_POST["message"];

            $result = memberModel::sendMemberMail($name, $email, $$subject, $msg);

            if ($result) {
                echo json_encode(["success" => true, "message" => "Email sent successfully!"]);
            } else {
                echo json_encode(["success" => false, "message" => "Failed to send email."]);
            }
        } else {
            echo json_encode(["success" => false, "message" => "Invalid Request"]);
        }
    }

    public function activateMember()
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $id = $_POST['memberid'];

            $result = MemberModel::activateMember($id);

            if ($result) {
                echo json_encode(["success" => true, "message" => "Member activated again"]);
            } else {
                echo json_encode(["success" => false, "message" => "Member not found."]);
            }
        } else {
            echo json_encode(["success" => false, "message" => "Invalid request."]);
        }
    }

    public function activateRequest()
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $id = $_POST['id'];

            $result = MemberModel::activateRequest($id);

            if ($result) {
                echo json_encode(["success" => true, "message" => "Request activated again"]);
            } else {
                echo json_encode(["success" => false, "message" => "Request not found."]);
            }
        } else {
            echo json_encode(["success" => false, "message" => "Invalid request."]);
        }
    }

 
    
}
