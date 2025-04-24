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
            $name = $this->getPost('name');
            $email = $this->getPost('email');

            $memberID = MemberModel::generateMemberID();
            $password = MemberModel::generatePassword();
            $result = MemberModel::approveMembership($id, $memberID, $password);  


            if ($result) {
                $this->sendRequestApprovedEmail($id, $name, $email, $memberID, $password);
                $this->jsonResponse(["message" => "Membership approved"]);
            } else {
                $this->jsonResponse(["message" => "User not found."], false);
            }
        } else {
            $this->jsonResponse(["message" => "Invalid request."], false);
        }
    }

    public function sendRequestApprovedEmail($id, $name, $email, $memberID, $password)
    {
        require_once Config::getServicePath('emailService.php');

        $subject = 'Membership Request Approved';

        $resetLink = '<div style="margin-bottom: 10px;">
        <a href="http://localhost/LMS/public/member/index.php?action=showresetpw&id=' . $id . '">Click here to reset your password</a>
        </div>';

        $specificMessage = '<h4>Welcome to our Library Management Syatem.</h4>
        <p>You can now log in to the library management system using the credentials provided below:</p>
                <h4>Your Member ID: ' . $memberID . '</h4>
                <h4>Your temporary password: ' . $password . '</h4>
                <p>Please use the above credentials to log in to your account or if you want to change the password before login to the system, below is the password reset link.</p>
                ' . $resetLink;

        $emailTemplate = new EmailTemplate();
        $body = $emailTemplate->getEmailBody($name, $specificMessage);

        $emailService = new EmailService();
        $emailSent = $emailService->sendEmail($email, $subject, $body);

        $notificationController = new NotificationController();
        $notification = $notificationController->insertNotification($email, "Welcome to our Library Management System");
    
    }

    public function deactivateMember()
    {
        if ($this->isPost()) {
            $id = $this->getPost('id');
            $name = $this->getPost('name');
            $email = $this->getPost('email');

            $result = MemberModel::deactivateMember($id, $name, $name);

            if ($result) {
                $this->sendDeactivateMemberEmail($name, $email);  
                $this->jsonResponse(["message" => "Member deactivated"]);
            } else {
                $this->jsonResponse(["message" => "Member not found."], false);
            }
        } else {
            $this->jsonResponse(["message" => "Invalid request."], false);
        }
    }

    public function sendDeactivateMemberEmail($name, $email)
    {
        require_once Config::getServicePath('emailService.php');

        $subject = 'Membership Deactivated';

        $specificMessage = '
                <h4>Your library account has been deactivated by the administration.</h4>';
             

        $emailTemplate = new EmailTemplate();
        $body = $emailTemplate->getEmailBody($name, $specificMessage);

        $emailService = new EmailService();
        $emailSent = $emailService->sendEmail($email, $subject, $body);

    }

    public function rejectMember()
    {
        if ($this->isPost()) {
            $id = $this->getPost('id');
            $name = $this->getPost('name');
            $email = $this->getPost('email');

            $result = MemberModel::rejectMember($id, $name, $name);

            if ($result) {
                $this->sendRejectMemberEmail($name, $email);  
                $this->jsonResponse(["message" => "Member Rejected"]);
            } else {
                $this->jsonResponse(["message" => "User not found."], false);
            }
        } else {
            $this->jsonResponse(["message" => "Invalid request."], false);
        }
    }

    public function sendRejectMemberEmail($name, $email)
    {
        require_once Config::getServicePath('emailService.php');

        $subject = 'Membership Request Rejected';

     
        $specificMessage = '<h4>Your membership request has been rejected by the administration.</h4>';
     
        $emailTemplate = new EmailTemplate();
        $body = $emailTemplate->getEmailBody($name, $specificMessage);

        $emailService = new EmailService();
        $emailSent = $emailService->sendEmail($email, $subject, $body);

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
            $id = $this->getPost('id');
            $name = $this->getPost('name');
            $email = $this->getPost('email');

            $result = MemberModel::activateMember($id, $name, $name);

            if ($result) {
                $this->sendActivateMemberEmail($name, $email);  
                $this->jsonResponse(["message" => "Member activated again"]);
            } else {
                $this->jsonResponse(["message" => "Member not found."], false);
            }
        } else {
            $this->jsonResponse(["message" => "Invalid request."], false);
        }
    }

    public function sendActivateMemberEmail( $name, $email)
    {
        require_once Config::getServicePath('emailService.php');

        $subject = 'Membership Activated Again';

        $specificMessage = '<h4>Youe account has been activated again by the administration.</h4>';

        $emailTemplate = new EmailTemplate();
        $body = $emailTemplate->getEmailBody($name, $specificMessage);

        $emailService = new EmailService();
        $emailSent = $emailService->sendEmail($email, $subject, $body);
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
