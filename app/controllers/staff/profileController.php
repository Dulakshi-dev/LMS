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
                }

                if (move_uploaded_file($receipt["tmp_name"], $targetFilePath)) {
                    $result = ProfileModel::updateUserDetails($nic, $fname, $lname, $address, $mobile, $fileName);
                    $_SESSION["staff"]["profile_img"] = $fileName;

                    $this->jsonResponse(
                        ["message" => "Profile updated successfully."],
                        $result
                    );
                } else {
                    $this->jsonResponse(["message" => "Error moving uploaded file."], false);
                }
            } else {
                $result = ProfileModel::updateUserDetailsWithoutImage($nic, $fname, $lname, $address, $mobile);
                $this->jsonResponse(
                    ["message" => "Profile updated successfully."],
                    $result
                );
            }
        } else {
            $this->jsonResponse(["message" => "Invalid request."], false);
        }
    }

    public function resetPassword()
    {
        if ($this->isPost()) {
            $user_id = $this->getPost('user_id');
            $newpassword = $this->getPost('newpassword');
            $result = ProfileModel::resetPassword($user_id, $newpassword);

            $this->jsonResponse(
                ["message" => $result ? "Password Updated" : "Incorrect Password"],
                $result
            );
        } else {
            $this->jsonResponse(["message" => "Invalid request."], false);
        }
    }

    public function validateCurrentPassword()
    {
        if ($this->isPost()) {
            $user_id = $this->getPost('user_id');
            $currentpassword = $this->getPost('currentpassword');
            $result = ProfileModel::validateCurrentPassword($user_id, $currentpassword);

            $this->jsonResponse(
                ["message" => $result ? "Correct Password" : "Incorrect Password"],
                $result
            );
        } else {
            $this->jsonResponse(["message" => "Invalid request."], false);
        }
    }
}
