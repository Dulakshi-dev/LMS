<?php

require_once __DIR__ . '/../../../main.php';
require_once Config::getControllerPath('staff','notificationController.php');

// This controller handles all member-related actions
// like approving, rejecting, activating, deactivating,
// updating details, sending emails, etc.
class MemberController extends Controller
{
    private $memberModel;

    public function __construct()
    {
        require_once Config::getModelPath('staff','membermodel.php');
        $this->memberModel = new MemberModel();
    }


    public function getAllMembers() // Default number of results per page
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
            Logger::warning("Invalid request method for getAllMembers");
            $this->jsonResponse(["message" => "Invalid request."], false);
        }
    }

    public function getMemberRequests() // Similar logic but for membership requests
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
            Logger::warning("Invalid request method for getMemberRequests");
            $this->jsonResponse(["message" => "Invalid request."], false);
        }
    }

    public function approveMembership()
    {
        if ($this->isPost()) { // Approve a new member's request
            $id = $this->getPost('id');
            $name = $this->getPost('name');
            $email = $this->getPost('email');

            $memberID = MemberModel::generateMemberID();  // Generate member ID and password
            $password = MemberModel::generatePassword();
            $result = MemberModel::approveMembership($id, $memberID, $password);

            if ($result) {
                Logger::info("Membership approved", ['id' => $id, 'memberID' => $memberID]);
                $this->sendRequestApprovedEmail($id, $name, $email, $memberID, $password);
                $this->jsonResponse(["message" => "Membership approved"]);
            } else {
                Logger::error("Failed to approve membership - user not found", ['id' => $id]);
                $this->jsonResponse(["message" => "User not found."], false);
            }
        } else {
            Logger::warning("Invalid request method for approveMembership");
            $this->jsonResponse(["message" => "Invalid request."], false);
        }
    }

    public function sendRequestApprovedEmail($id, $name, $email, $memberID, $password) // Send approval email with credentials and reset password link
    {
        require_once Config::getServicePath('emailService.php');

        $subject = 'Membership Request Approved';

        $resetLink = '<div style="margin-bottom: 10px;">
        <a href="http://localhost/LMS/public/member/index.php?action=showresetpw&id=' . $id . '">Click here to reset your password</a>
        </div>';

        $specificMessage = '<h4>Welcome to our Library Management System.</h4>
        <p>You can now log in to the library management system using the credentials provided below:</p>
                <h4>Your Member ID: ' . $memberID . '</h4>
                <h4>Your temporary password: ' . $password . '</h4>
                <p>Please use the above credentials to log in to your account or if you want to change the password before login to the system, below is the password reset link.</p>
                ' . $resetLink;

        $emailTemplate = new EmailTemplate();
        $body = $emailTemplate->getEmailBody($name, $specificMessage);

        $emailService = new EmailService();
        $emailSent = $emailService->sendEmail($email, $subject, $body);

        if ($emailSent) {
            Logger::info("Approval email sent", ['email' => $email]);
        } else {
            Logger::error("Failed to send approval email", ['email' => $email]);
        }

        $notificationController = new NotificationController();
        $notification = $notificationController->insertNotification($email, "Welcome to our Library Management System");
    }

    public function deactivateMember()   // Deactivate an existing member
    {
        if ($this->isPost()) {
            $id = $this->getPost('id');
            $name = $this->getPost('name');
            $email = $this->getPost('email');

            $result = MemberModel::deactivateMember($id, $name, $name);

            if ($result) {
                Logger::info("Member deactivated", ['id' => $id]);
                $this->sendDeactivateMemberEmail($name, $email);
                $this->jsonResponse(["message" => "Member deactivated"]);
            } else {
                Logger::error("Failed to deactivate member - not found", ['id' => $id]);
                $this->jsonResponse(["message" => "Member not found."], false);
            }
        } else {
            Logger::warning("Invalid request method for deactivateMember");
            $this->jsonResponse(["message" => "Invalid request."], false);
        }
    }

    public function sendDeactivateMemberEmail($name, $email) // Send email to user after deactivation
    {
        require_once Config::getServicePath('emailService.php');

        $subject = 'Membership Deactivated';

        $specificMessage = '<h4>Your library account has been deactivated by the administration.</h4>';

        $emailTemplate = new EmailTemplate();
        $body = $emailTemplate->getEmailBody($name, $specificMessage);

        $emailService = new EmailService();
        $emailSent = $emailService->sendEmail($email, $subject, $body);

        if ($emailSent) {
            Logger::info("Deactivate email sent", ['email' => $email]);
        } else {
            Logger::error("Failed to send deactivate email", ['email' => $email]);
        }
    }

    public function rejectMember() // Reject a membership request
    {
        if ($this->isPost()) {
            $id = $this->getPost('id');
            $name = $this->getPost('name');
            $email = $this->getPost('email');

            $result = MemberModel::rejectMember($id, $name, $name);

            if ($result) {
                Logger::info("Membership request rejected", ['id' => $id]);
                $this->sendRejectMemberEmail($name, $email);
                $this->jsonResponse(["message" => "Member Rejected"]);
            } else {
                Logger::error("Failed to reject membership request - not found", ['id' => $id]);
                $this->jsonResponse(["message" => "User not found."], false);
            }
        } else {
            Logger::warning("Invalid request method for rejectMember");
            $this->jsonResponse(["message" => "Invalid request."], false);
        }
    }

    public function sendRejectMemberEmail($name, $email)  // Send rejection email to member
    {
        require_once Config::getServicePath('emailService.php');

        $subject = 'Membership Request Rejected';

        $specificMessage = '<h4>Your membership request has been rejected by the administration.</h4>';

        $emailTemplate = new EmailTemplate();
        $body = $emailTemplate->getEmailBody($name, $specificMessage);

        $emailService = new EmailService();
        $emailSent = $emailService->sendEmail($email, $subject, $body);

        if ($emailSent) {
            Logger::info("Reject email sent", ['email' => $email]);
        } else {
            Logger::error("Failed to send reject email", ['email' => $email]);
        }
    }

    public function loadMemberDetails()
    {
        if ($this->isPost()) {  // Load member details by ID
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
                Logger::error("Failed to load member details - not found", ['member_id' => $member_id]);
                $this->jsonResponse(["message" => "User not found."], false);
            }
        } else {
            Logger::warning("Invalid request method for loadMemberDetails");
            $this->jsonResponse(["message" => "Invalid request."], false);
        }
    }

    public function UpdateMemberDetails() // Update member details
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
                Logger::info("Member updated successfully", ['member_id' => $member_id]);
                $this->jsonResponse(["message" => "Member updated successfully."]);
            } else {
                Logger::error("Failed to update member - not found", ['member_id' => $member_id]);
                $this->jsonResponse(["message" => "Member not found."], false);
            }
        } else {
            Logger::warning("Invalid request method for UpdateMemberDetails");
            $this->jsonResponse(["message" => "Invalid request."], false);
        }
    }

    public function loadMailData()  // Load mail details of a membe
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
                Logger::error("Failed to load mail data - member not found", ['member_id' => $member_id]);
                $this->jsonResponse(["message" => "Member not found."], false);
            }
        } else {
            Logger::warning("Invalid request method for loadMailData");
            $this->jsonResponse(["message" => "Invalid request."], false);
        }
    }

    public function sendMail()  // Send email to a specific member
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
                Logger::info("Email sent to member", ['email' => $email, 'subject' => $subject]);
                $this->jsonResponse(["message" => "Email sent successfully!"]);
            } else {
                Logger::error("Failed to send email to member", ['email' => $email, 'subject' => $subject]);
                $this->jsonResponse(["message" => "Failed to send email."], false);
            }
        } else {
            Logger::warning("Invalid request method for sendMail");
            $this->jsonResponse(["message" => "Invalid Request"], false);
        }
    }

    public function activateMember() // Activate a member again
    {
        if ($this->isPost()) {
            $id = $this->getPost('id');
            $name = $this->getPost('name');
            $email = $this->getPost('email');

            $result = MemberModel::activateMember($id, $name, $name);

            if ($result) {
                Logger::info("Member activated", ['id' => $id]);
                $this->sendActivateMemberEmail($name, $email);
                $this->jsonResponse(["message" => "Member activated again"]);
            } else {
                Logger::error("Failed to activate member - not found", ['id' => $id]);
                $this->jsonResponse(["message" => "Member not found."], false);
            }
        } else {
            Logger::warning("Invalid request method for activateMember");
            $this->jsonResponse(["message" => "Invalid request."], false);
        }
    }

    public function sendActivateMemberEmail( $name, $email)   // Send activation email
    {
        require_once Config::getServicePath('emailService.php');

        $subject = 'Membership Activated Again';

        $specificMessage = '<h4>Your account has been activated again by the administration.</h4>';

        $emailTemplate = new EmailTemplate();
        $body = $emailTemplate->getEmailBody($name, $specificMessage);

        $emailService = new EmailService();
        $emailSent = $emailService->sendEmail($email, $subject, $body);

        if ($emailSent) {
            Logger::info("Activate email sent", ['email' => $email]);
        } else {
            Logger::error("Failed to send activate email", ['email' => $email]);
        }
    }

    public function activateRequest() // Reactivate a membership request
    {
        if ($this->isPost()) {
            $id = $this->getPost('id');

            $result = MemberModel::activateRequest($id);

            if ($result) {
                Logger::info("Request activated again", ['id' => $id]);
                $this->jsonResponse(["message" => "Request activated again"]);
            } else {
                Logger::error("Failed to activate request - not found", ['id' => $id]);
                $this->jsonResponse(["message" => "Request not found."], false);
            }
        } else {
            Logger::warning("Invalid request method for activateRequest");
            $this->jsonResponse(["message" => "Invalid request."], false);
        }
    }

}
