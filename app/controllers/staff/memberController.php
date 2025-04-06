<?php

require_once __DIR__ . '/../../../main.php';
require_once Config::getControllerPath('staff','notificationController.php');

class MemberController extends Controller
{
    private $memberModel;

    public function __construct()
    {
        require_once Config::getModelPath('staff','membermodel.php');
        $this->memberModel = new MemberModel();
    }

    public function getAllMembers()
    {
        $resultsPerPage = 10;
        if ($this->isPost()) {

            $page = $this->getPost('page', 1);
            $memberId = $this->getPost('memberid');
            $nic = $this->getPost('nic');
            $userName = $this->getPost('username');
            $status = $this->getPost('status', 'Active');

            if (!empty($memberId) || !empty($nic) || !empty($userName)) {
                $membersData = MemberModel::searchMembers($memberId, $nic, $userName, $status, $page, $resultsPerPage);
            } else {
                $membersData = MemberModel::getAllMembers($page, $resultsPerPage, $status);
            }

            $members = $membersData['results'] ?? [];
            $total = $membersData['total'] ?? 0;
            $totalPages = ceil($total / $resultsPerPage);

            $this->jsonResponse([
                "members" => $members,
                "total" => $total,
                "totalPages" => $totalPages,
                "currentPage" => $page
            ]);

        } else {
            $this->jsonResponse(["message" => "Invalid request."], false);
        }
    }

    public function getMemberRequests()
    {
        $resultsPerPage = 10;
        if ($this->isPost()) {

            $page = $this->getPost('page', 1);
            $nic = $this->getPost('nic');
            $userName = $this->getPost('username');
            $status = $this->getPost('status', 'Pending');

            if (!empty($nic) || !empty($userName)) {
                $requestData = MemberModel::searchMemberRequests($nic, $userName, $status, $page, $resultsPerPage);
            } else {
                $requestData = MemberModel::getAllMemberRequests($page, $resultsPerPage, $status);
            }

            $requests = $requestData['results'] ?? [];
            $total = $requestData['total'] ?? 0;
            $totalPages = ceil($total / $resultsPerPage);

            $this->jsonResponse([
                "requests" => $requests,
                "total" => $total,
                "totalPages" => $totalPages,
                "currentPage" => $page
            ]);
        } else {
            $this->jsonResponse(["message" => "Invalid request."], false);
        }
    }

    public function approveMembership()
    {
        if ($this->isPost()) {
            $id = $this->getPost('id');

            $result = MemberModel::approveMembership($id);

            if ($result) {
                $this->jsonResponse(["message" => "Membership approved"]);
            } else {
                $this->jsonResponse(["message" => "User not found."], false);
            }
        } else {
            $this->jsonResponse(["message" => "Invalid request."], false);
        }
    }

    public function deactivateMember()
    {
        if ($this->isPost()) {
            $id = $this->getPost('id');

            $result = MemberModel::deactivateMember($id);

            if ($result) {
                $this->jsonResponse(["message" => "Member deactivated"]);
            } else {
                $this->jsonResponse(["message" => "Member not found."], false);
            }
        } else {
            $this->jsonResponse(["message" => "Invalid request."], false);
        }
    }

    public function rejectMember()
    {
        if ($this->isPost()) {
            $id = $this->getPost('id');

            $result = MemberModel::rejectMember($id);

            if ($result) {
                $this->jsonResponse(["message" => "Member Rejected"]);
            } else {
                $this->jsonResponse(["message" => "User not found."], false);
            }
        } else {
            $this->jsonResponse(["message" => "Invalid request."], false);
        }
    }

    public function loadMemberDetails()
    {
        if ($this->isPost()) {
            $member_id = $this->getPost('member_id');

            $result = MemberModel::loadMemberDetails($member_id);

            if ($result) {
                $userData = $result->fetch_assoc();
                $this->jsonResponse([
                    "member_id" => $userData['member_id'],
                    "nic" => $userData['nic'],
                    "fname" => $userData['fname'],
                    "lname" => $userData['lname'],
                    "email" => $userData['email'],
                    "mobile" => $userData['mobile'],
                    "address" => $userData['address'],
                ]);
            } else {
                $this->jsonResponse(["message" => "User not found."], false);
            }
        } else {
            $this->jsonResponse(["message" => "Invalid request."], false);
        }
    }

    public function UpdateMemberDetails()
    {
        if ($this->isPost()) {
            $member_id = $this->getPost('userId');
            $full_name = $this->getPost('name');
            $email = $this->getPost('email');
            $phone = $this->getPost('phone');
            $address = $this->getPost('address');
            $nic = $this->getPost('nic');

            $name_parts = explode(" ", trim($full_name));

            $firstName = $name_parts[0] ?? '';
            $lastName = $name_parts[1] ?? '';

            $result = MemberModel::UpdateMemberDetails($member_id, $firstName, $lastName, $email, $phone, $address, $nic);

            if ($result) {
                $this->jsonResponse(["message" => "Member updated successfully."]);
            } else {
                $this->jsonResponse(["message" => "Member not found."], false);
            }
        } else {
            $this->jsonResponse(["message" => "Invalid request."], false);
        }
    }

    public function loadMailData()
    {
        if ($this->isPost()) {
            $member_id = $this->getPost('member_id');

            $result = memberModel::loadMailDetails($member_id);

            if ($result) {
                $mailData = $result->fetch_assoc();
                $this->jsonResponse([
                    "name" => $mailData['fname'] . " " . $mailData['lname'],
                    "email" => $mailData['email'],
                ]);
            } else {
                $this->jsonResponse(["message" => "Member not found."], false);
            }
        } else {
            $this->jsonResponse(["message" => "Invalid request."], false);
        }
    }

    public function sendMail()
    {
        if ($this->isPost()) {

            $name = $this->getPost('name');
            $email = $this->getPost('email');
            $subject = $this->getPost('subject');
            $msg = $this->getPost('message');

            $result = memberModel::sendMemberMail($name, $email, $subject, $msg);

            $notificationController = new NotificationController();
            $notification = $notificationController->insertNotification($email, $subject);

            if ($result && $notification) {
                $this->jsonResponse(["message" => "Email sent successfully!"]);
            } else {
                $this->jsonResponse(["message" => "Failed to send email."], false);
            }
        } else {
            $this->jsonResponse(["message" => "Invalid Request"], false);
        }
    }

    public function activateMember()
    {
        if ($this->isPost()) {
            $id = $this->getPost('memberid');

            $result = MemberModel::activateMember($id);

            if ($result) {
                $this->jsonResponse(["message" => "Member activated again"]);
            } else {
                $this->jsonResponse(["message" => "Member not found."], false);
            }
        } else {
            $this->jsonResponse(["message" => "Invalid request."], false);
        }
    }

    public function activateRequest()
    {
        if ($this->isPost()) {
            $id = $this->getPost('id');

            $result = MemberModel::activateRequest($id);

            if ($result) {
                $this->jsonResponse(["message" => "Request activated again"]);
            } else {
                $this->jsonResponse(["message" => "Request not found."], false);
            }
        } else {
            $this->jsonResponse(["message" => "Invalid request."], false);
        }
    }
}
