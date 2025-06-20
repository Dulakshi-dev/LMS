<?php

require_once __DIR__ . '/../../../main.php';

class StaffController extends Controller
{
    private $staffModel;

    public function __construct()
    {
        require_once Config::getModelPath('staff', 'staffmodel.php');
        $this->staffModel = new StaffModel();
    }

    public function loadStaff()
    {
        $resultsPerPage = 10;

        if ($this->isPost()) {
            $page = (int)$this->getPost('page', 1);
            $memberId = $this->getPost('memberid');
            $nic = $this->getPost('nic');
            $userName = $this->getPost('username');
            $status = $this->getPost('status', 'Active');

            if (!empty($memberId) || !empty($nic) || !empty($userName)) {
                $usersData = StaffModel::searchStaff($memberId, $nic, $userName, $status, $page, $resultsPerPage);
            } else {
                $usersData = StaffModel::getAllStaff($page, $resultsPerPage, $status);
            }

            $users = $usersData['results'] ?? [];
            $total = $usersData['total'] ?? 0;
            $totalPages = ceil($total / $resultsPerPage);

            $this->jsonResponse([
                "users" => $users,
                "total" => $total,
                "totalPages" => $totalPages,
                "currentPage" => $page
            ]);
        } else {
            Logger::warning("loadStaff invalid request method", ['method' => $_SERVER['REQUEST_METHOD']]);
            $this->jsonResponse(["message" => "Invalid request."], false);
        }
    }

    public function loadStaffDetails()
    {
        if ($this->isPost()) {
            $user_id = $this->getPost('staff_id');

            $result = StaffModel::loadStaffDetails($user_id);

            if ($result && $result->num_rows > 0) {
                $userData = $result->fetch_assoc();

                if ($userData) {
                    $this->jsonResponse([
                        "id" => $userData['id'],
                        "user_id" => $userData['staff_id'],
                        "nic" => $userData['nic'],
                        "fname" => $userData['fname'],
                        "lname" => $userData['lname'],
                        "email" => $userData['email'],
                        "mobile" => $userData['mobile'],
                        "address" => $userData['address'],
                        "profile_img" => $userData['profile_img']
                    ]);
                } else {
                    Logger::warning("loadStaffDetails user data empty", ['staff_id' => $user_id]);
                    $this->jsonResponse(["message" => "User data not found."], false);
                }
            } else {
                Logger::warning("loadStaffDetails user not found", ['staff_id' => $user_id]);
                $this->jsonResponse(["message" => "User not found."], false);
            }
        } else {
            Logger::warning("loadStaffDetails invalid request method", ['method' => $_SERVER['REQUEST_METHOD']]);
            $this->jsonResponse(["message" => "Invalid request."], false);
        }
    }

    public function updateStaffDetails()
    {
        if ($this->isPost()) {
            $user_id = $this->getPost('staffId');
            $full_name = $this->getPost('username');
            $email = $this->getPost('email');
            $phone = $this->getPost('phone');
            $address = $this->getPost('address');
            $nic = $this->getPost('nic');

            $name_parts = explode(" ", trim($full_name));
            $firstName = $name_parts[0] ?? '';
            $lastName = $name_parts[1] ?? '';

            $result = StaffModel::updateStaffDetails($user_id, $firstName, $lastName, $email, $phone, $address, $nic);

            if ($result) {
                Logger::info("updateStaffDetails success", ['staff_id' => $user_id]);
                $this->jsonResponse(["message" => "User updated successfully."]);
            } else {
                Logger::warning("updateStaffDetails failed - user not found", ['staff_id' => $user_id]);
                $this->jsonResponse(["message" => "User not found."], false);
            }
        } else {
            Logger::warning("updateStaffDetails invalid request method", ['method' => $_SERVER['REQUEST_METHOD']]);
            $this->jsonResponse(["message" => "Invalid request."], false);
        }
    }

    public function loadMailData()
    {

        if ($this->isPost()) {
            $user_id = $this->getPost('staff_id');

            $result = StaffModel::loadMailDetails($user_id);

            if ($result) {
                $mailData = $result->fetch_assoc();
                $this->jsonResponse([
                    "name" => $mailData['fname'] . " " . $mailData['lname'],
                    "email" => $mailData['email'],
                ]);
            } else {
                Logger::warning("loadMailData user not found", ['staff_id' => $user_id]);
                $this->jsonResponse(["message" => "User not found."], false);
            }
        } else {
            Logger::warning("loadMailData invalid request method", ['method' => $_SERVER['REQUEST_METHOD']]);
            $this->jsonResponse(["message" => "Invalid request."], false);
        }
    }

