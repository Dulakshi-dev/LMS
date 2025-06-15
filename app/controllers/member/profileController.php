<?php
require_once __DIR__ . '/../../../main.php';

class ProfileController extends Controller
{
    private $profileModel;

    public function __construct()
    {
        require_once Config::getModelPath('member', 'profilemodel.php');
        $this->profileModel = new ProfileModel();
    }

    public function loadMemberDetails()
    {
        if (!$this->isPost()) {
            Logger::warning('Invalid request method for loadMemberDetails', ['method' => $_SERVER['REQUEST_METHOD']]);
            $this->jsonResponse(["message" => "Invalid request."], false);
            return;
        }

        $member_id = $this->getPost('member_id');

        $result = ProfileModel::loadMemberDetails($member_id);

        if ($result && $userData = $result->fetch_assoc()) {
            $this->jsonResponse([
                "id" => $userData['id'],
                "member_id" => $userData['member_id'],
                "nic" => $userData['nic'],
                "fname" => $userData['fname'],
                "lname" => $userData['lname'],
                "email" => $userData['email'],
                "mobile" => $userData['mobile'],
                "address" => $userData['address'],
                "profile_img" => $userData['profile_img']
            ]);
        } else {
            Logger::warning('User not found in loadMemberDetails', ['member_id' => $member_id]);
            $this->jsonResponse(["message" => "User not found."], false);
        }
    }

    public function serveProfileImage()
    {
        $imageName = $this->getGet('image', '');
        $basePath = Config::getMemberProfileImagePath();
        $filePath = realpath($basePath . basename($imageName));

        if ($filePath && strpos($filePath, realpath($basePath)) === 0 && file_exists($filePath)) {
            header('Content-Type: ' . mime_content_type($filePath));
            readfile($filePath);
            exit;
        }

        Logger::warning('Profile image not found', ['image' => $imageName]);
        http_response_code(404);
        echo "Image not found.";
        exit;
    }

    public function updateProfile()
    {
        if (!$this->isPost()) {
            Logger::warning('Invalid request method for updateProfile', ['method' => $_SERVER['REQUEST_METHOD']]);
            $this->jsonResponse(["message" => "Invalid request."], false);
            return;
        }

        $nic = $this->getPost('nic');
        $fname = $this->getPost('fname');
        $lname = $this->getPost('lname');
        $mobile = $this->getPost('phone');
        $address = $this->getPost('address');

        Logger::info('Attempting to update profile', ['nic' => $nic, 'fname' => $fname, 'lname' => $lname]);

        $fileName = '';

        if (isset($_FILES['profimg']) && $_FILES['profimg']['error'] === UPLOAD_ERR_OK) {
            $img = $_FILES['profimg'];
            $targetDir = Config::getMemberProfileImagePath();
            $fileName = uniqid() . "_" . basename($img["name"]);
            $targetFilePath = $targetDir . $fileName;

            $currentImage = ProfileModel::getMemberCurrentProfileImage($nic);

            if ($currentImage && file_exists($targetDir . $currentImage)) {
                unlink($targetDir . $currentImage);
                Logger::info('Deleted old profile image', ['old_image' => $currentImage]);
            }

            if (move_uploaded_file($img["tmp_name"], $targetFilePath)) {
                $result = ProfileModel::updateMemberDetails($nic, $fname, $lname, $address, $mobile, $fileName);
                $_SESSION["member"]["profile_img"] = $fileName;

                if ($result) {
                    Logger::info('Profile updated successfully with new image', ['nic' => $nic, 'new_image' => $fileName]);
                    $this->jsonResponse(["message" => "User updated successfully."]);
                } else {
                    Logger::error('Failed to update profile after image upload', ['nic' => $nic]);
                    $this->jsonResponse(["message" => "User not found."], false);
                }
            } else {
                Logger::error('Error moving uploaded file for profile image', ['nic' => $nic]);
                $this->jsonResponse(["message" => "Error moving uploaded file."], false);
            }
        } else {
            $result = ProfileModel::updateMemberDetailsWithoutImage($nic, $fname, $lname, $address, $mobile);

            if ($result) {
                Logger::info('Profile updated successfully without image change', ['nic' => $nic]);
                $this->jsonResponse(["message" => "User updated successfully."]);
            } else {
                Logger::error('Failed to update profile without image change', ['nic' => $nic]);
                $this->jsonResponse(["message" => "User not found."], false);
            }
        }
    }

    public function resetPassword()
    {
        if (!$this->isPost()) {
            Logger::warning('Invalid request method for resetPassword', ['method' => $_SERVER['REQUEST_METHOD']]);
            $this->jsonResponse(["message" => "Invalid request."], false);
            return;
        }

        $member_id = $this->getPost('member_id');
        $newpassword = $this->getPost('newpassword');

        Logger::info('Reset password requested', ['member_id' => $member_id]);

        $result = ProfileModel::resetPassword($member_id, $newpassword);

        if ($result) {
            Logger::info('Password updated successfully', ['member_id' => $member_id]);
            $this->jsonResponse(["message" => "Password Updated"]);
        } else {
            Logger::warning('Failed to update password', ['member_id' => $member_id]);
            $this->jsonResponse(["message" => "Incorrect Password"], false);
        }
    }

    public function validateCurrentPassword()
    {
        if (!$this->isPost()) {
            Logger::warning('Invalid request method for validateCurrentPassword', ['method' => $_SERVER['REQUEST_METHOD']]);
            $this->jsonResponse(["message" => "Invalid request."], false);
            return;
        }

        $member_id = $this->getPost('member_id');
        $currentpassword = $this->getPost('currentpassword');

        Logger::info('Validate current password requested', ['member_id' => $member_id]);

        $result = ProfileModel::validateCurrentPassword($member_id, $currentpassword);

        if ($result) {
            Logger::info('Current password validation successful', ['member_id' => $member_id]);
            $this->jsonResponse(["message" => "Correct Password"]);
        } else {
            Logger::warning('Current password validation failed', ['member_id' => $member_id]);
            $this->jsonResponse(["message" => "Incorrect Password"], false);
        }
    }
}
