<?php

require_once __DIR__ . '/../../main.php';

class LibrarySetupController
{

    private $librarySetupModel;

    public function __construct()
    {
        require_once Config::getModelPath('librarysetupmodel.php');
        $this->librarySetupModel = new LibrarySetupModel();
    }

    public static function changeOpeningHours()
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $weekdaysfrom = $_POST['weekdayfrom'];
            $weekdaysto = $_POST['weekdayto'];
            $weekendsfrom = $_POST['weekendfrom'];
            $weekendsto = $_POST['weekendto'];
            $holidaysfrom = $_POST['holidayfrom'];
            $holidaysto = $_POST['holidayto'];


            $result = LibrarySetupModel::changeOpeningHours($weekdaysfrom, $weekdaysto, $weekendsfrom, $weekendsto, $holidaysfrom, $holidaysto);

            if ($result) {
                echo json_encode(["success" => true, "message" => "Opening Hours Changed"]);
                exit();
            } else {
                echo json_encode(["success" => false, "message" => "Failed to Change Opening Hours."]);
            }
        }
    }

    public static function changeNewsUpdates()
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $boxSelection = $_POST['boxSelection'];
            $title = $_POST['title'];
            $date = $_POST['date'];
            $description = $_POST['description'];
    
            $receipt = $_FILES['image'];
            $targetDir = Config::getNewsImagePath(); 
    
            // Determine box ID and filename
            $boxId = 0;
            $fileName = '';
    
            if ($boxSelection === 'box1') {
                $boxId = 1;
                $fileName = "box1." . pathinfo($receipt["name"], PATHINFO_EXTENSION);
            } elseif ($boxSelection === 'box2') {
                $boxId = 2;
                $fileName = "box2." . pathinfo($receipt["name"], PATHINFO_EXTENSION);
            } elseif ($boxSelection === 'box3') {
                $boxId = 3;
                $fileName = "box3." . pathinfo($receipt["name"], PATHINFO_EXTENSION);
            }
    
            $targetFilePath = $targetDir . $fileName;
    
            // Check if a previous file exists and delete it
            if (file_exists($targetFilePath)) {
                unlink($targetFilePath);
            }
    
            // Move the uploaded file to the target directory with the fixed filename
            if (move_uploaded_file($receipt["tmp_name"], $targetFilePath)) {
                // Update news in database
                $result = LibrarySetupModel::changeNewsUpdates($boxId, $title, $date, $description, $fileName);
    
                if ($result) {
                    echo json_encode(["success" => true, "message" => "News Updated"]);
                } else {
                    echo json_encode(["success" => false, "message" => "Failed to update news"]);
                }
            } else {
                echo json_encode(["success" => false, "message" => "Image upload failed"]);
            }
            exit();
        }
    }

    
    public static function changeLibraryInfo()
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $name = $_POST['name'];
            $address = $_POST['address'];
            $email = $_POST['email'];
            $phone = $_POST['phone'];
            $fee = $_POST['fee'];
            $fine = $_POST['fine'];
            $fileName = '';

            $targetDir = Config::getLogoPath(); // Get the directory path for storing the logo
    
            // Check if a new logo is uploaded
            if (!empty($_FILES['logo']['name'])) {
                $logo = $_FILES['logo'];
                $fileExtension = pathinfo($logo["name"], PATHINFO_EXTENSION); // Get the file extension
                $fileName = "logo." . $fileExtension; // Save as logo.png, logo.jpg, etc.
                $targetFilePath = $targetDir . $fileName;
    
                // Delete any existing logo file in the directory (regardless of extension)
                foreach (glob($targetDir . "logo.*") as $existingFile) {
                    unlink($existingFile);
                }
    
                // Move the new uploaded file to the target directory
                if (!move_uploaded_file($logo["tmp_name"], $targetFilePath)) {
                    echo json_encode(["success" => false, "message" => "Failed to upload new logo."]);
                    exit();
                }
            }
    
            $result = LibrarySetupModel::changeLibraryInfo($name, $address, $email, $phone, $fee, $fileName, $fine);
    
            if ($result) {
                echo json_encode(["success" => true, "message" => "Library Information Changed"]);
            } else {
                echo json_encode(["success" => false, "message" => "Failed to update Library Information."]);
            }
        }
    }
    

    public static function sendMailsToAllStaff()
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $subject = $_POST['subject'];
            $message = $_POST['message'];


            $result = LibrarySetupModel::sendEmailToAllStaff($subject, $message);

            if ($result) {
                echo json_encode(["success" => true, "message" => "Mails Sent"]);
                exit();
            } else {
                echo json_encode(["success" => false, "message" => "Failed to send Mails."]);
            }
        }
    }
    
    public static function sendMailsToAllMembers()
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $subject = $_POST['subject'];
            $message = $_POST['message'];


            $result = LibrarySetupModel::sendEmailToAllMembers($subject, $message);

            if ($result) {
                echo json_encode(["success" => true, "message" => "Mails Sent"]);
                exit();
            } else {
                echo json_encode(["success" => false, "message" => "Filed to send Mails"]);
            }
        }
    }

    public function loadOpeningHours()
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $result = LibrarySetupModel::getOpeningHours();

            if ($result) {
                echo json_encode([
                    "success" => true,
                    "data" => $result
                ]);
            } else {
                echo json_encode(["success" => false, "message" => "No Opening hours found."]);
            }
        } else {
            echo json_encode(["success" => false, "message" => "Invalid request."]);
        }
    }

    public function getLibraryInfo()
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $libraryData = LibrarySetupModel::getLibraryInfo();

            if (!empty($libraryData)) {
                echo json_encode([
                    "success" => true,
                    "libraryData" => $libraryData
                ]);
            } else {
                echo json_encode(["success" => false, "message" => "No information found."]);
            }
        } else {
            echo json_encode(["success" => false, "message" => "Invalid request method."]);
        }
    }
    public function serveLogo()
    {
        $imageName = $_GET['image'] ?? '';

        $basePath = Config::getLogoPath();;
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
    
}
