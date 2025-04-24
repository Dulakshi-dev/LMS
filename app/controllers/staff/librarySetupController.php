<?php

require_once __DIR__ . '/../../../main.php';

class LibrarySetupController extends Controller
{
    private $librarySetupModel;

    public function __construct()
    {
        require_once Config::getModelPath('staff', 'librarysetupmodel.php');
        $this->librarySetupModel = new LibrarySetupModel();
    }

    public function changeOpeningHours()
    {
        if ($this->isPost()) {
            $weekdaysfrom = $this->getPost('weekdayfrom');
            $weekdaysto = $this->getPost('weekdayto');
            $weekendsfrom = $this->getPost('weekendfrom');
            $weekendsto = $this->getPost('weekendto');
            $holidaysfrom = $this->getPost('holidayfrom');
            $holidaysto = $this->getPost('holidayto');

            $result = LibrarySetupModel::changeOpeningHours($weekdaysfrom, $weekdaysto, $weekendsfrom, $weekendsto, $holidaysfrom, $holidaysto);

            $this->jsonResponse(["message" => $result ? "Opening Hours Changed" : "Failed to Change Opening Hours."], $result);
        }
    }

    public function changeNewsUpdates()
    {
        if ($this->isPost()) {
            $boxSelection = $this->getPost('boxSelection');
            $title = $this->getPost('title');
            $date = $this->getPost('date');
            $description = $this->getPost('description');
            $receipt = $_FILES['image'];
            $targetDir = Config::getNewsImagePath(); 

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

            // Move the uploaded file
            if (move_uploaded_file($receipt["tmp_name"], $targetFilePath)) {
                $result = LibrarySetupModel::changeNewsUpdates($boxId, $title, $date, $description, $fileName);
                $this->jsonResponse(["message" => $result ? "News Updated" : "Failed to update news"], $result);
            } else {
                $this->jsonResponse(["message" => "Image upload failed"], false);
            }
        }
    }

    public function changeLibraryInfo()
    {
        if ($this->isPost()) {
            $name = $this->getPost('name');
            $address = $this->getPost('address');
            $email = $this->getPost('email');
            $phone = $this->getPost('phone');
            $fee = $this->getPost('fee');
            $fine = $this->getPost('fine');
            $fileName = '';

            $targetDir = Config::getLogoPath();

            // Check if a new logo is uploaded
            if (!empty($_FILES['logo']['name'])) {
                $logo = $_FILES['logo'];
                $fileExtension = pathinfo($logo["name"], PATHINFO_EXTENSION);
                $fileName = "logo." . $fileExtension;
                $targetFilePath = $targetDir . $fileName;
            
                // Delete existing logo files
                foreach (glob($targetDir . "logo.*") as $existingFile) {
                    unlink($existingFile);
                }
            
                // Move the new logo to the target directory
                if (!move_uploaded_file($logo["tmp_name"], $targetFilePath)) {
                    $this->jsonResponse(["message" => "Failed to upload new logo."], false);
                    return;
                }
            } else {
                // No new logo selected, keep the existing one
                $fileName = $this->getPost('currentLogo');
            }

            $result = LibrarySetupModel::changeLibraryInfo($name, $address, $email, $phone, $fee, $fileName, $fine);
            $this->jsonResponse(["message" => $result ? "Library Information Changed" : "Failed to update Library Information."], $result);
        }
    }

    public function sendMailsToAllStaff()
    {
        if ($this->isPost()) {
            $subject = $this->getPost('subject');
            $specificMessage = $this->getPost('message');
    
            $result = LibrarySetupModel::getAllActiveStaff();
    
            if ($result) {
                $emailService = new EmailService();
                $emailTemplate = new EmailTemplate();
                
                $failures = 0; // Count failed emails
    
                while ($row = $result->fetch_assoc()) {
                    $email = $row['email'];
                    $body = $emailTemplate->getEmailBody("Staff Member", $specificMessage);
                    
                    $emailSent = $emailService->sendEmail($email, $subject, $body);
    
                    if (!$emailSent) {
                        $failures++;
                    }
                }
    
                if ($failures === 0) {
                    $this->jsonResponse(["message" => "Emails sent successfully to all staff members."], true);
                } else {
                    $this->jsonResponse([
                        "message" => "Some emails failed to send.",
                        "failed_count" => $failures
                    ], false);
                }
            } else {
                $this->jsonResponse(["message" => "No staff members can be found"], false);
            }
        }
    }


    public function sendMailsToAllMembers()
    {
        if ($this->isPost()) {
            $subject = $this->getPost('subject');
            $specificMessage = $this->getPost('message');
    
            $result = LibrarySetupModel::getAllActiveMembers();
    
            if ($result) {
                $emailService = new EmailService();
                $emailTemplate = new EmailTemplate();
                
                $failures = 0; // Count failed emails
    
                while ($row = $result->fetch_assoc()) {
                    $email = $row['email'];
                    $body = $emailTemplate->getEmailBody("Member", $specificMessage);
                    $emailSent = $emailService->sendEmail($email, $subject, $body);
    
                    if (!$emailSent) {
                        $failures++;
                    }
                }
    
                if ($failures === 0) {
                    $this->jsonResponse(["message" => "Emails sent successfully to all members."], true);
                } else {
                    $this->jsonResponse([
                        "message" => "Some emails failed to send.",
                        "failed_count" => $failures
                    ], false);
                }
            } else {
                $this->jsonResponse(["message" => "No members can be found"], false);
            }
        }
    } 


    public function loadOpeningHours()
    {
        if ($this->isPost()) {
            $result = LibrarySetupModel::getOpeningHours();
            $this->jsonResponse(["data" => $result], $result !== false);
        }
    }

    public function getLibraryInfo()
    {
        if ($this->isPost()) {
            $libraryData = LibrarySetupModel::getLibraryInfo();
            $this->jsonResponse(["libraryData" => $libraryData], !empty($libraryData));
        }
    }

    public function serveLogo()
    {
        $imageName = $this->getGet('image', '');
        $basePath = Config::getLogoPath();
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

