<?php
require_once __DIR__ . '/../../main.php';

class MemberProfileController
{

    private $profileModel;

    public function __construct()
    {
        require_once Config::getModelPath('memberprofilemodel.php');
        $this->profileModel = new MemberProfileModel();
    }

    public function loadMemberDetails()
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $member_id = $_POST['member_id'];

            $result = MemberProfileModel::loadMemberDetails($member_id);

            if ($result) {
                $userData = $result->fetch_assoc();
                echo json_encode([
                    "success" => true,
                    "id" => $userData['id'],
                    "member_id" => $userData['member_id'],
                    "nic" => $userData['nic'],
                    "fname" => $userData['fname'],
                    "lname" => $userData['lname'],
                    "email" => $userData['email'],
                    "mobile" => $userData['mobile'],
                    "address" => $userData['address'],
                    "profile_img" =>$userData['profile_img']
                ]);
            } else {
                echo json_encode(["success" => false, "message" => "User not found."]);
            }
        } else {
            echo json_encode(["success" => false, "message" => "Invalid request."]);
        }
    }

    public function serveProfileImage() {
        $imageName = $_GET['image'] ?? '';
        $userID = 
        
        $basePath = Config::getMemberProfileImagePath();;
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
            $mobile = $_POST["phone"];
            $address = $_POST["address"];
    
            $fileName = ''; 
    
            if (isset($_FILES['profimg']) && $_FILES['profimg']['error'] === UPLOAD_ERR_OK) {
                $img = $_FILES['profimg'];
                $targetDir = Config::getMemberProfileImagePath();
                $fileName = uniqid() . "_" . basename($img["name"]);
                $targetFilePath = $targetDir . $fileName;
    
              $currentImage = MemberProfileModel::getMemberCurrentProfileImage($nic); 
    
                if ($currentImage && file_exists($targetDir . $currentImage)) {
                    unlink($targetDir . $currentImage);
                }
    
                if (move_uploaded_file($img["tmp_name"], $targetFilePath)) {
                    $result = MemberProfileModel::updateMemberDetails($nic, $fname, $lname, $address, $mobile, $fileName);
                    $_SESSION["profile_img"] = $fileName;
    
                    if ($result) {
                        echo json_encode(["success" => true, "message" => "User updated successfully."]);
                    } else {
                        echo json_encode(["success" => false, "message" => "User not found."]);
                    }
                } else {
                    echo json_encode(["success" => false, "message" => "Error moving uploaded file."]);
                }
            } else {
                $result = MemberProfileModel::updateMemberDetailsWithoutImage($nic, $fname, $lname, $address, $mobile);
    
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

    public function resetPassword()
    {
        
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $member_id = $_POST['member_id'];
            $newpassword = $_POST['newpassword'];
            $result = MemberProfileModel::resetPassword($member_id, $newpassword);
            
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
            $member_id = $_POST['member_id'];
            $currentpassword = $_POST['currentpassword'];
            $result = MemberProfileModel::validateCurrentPassword($member_id,$currentpassword);
            
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