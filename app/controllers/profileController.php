<?php
require_once __DIR__ . '/../../main.php';

class ProfileController
{

    private $profileModel;

    public function __construct()
    {
        require_once Config::getModelPath('profilemodel.php');
        $this->profileModel = new UserModel();
    }

    public function serveProfileImage() {
        $imageName = $_GET['image'] ?? '';
        $userID = 
        
        $basePath = Config::getProfileImagePath();;
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
                $targetDir = Config::getProfileImagePath();
                $fileName = uniqid() . "_" . basename($receipt["name"]);
                $targetFilePath = $targetDir . $fileName;
    
              $currentImage = ProfileModel::getUserCurrentProfileImage($nic); 
    
                if ($currentImage && file_exists($targetDir . $currentImage)) {
                    unlink($targetDir . $currentImage);
                }
    
                if (move_uploaded_file($receipt["tmp_name"], $targetFilePath)) {
                    $result = ProfileModel::updateUserDetails($nic, $fname, $lname, $address, $mobile, $fileName);
                    $_SESSION["user"]["profile_img"] = $fileName;
    
                    if ($result) {
                        echo json_encode(["success" => true, "message" => "User updated successfully."]);
                    } else {
                        echo json_encode(["success" => false, "message" => "User not found."]);
                    }
                } else {
                    echo json_encode(["success" => false, "message" => "Error moving uploaded file."]);
                }
            } else {
                $result = ProfileModel::updateUserDetailsWithoutImage($nic, $fname, $lname, $address, $mobile);
    
                if ($result) {
                    echo json_encode(["success" => true, "message" => "User updated successfully."]);
                } else {
                    echo json_encode(["success" => false, "message" => "User not found."]);
                }
            }
        } else {
            echo json_encode(["success" => false, "message" => "Invalid request."]);
        }
    }
} 