    public function sendMail()
    {
        require_once Config::getServicePath('emailService.php');

        if ($this->isPost()) {
            $name = $this->getPost('name');
            $email = $this->getPost('email');
            $subject = $this->getPost('subject');
            $msg = $this->getPost('message');

            $body = '
            <p style="font-size: 30px; color: black; font-weight: bold; text-align: center;">Update from Library Administration</p> 
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
                Logger::info("sendMail success", ['email' => $email]);
                $this->jsonResponse(["message" => "Email sent successfully."]);
            } else {
                Logger::error("sendMail failed", ['email' => $email]);
                $this->jsonResponse(["message" => "Failed to send email."], false);
            }
        } else {
            Logger::warning("sendMail invalid request method", ['method' => $_SERVER['REQUEST_METHOD']]);
            $this->jsonResponse(["message" => "Invalid Request"], false);
        }
    }

    public function deactivateStaff()
    {

        if ($this->isPost()) {
            $id = $this->getPost('staff_id');
            $name = $this->getPost('name');
            $email = $this->getPost('email');

            $result = StaffModel::deactivateStaff($id);

            if ($result) {
                Logger::info("deactivateStaff success", ['staff_id' => $id]);
                $this->sendDeactivateStaffEmail($name, $email);
                $this->jsonResponse(["message" => "Membership Deactivated"]);
            } else {
                Logger::warning("deactivateStaff failed - user not found", ['staff_id' => $id]);
                $this->jsonResponse(["message" => "User Not found."], false);
            }
        } else {
            Logger::warning("deactivateStaff invalid request method", ['method' => $_SERVER['REQUEST_METHOD']]);
            $this->jsonResponse(["message" => "Invalid request."], false);
        }
    }

    public function sendDeactivateStaffEmail($name, $email)
    {
        require_once Config::getServicePath('emailService.php');

        $subject = 'Staff Account Deactivated';

        $specificMessage = '
                <h4>Your library staff account has been deactivated by the administration.</h4>';

        $emailTemplate = new EmailTemplate();
        $body = $emailTemplate->getEmailBody($name, $specificMessage);

        $emailService = new EmailService();
        $emailSent = $emailService->sendEmail($email, $subject, $body);

        if ($emailSent) {
            Logger::info("sendDeactivateStaffEmail success", ['email' => $email]);
        } else {
            Logger::error("sendDeactivateStaffEmail failed", ['email' => $email]);
        }
    }

    public function activateStaff()
    {

        if ($this->isPost()) {
            $id = $this->getPost('staff_id');
            $name = $this->getPost('name');
            $email = $this->getPost('email');

            Logger::info("activateStaff inputs", compact('id', 'name', 'email'));

            $result = StaffModel::activateStaff($id);

            if ($result) {
                Logger::info("activateStaff success", ['staff_id' => $id]);
                $this->sendActivateStaffEmail($name, $email);
                $this->jsonResponse(["message" => "Staff Member Activated Again"]);
            } else {
                Logger::warning("activateStaff failed - user not found", ['staff_id' => $id]);
                $this->jsonResponse(["message" => "Staff Member not found."], false);
            }
        } else {
            Logger::warning("activateStaff invalid request method", ['method' => $_SERVER['REQUEST_METHOD']]);
            $this->jsonResponse(["message" => "Invalid request."], false);
        }
    }

    public function sendActivateStaffEmail($name, $email)
    {
        require_once Config::getServicePath('emailService.php');

        $subject = 'Staff Account Activated Again';

        $specificMessage = '
                <h4>Your library staff account has been activated again by the administration.</h4>';

        $emailTemplate = new EmailTemplate();
        $body = $emailTemplate->getEmailBody($name, $specificMessage);

        $emailService = new EmailService();
        $emailSent = $emailService->sendEmail($email, $subject, $body);

        if ($emailSent) {
            Logger::info("sendActivateStaffEmail success", ['email' => $email]);
        } else {
            Logger::error("sendActivateStaffEmail failed", ['email' => $email]);
        }
    }

    public function sendEnrollmentKey()
    {
        require_once Config::getServicePath('emailService.php');

        if ($this->isPost()) {
            $email = $this->getPost('email');
            $role = $this->getPost('role');

            // Check if the role is librarian and set role_id accordingly
            if ($role === 'Librarian') {
                $role_id = 1; // Librarian
            } else {
                $role_id = 2; // Other roles
            }

            // Send the enrollment key with the role_id
            $key = StaffModel::generateKey($email, $role_id);

            $subject = 'Staff Enrollment Key';

            $specificMessage = '<h4>Your enrollment key is <br> ' . $key . '</h4>';

            $emailTemplate = new EmailTemplate();
            $body = $emailTemplate->getEmailBody("Staff Member", $specificMessage);

            $emailService = new EmailService();
            $emailSent = $emailService->sendEmail($email, $subject, $body);

            if ($emailSent) {
                Logger::info("sendEnrollmentKey email sent", ['email' => $email, 'key' => $key]);
                $this->jsonResponse(["message" => "Enrollment Key Sent"]);
            } else {
                Logger::error("sendEnrollmentKey email failed", ['email' => $email]);
                $this->jsonResponse(["message" => "Failed to send enrollment key."], false);
            }
        } else {
            Logger::warning("sendEnrollmentKey invalid request method", ['method' => $_SERVER['REQUEST_METHOD']]);
            $this->jsonResponse(["message" => "Invalid request."], false);
        }
    }
}
