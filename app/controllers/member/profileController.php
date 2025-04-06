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
        if ($this->isPost()) {
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
                $this->jsonResponse(["message" => "User not found."], false);
            }
        } else {
            $this->jsonResponse(["message" => "Invalid request."], false);
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

        http_response_code(404);
        echo "Image not found.";
        exit;
    }

    public function updateProfile()
    {
        if ($this->isPost()) {
            $nic = $this->getPost('nic');
            $fname = $this->getPost('fname');
            $lname = $this->getPost('lname');
            $mobile = $this->getPost('phone');
            $address = $this->getPost('address');

            $fileName = '';

            if (isset($_FILES['profimg']) && $_FILES['profimg']['error'] === UPLOAD_ERR_OK) {
                $img = $_FILES['profimg'];
                $targetDir = Config::getMemberProfileImagePath();
                $fileName = uniqid() . "_" . basename($img["name"]);
                $targetFilePath = $targetDir . $fileName;

                $currentImage = ProfileModel::getMemberCurrentProfileImage($nic);

                if ($currentImage && file_exists($targetDir . $currentImage)) {
                    unlink($targetDir . $currentImage);
                }

                if (move_uploaded_file($img["tmp_name"], $targetFilePath)) {
                    $result = ProfileModel::updateMemberDetails($nic, $fname, $lname, $address, $mobile, $fileName);
                    $_SESSION["member"]["profile_img"] = $fileName;

                    if ($result) {
                        $this->jsonResponse(["message" => "User updated successfully."]);
                    } else {
                        $this->jsonResponse(["message" => "User not found."], false);
                    }
                } else {
                    $this->jsonResponse(["message" => "Error moving uploaded file."], false);
                }
            } else {
                $result = ProfileModel::updateMemberDetailsWithoutImage($nic, $fname, $lname, $address, $mobile);

                if ($result) {
                    $this->jsonResponse(["message" => "User updated successfully."]);
                } else {
                    $this->jsonResponse(["message" => "User not found."], false);
                }
            }
        } else {
            $this->jsonResponse(["message" => "Invalid request."], false);
        }
    }

    public function resetPassword()
    {
        if ($this->isPost()) {
            $member_id = $this->getPost('member_id');
            $newpassword = $this->getPost('newpassword');

            $result = ProfileModel::resetPassword($member_id, $newpassword);

            if ($result) {
                $this->jsonResponse(["message" => "Password Updated"]);
            } else {
                $this->jsonResponse(["message" => "Incorrect Password"], false);
            }
        } else {
            $this->jsonResponse(["message" => "Invalid request."], false);
        }
    }

    public function validateCurrentPassword()
    {
        if ($this->isPost()) {
            $member_id = $this->getPost('member_id');
            $currentpassword = $this->getPost('currentpassword');

            $result = ProfileModel::validateCurrentPassword($member_id, $currentpassword);

            if ($result) {
                $this->jsonResponse(["message" => "Correct Password"]);
            } else {
                $this->jsonResponse(["message" => "Incorrect Password"], false);
            }
        } else {
            $this->jsonResponse(["message" => "Invalid request."], false);
        }
    }
}
