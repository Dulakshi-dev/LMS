<?php
require_once __DIR__ . '/../../main.php';

class ProfileController
{

    private $profileModel;

    public function __construct()
    {
        require_once Config::getModelPath('profilemodel.php');
        $this->profileModel = new ProfileModel();
    }

    public function serveProfileImage() {
        $imageName = $_GET['image'] ?? '';
        $userID = 
        
        $basePath = Config::getStaffProfileImagePath();;
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
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    
            $nic = $_POST['nic'];
            $fname = $_POST['fname'];
            $lname = $_POST["lname"];
            $mobile = $_POST["mobile"];
            $address = $_POST["address"];
    
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
    
                    if ($result) {
                        echo json_encode(["success" => true, "message" => "Profile updated successfully."]);
                    } else {
                        echo json_encode(["success" => false, "message" => "User not found."]);
                    }
                } else {
                    echo json_encode(["success" => false, "message" => "Error moving uploaded file."]);
                }
            } else {
                $result = ProfileModel::updateUserDetailsWithoutImage($nic, $fname, $lname, $address, $mobile);
    
                if ($result) {
                    echo json_encode(["success" => true, "message" => "Profile updated successfully."]);
                } else {
                    echo json_encode(["success" => false, "message" => "User not found."]);
                }
            }
        } else {
            echo json_encode(["success" => false, "message" => "Invalid request."]);
        }
    }

    public function resetPassword()
    {
        
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $user_id = $_POST['user_id'];
            $newpassword = $_POST['newpassword'];
            $result = ProfileModel::resetPassword($user_id, $newpassword);
            
            if ($result) {
                echo json_encode(["success" => true, "message" => "Password Updated"]);
            } else {
                echo json_encode(["success" => false, "message" => "Incorrect Password"]);
            }

        } else {
            echo json_encode(["success" => false, "message" => "Invalid request."]);
        }
    }

    public function validateCurrentPassword()
    {
        
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $user_id = $_POST['user_id'];
            $currentpassword = $_POST['currentpassword'];
            $result = ProfileModel::validateCurrentPassword($user_id,$currentpassword);
            
            if ($result) {
                echo json_encode(["success" => true, "message" => "Correct Password"]);
            } else {
                echo json_encode(["success" => false, "message" => "Incorrect Password"]);
            }

        } else {
            echo json_encode(["success" => false, "message" => "Invalid request."]);
        }
    }
} 