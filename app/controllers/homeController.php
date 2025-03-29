<?php

require_once __DIR__ . '/../../main.php';

class HomeController
{

    private $homeModel;

    public function __construct()
    {
        require_once Config::getModelPath('homemodel.php');
        $this->homeModel = new HomeModel();
    }

    public function loadOpeningHours()
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $result = HomeModel::getOpeningHours();

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

    public function loadNewsUpdates()
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $result = HomeModel::getNewsUpdates();

            if ($result) {
                echo json_encode([
                    "success" => true,
                    "newsData" => $result
                ]);
            } else {
                echo json_encode(["success" => false, "message" => "No News Found."]);
            }
        } else {
            echo json_encode(["success" => false, "message" => "Invalid request."]);
        }
    }

    public function serveNewsImage()
    {
        $imageName = $_GET['image'] ?? '';

        $basePath = Config::getNewsImagePath();;
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

    public function getLibraryInfo()
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $libraryData = HomeModel::getLibraryInfo();

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

    public function sendEmailtoLibrary()
    {
        require_once Config::getServicePath('emailService.php');

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $name = $_POST['name'];
            $email = $_POST['email'];
            $msg = $_POST['msg'];

            // Fetch the library email from the database
            $libraryData = HomeModel::getLibraryInfo(); // Assuming this fetches the library info
            $libraryEmail = $libraryData['email']; // Use a default email if not found

            $subject = 'SHELF LOOM - Contact Us';
            $body = '<h1 style="padding-top: 30px;">Shelf Loom</h1>
                <p style="font-size: 30px; color: black; font-weight: bold; text-align: center;">User Wants to contact the library!</p> 
                <div style="max-width: 600px; margin: 0 auto; padding: 20px; text-align: left;">
                    <p>User Name: ' . htmlspecialchars($name) . '</p>
                    <p>User Email: ' . htmlspecialchars($email) . '</p>
                    <p>Message: ' . nl2br(htmlspecialchars($msg)) . '</p>
                    <div style="margin-top: 20px;">
                        <p>Best regards,</p>
                        <p>Shelf Loom Team</p>
                    </div>
                </div>';

            // Send email using retrieved email
            $emailService = new EmailService();
            $emailSent = $emailService->sendEmail($libraryEmail, $subject, $body);

            if ($emailSent) {
                echo json_encode(["success" => true, "message" => "Email sent successfully!"]);
            } else {
                echo json_encode(["success" => false, "message" => "Failed to send email."]);
            }
        } else {
            echo json_encode(["success" => false, "message" => "Invalid Request"]);
        }
    }

    public function loadTopBooks()
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $topBooksResult = HomeModel::getTopBooks();

            if (!$topBooksResult) {
                echo json_encode(["success" => false, "message" => "Failed to fetch books."]);
                return;
            }

            $topbooks = [];
            while ($row = $topBooksResult->fetch_assoc()) {
                $topbooks[] = $row;
            }

            echo json_encode([
                "success" => true,
                "books" => $topbooks
            ]);
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
