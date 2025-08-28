<?php

// Include the main configuration and base setup
require_once __DIR__ . '/../../../main.php';

// This controller handles settings related to the library (hours, info, news, emails, logo, etc.)
class LibrarySetupController extends Controller
{
    private $librarySetupModel;

    public function __construct()
    {
        // Load the LibrarySetupModel file (staff side)
        require_once Config::getModelPath('staff', 'librarysetupmodel.php');
        $this->librarySetupModel = new LibrarySetupModel();
    }

    // Change library opening hours (weekday, weekend, holiday)
    public function changeOpeningHours()
    {
        if ($this->isPost()) {
            Logger::info("Request to change opening hours received.");

            // Get opening hours from POST request
            $weekdaysfrom = $this->getPost('weekdayfrom');
            $weekdaysto = $this->getPost('weekdayto');
            $weekendsfrom = $this->getPost('weekendfrom');
            $weekendsto = $this->getPost('weekendto');
            $holidaysfrom = $this->getPost('holidayfrom');
            $holidaysto = $this->getPost('holidayto');

            // Update opening hours in database
            $result = LibrarySetupModel::changeOpeningHours($weekdaysfrom, $weekdaysto, $weekendsfrom, $weekendsto, $holidaysfrom, $holidaysto);

            // Log result and send response
            if ($result) {
                Logger::info("Opening hours changed successfully.");
            } else {
                Logger::error("Failed to change opening hours.");
            }

            $this->jsonResponse(["message" => $result ? "Opening Hours Changed" : "Failed to Change Opening Hours."], $result);
        }
    }

    // Change news updates on the system (with image upload)
    public function changeNewsUpdates()
    {
        if ($this->isPost()) {
            Logger::info("Request to change news updates received.");

            // Collect data from request
            $boxSelection = $this->getPost('boxSelection'); // which box (1,2,3)
            $title = $this->getPost('title');
            $date = $this->getPost('date');
            $description = $this->getPost('description');
            $receipt = $_FILES['image']; // uploaded image file
            $targetDir = Config::getNewsImagePath(); 

            $boxId = 0;
            $fileName = '';

            // Decide which box is being updated and prepare filename
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

            // If old image exists, delete it first
            if (file_exists($targetFilePath)) {
                unlink($targetFilePath);
                Logger::info("Deleted existing news image: $fileName");
            }

            // Save new image to target location
            if (move_uploaded_file($receipt["tmp_name"], $targetFilePath)) {
                Logger::info("Uploaded new news image: $fileName");

                // Save news update details into database
                $result = LibrarySetupModel::changeNewsUpdates($boxId, $title, $date, $description, $fileName);

                if ($result) {
                    Logger::info("News updated successfully.");
                } else {
                    Logger::error("Failed to update news in database.");
                }

                $this->jsonResponse(["message" => $result ? "News Updated" : "Failed to update news"], $result);
            } else {
                // Image upload failed
                Logger::error("Failed to upload news image.");
                $this->jsonResponse(["message" => "Image upload failed"], false);
            }
        }
    }

    // Change general library information (name, address, email, phone, fee, fine, logo)
    public function changeLibraryInfo()
    {
        if ($this->isPost()) {
            Logger::info("Request to change library information received.");

            // Get details from POST request
            $name = $this->getPost('name');
            $address = $this->getPost('address');
            $email = $this->getPost('email');
            $phone = $this->getPost('phone');
            $fee = $this->getPost('fee');
            $fine = $this->getPost('fine');
            $fileName = '';

            $targetDir = Config::getLogoPath();

            // If new logo uploaded, replace old logo
            if (!empty($_FILES['logo']['name'])) {
                $logo = $_FILES['logo'];
                $fileExtension = pathinfo($logo["name"], PATHINFO_EXTENSION);
                $fileName = "logo." . $fileExtension;
                $targetFilePath = $targetDir . $fileName;

                // Delete existing logo file if any
                foreach (glob($targetDir . "logo.*") as $existingFile) {
                    unlink($existingFile);
                    Logger::info("Deleted existing logo file: " . basename($existingFile));
                }

                // Save new logo
                if (!move_uploaded_file($logo["tmp_name"], $targetFilePath)) {
                    Logger::error("Failed to upload new logo.");
                    $this->jsonResponse(["message" => "Failed to upload new logo."], false);
                    return;
                }
                Logger::info("New logo uploaded: $fileName");
            } else {
                // No new logo, keep existing one
                $fileName = $this->getPost('currentLogo');
                Logger::info("No new logo uploaded, keeping existing logo: $fileName");
            }

            // Update library info in database
            $result = LibrarySetupModel::changeLibraryInfo($name, $address, $email, $phone, $fee, $fileName, $fine);

            if ($result) {
                Logger::info("Library information updated successfully.");
            } else {
                Logger::error("Failed to update library information.");
            }

            $this->jsonResponse(["message" => $result ? "Library Information Changed" : "Failed to update Library Information."], $result);
        }
    }

