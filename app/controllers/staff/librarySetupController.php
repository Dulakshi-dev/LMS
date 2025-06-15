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
            Logger::info("Request to change opening hours received.");

            $weekdaysfrom = $this->getPost('weekdayfrom');
            $weekdaysto = $this->getPost('weekdayto');
            $weekendsfrom = $this->getPost('weekendfrom');
            $weekendsto = $this->getPost('weekendto');
            $holidaysfrom = $this->getPost('holidayfrom');
            $holidaysto = $this->getPost('holidayto');

            $result = LibrarySetupModel::changeOpeningHours($weekdaysfrom, $weekdaysto, $weekendsfrom, $weekendsto, $holidaysfrom, $holidaysto);

            if ($result) {
                Logger::info("Opening hours changed successfully.");
            } else {
                Logger::error("Failed to change opening hours.");
            }

            $this->jsonResponse(["message" => $result ? "Opening Hours Changed" : "Failed to Change Opening Hours."], $result);
        }
    }

    public function changeNewsUpdates()
    {
        if ($this->isPost()) {
            Logger::info("Request to change news updates received.");

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

            if (file_exists($targetFilePath)) {
                unlink($targetFilePath);
                Logger::info("Deleted existing news image: $fileName");
            }

            if (move_uploaded_file($receipt["tmp_name"], $targetFilePath)) {
                Logger::info("Uploaded new news image: $fileName");

                $result = LibrarySetupModel::changeNewsUpdates($boxId, $title, $date, $description, $fileName);

                if ($result) {
                    Logger::info("News updated successfully.");
                } else {
                    Logger::error("Failed to update news in database.");
                }

                $this->jsonResponse(["message" => $result ? "News Updated" : "Failed to update news"], $result);
            } else {
                Logger::error("Failed to upload news image.");
                $this->jsonResponse(["message" => "Image upload failed"], false);
            }
        }
    }

    public function changeLibraryInfo()
    {
        if ($this->isPost()) {
            Logger::info("Request to change library information received.");

            $name = $this->getPost('name');
            $address = $this->getPost('address');
            $email = $this->getPost('email');
            $phone = $this->getPost('phone');
            $fee = $this->getPost('fee');
            $fine = $this->getPost('fine');
            $fileName = '';

            $targetDir = Config::getLogoPath();

            if (!empty($_FILES['logo']['name'])) {
                $logo = $_FILES['logo'];
                $fileExtension = pathinfo($logo["name"], PATHINFO_EXTENSION);
                $fileName = "logo." . $fileExtension;
                $targetFilePath = $targetDir . $fileName;

                foreach (glob($targetDir . "logo.*") as $existingFile) {
                    unlink($existingFile);
                    Logger::info("Deleted existing logo file: " . basename($existingFile));
                }

                if (!move_uploaded_file($logo["tmp_name"], $targetFilePath)) {
                    Logger::error("Failed to upload new logo.");
                    $this->jsonResponse(["message" => "Failed to upload new logo."], false);
                    return;
                }
                Logger::info("New logo uploaded: $fileName");
            } else {
                $fileName = $this->getPost('currentLogo');
                Logger::info("No new logo uploaded, keeping existing logo: $fileName");
            }

            $result = LibrarySetupModel::changeLibraryInfo($name, $address, $email, $phone, $fee, $fileName, $fine);

            if ($result) {
                Logger::info("Library information updated successfully.");
            } else {
                Logger::error("Failed to update library information.");
            }

            $this->jsonResponse(["message" => $result ? "Library Information Changed" : "Failed to update Library Information."], $result);
        }
    }

    public function sendMailsToAllStaff()
    {
        if ($this->isPost()) {
            Logger::info("Request to send emails to all staff.");

            $subject = $this->getPost('subject');
            $specificMessage = $this->getPost('message');

            $result = LibrarySetupModel::getAllActiveStaff();

            if ($result) {
                $emailService = new EmailService();
                $emailTemplate = new EmailTemplate();

                $failures = 0;

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

    public function sendMailsToAllMembers()
    {
        if ($this->isPost()) {
            Logger::info("Request to send emails to all members.");

            $subject = $this->getPost('subject');
            $specificMessage = $this->getPost('message');

            $result = LibrarySetupModel::getAllActiveMembers();

            if ($result) {
                $emailService = new EmailService();
                $emailTemplate = new EmailTemplate();

                $failures = 0;

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

    public function serveLogo()
    {
        $imageName = $this->getGet('image', '');
        $basePath = Config::getLogoPath();
        $filePath = realpath($basePath . basename($imageName));

        if ($filePath && strpos($filePath, realpath($basePath)) === 0 && file_exists($filePath)) {
            Logger::info("Serving logo image: $imageName");
            header('Content-Type: ' . mime_content_type($filePath));
            readfile($filePath);
            exit;
        }

        Logger::error("Logo image not found: $imageName");
        http_response_code(404);
        echo "Image not found.";
        exit;
    }
}
