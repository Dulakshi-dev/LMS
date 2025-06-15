<?php

class ProfileController extends Controller
{
    private $profileModel;

    public function __construct()
    {
        require_once Config::getModelPath('staff', 'profilemodel.php');
        $this->profileModel = new ProfileModel();
    }

    public function serveProfileImage()
    {
        $imageName = $this->getGet('image', '');
        $basePath = Config::getStaffProfileImagePath();
        $filePath = realpath($basePath . basename($imageName));

        if ($filePath && strpos($filePath, realpath($basePath)) === 0 && file_exists($filePath)) {
            
            header('Content-Type: ' . mime_content_type($filePath));
            readfile($filePath);
            exit;
        }

        Logger::warning("Profile image not found", ['image' => $imageName, 'filePath' => $filePath]);
        $this->jsonResponse(["message" => "Image not found."], false, 404);
    }

    public function updateProfile()
    {
        if ($this->isPost()) {
            $nic = $this->getPost('nic');
            $fname = $this->getPost('fname');
            $lname = $this->getPost('lname');
            $mobile = $this->getPost('mobile');
            $address = $this->getPost('address');

            $fileName = '';

            if (isset($_FILES['profimg']) && $_FILES['profimg']['error'] === UPLOAD_ERR_OK) {
                $receipt = $_FILES['profimg'];
                $targetDir = Config::getStaffProfileImagePath();
                $fileName = uniqid() . "_" . basename($receipt["name"]);
                $targetFilePath = $targetDir . $fileName;

                $currentImage = ProfileModel::getUserCurrentProfileImage($nic);

                if ($currentImage && file_exists($targetDir . $currentImage)) {
                    unlink($targetDir . $currentImage);
                    Logger::info("Deleted old profile image", ['nic' => $nic, 'oldImage' => $currentImage]);
                }

                if (move_uploaded_file($receipt["tmp_name"], $targetFilePath)) {
                    $result = ProfileModel::updateUserDetails($nic, $fname, $lname, $address, $mobile, $fileName);
                    if ($result) {
                        $_SESSION["staff"]["profile_img"] = $fileName;
                        Logger::info("Profile updated with new image", ['nic' => $nic, 'newImage' => $fileName]);
                    } else {
                        Logger::error("Failed to update profile details after image upload", ['nic' => $nic]);
                    }

                    $this->jsonResponse(
                        ["message" => "Profile updated successfully."],
                        $result
                    );
                } else {
                    Logger::error("Failed to move uploaded profile image", ['nic' => $nic, 'fileName' => $fileName]);
                    $this->jsonResponse(["message" => "Error moving uploaded file."], false);
                }
            } else {
                $result = ProfileModel::updateUserDetailsWithoutImage($nic, $fname, $lname, $address, $mobile);
                if ($result) {
                    Logger::info("Profile updated without image change", ['nic' => $nic]);
                } else {
                    Logger::error("Failed to update profile without image", ['nic' => $nic]);
                }

                $this->jsonResponse(
                    ["message" => "Profile updated successfully."],
                    $result
                );
            }
        } else {
            Logger::warning("Invalid request to updateProfile: not a POST");
            $this->jsonResponse(["message" => "Invalid request."], false);
        }
    }

    public function resetPassword()
    {
        if ($this->isPost()) {
            $user_id = $this->getPost('user_id');
            $newpassword = $this->getPost('newpassword');
            $result = ProfileModel::resetPassword($user_id, $newpassword);

            if ($result) {
                Logger::info("Password reset successful", ['user_id' => $user_id]);
            } else {
                Logger::warning("Password reset failed or incorrect", ['user_id' => $user_id]);
            }

            $this->jsonResponse(
                ["message" => $result ? "Password Updated" : "Incorrect Password"],
                $result
            );
        } else {
            Logger::warning("Invalid request to resetPassword: not a POST");
            $this->jsonResponse(["message" => "Invalid request."], false);
        }
    }

    public function validateCurrentPassword()
    {
        if ($this->isPost()) {
            $user_id = $this->getPost('user_id');
            $currentpassword = $this->getPost('currentpassword');
            $result = ProfileModel::validateCurrentPassword($user_id, $currentpassword);

            if ($result) {
                Logger::info("Current password validated", ['user_id' => $user_id]);
            } else {
                Logger::warning("Current password validation failed", ['user_id' => $user_id]);
            }

            $this->jsonResponse(
                ["message" => $result ? "Correct Password" : "Incorrect Password"],
                $result
            );
        } else {
            Logger::warning("Invalid request to validateCurrentPassword: not a POST");
            $this->jsonResponse(["message" => "Invalid request."], false);
        }
    }
}