    // Send email messages to all staff members
    public function sendMailsToAllStaff()
    {
        if ($this->isPost()) {
            Logger::info("Request to send emails to all staff.");

            // Get email subject and message
            $subject = $this->getPost('subject');
            $specificMessage = $this->getPost('message');

            // Get all active staff members from database
            $result = LibrarySetupModel::getAllActiveStaff();

            if ($result) {
                $emailService = new EmailService();
                $emailTemplate = new EmailTemplate();

                $failures = 0;

                // Send email to each staff member
                while ($row = $result->fetch_assoc()) {
                    $email = $row['email'];
                    $body = $emailTemplate->getEmailBody("Staff Member", $specificMessage);

                    if (!$emailService->sendEmail($email, $subject, $body)) {
                        $failures++;
                        Logger::warning("Failed to send email to staff: $email");
                    } else {
                        Logger::info("Email sent to staff: $email");
                    }
                }

                // Check if all emails sent successfully
                if ($failures === 0) {
                    Logger::info("All staff emails sent successfully.");
                    $this->jsonResponse(["message" => "Emails sent successfully to all staff members."], true);
                } else {
                    Logger::error("Failed to send $failures staff emails.");
                    $this->jsonResponse([
                        "message" => "Some emails failed to send.",
                        "failed_count" => $failures
                    ], false);
                }
            } else {
                Logger::warning("No active staff found for emailing.");
                $this->jsonResponse(["message" => "No staff members can be found"], false);
            }
        }
    }

    // Send email messages to all members
    public function sendMailsToAllMembers()
    {
        if ($this->isPost()) {
            Logger::info("Request to send emails to all members.");

            // Get email subject and message
            $subject = $this->getPost('subject');
            $specificMessage = $this->getPost('message');

            // Get all active members from database
            $result = LibrarySetupModel::getAllActiveMembers();

            if ($result) {
                $emailService = new EmailService();
                $emailTemplate = new EmailTemplate();

                $failures = 0;

                // Send email to each member
                while ($row = $result->fetch_assoc()) {
                    $email = $row['email'];
                    $body = $emailTemplate->getEmailBody("Member", $specificMessage);

                    if (!$emailService->sendEmail($email, $subject, $body)) {
                        $failures++;
                        Logger::warning("Failed to send email to member: $email");
                    } else {
                        Logger::info("Email sent to member: $email");
                    }
                }

                // Check if all emails sent successfully
                if ($failures === 0) {
                    Logger::info("All member emails sent successfully.");
                    $this->jsonResponse(["message" => "Emails sent successfully to all members."], true);
                } else {
                    Logger::error("Failed to send $failures member emails.");
                    $this->jsonResponse([
                        "message" => "Some emails failed to send.",
                        "failed_count" => $failures
                    ], false);
                }
            } else {
                Logger::warning("No active members found for emailing.");
                $this->jsonResponse(["message" => "No members can be found"], false);
            }
        }
    }

    // Load opening hours from database
    public function loadOpeningHours()
    {
        if ($this->isPost()) {
            Logger::info("Loading opening hours.");

            $result = LibrarySetupModel::getOpeningHours();

            if ($result !== false) {
                Logger::info("Opening hours loaded successfully.");
            } else {
                Logger::error("Failed to load opening hours.");
            }

            $this->jsonResponse(["data" => $result], $result !== false);
        }
    }

    // Load library information from database
    public function getLibraryInfo()
    {
        if ($this->isPost()) {
            Logger::info("Loading library information.");

            $libraryData = LibrarySetupModel::getLibraryInfo();

            if (!empty($libraryData)) {
                Logger::info("Library information loaded successfully.");
            } else {
                Logger::error("Failed to load library information.");
            }

            $this->jsonResponse(["libraryData" => $libraryData], !empty($libraryData));
        }
    }

    // Serve (display) the library logo image when requested
// In serveLogo(), serveBookCover(), serveProfileImage(), etc.
public function serveLogo() {
    // CLEAR ALL OUTPUT BUFFERS FIRST
    while (ob_get_level()) {
        ob_end_clean();
    }
    
    $imageName = $this->getGet('image', '');
    $basePath = Config::getLogoPath();
    $filePath = realpath($basePath . basename($imageName));
    
    if ($filePath && file_exists($filePath)) {
        header('Content-Type: ' . mime_content_type($filePath));
        header('Content-Length: ' . filesize($filePath));
        readfile($filePath);
        exit;
    }
    
    http_response_code(404);
    echo "Image not found.";
    exit;
}
}
