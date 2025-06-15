<?php

require_once __DIR__ . '/../../../main.php';

class HomeController extends Controller
{
    private $homeModel;

    public function __construct()
    {
        require_once Config::getModelPath('member', 'homemodel.php');
        $this->homeModel = new HomeModel();
    }

    public function loadOpeningHours()
    {

        if ($this->isPost()) {
            $result = HomeModel::getOpeningHours();

            if ($result) {
                $this->jsonResponse(["data" => $result]);
            } else {
                Logger::warning("No opening hours found");
                $this->jsonResponse(["message" => "No Opening hours found."], false);
            }
        } else {
            Logger::warning("Invalid request method for loadOpeningHours");
            $this->jsonResponse(["message" => "Invalid request."], false);
        }
    }

    public function loadNewsUpdates()
    {

        if ($this->isPost()) {
            $result = HomeModel::getNewsUpdates();

            if ($result) {
                $this->jsonResponse(["newsData" => $result]);
            } else {
                Logger::warning("No news found");
                $this->jsonResponse(["message" => "No News Found."], false);
            }
        } else {
            Logger::warning("Invalid request method for loadNewsUpdates");
            $this->jsonResponse(["message" => "Invalid request."], false);
        }
    }

    public function serveNewsImage()
    {
        $imageName = $this->getGet('image', '');

        $basePath = Config::getNewsImagePath();
        $filePath = realpath($basePath . basename($imageName));

        if ($filePath && strpos($filePath, realpath($basePath)) === 0 && file_exists($filePath)) {
            header('Content-Type: ' . mime_content_type($filePath));
            readfile($filePath);
            exit;
        }

        Logger::error("News image not found", ['requested_image' => $imageName]);
        http_response_code(404);
        echo "Image not found.";
        exit;
    }

    public function getLibraryInfo()
    {

        if ($this->isPost()) {
            $libraryData = HomeModel::getLibraryInfo();

            if (!empty($libraryData)) {
                $this->jsonResponse(["libraryData" => $libraryData]);
            } else {
                Logger::warning("No library information found");
                $this->jsonResponse(["message" => "No information found."], false);
            }
        } else {
            Logger::warning("Invalid request method for getLibraryInfo");
            $this->jsonResponse(["message" => "Invalid request method."], false);
        }
    }

    public function sendEmailtoLibrary()
    {

        require_once Config::getServicePath('emailService.php');

        if ($this->isPost()) {
            $name = $this->getPost('name');
            $email = $this->getPost('email');
            $msg = $this->getPost('msg');

            Logger::info("Contact form data received", ['name' => $name, 'email' => $email]);

            $libraryData = HomeModel::getLibraryInfo();
            $libraryEmail = $libraryData['email'] ?? 'default@example.com';

            $subject = "Contact Us Message";
            $body = '
                <h3 style="text-align: center;">You have received a new message through the Contact Us form on your website.</h3> 
                <div style="max-width: 600px; margin: 0 auto; padding: 20px; text-align: left;">
                    <p style ="font-weight: bold;">Details:</p>
                    <p>User Name: ' . htmlspecialchars($name) . '</p>
                    <p>User Email: ' . htmlspecialchars($email) . '</p>
                    <p>Message:<br><span style="font-weight: bold;">' . nl2br(htmlspecialchars($msg)) . '</span></p>
                    <p>Please review the details and take the necessary action.</p>
                </div>';

            $emailService = new EmailService();
            $emailSent = $emailService->sendEmail($libraryEmail, $subject, $body, $email, $name);

            if ($emailSent) {
                Logger::info("Email sent successfully", ['to' => $libraryEmail]);
                $this->jsonResponse(["message" => "Email sent successfully!"]);
            } else {
                Logger::error("Failed to send email", ['to' => $libraryEmail]);
                $this->jsonResponse(["message" => "Failed to send email."], false);
            }
        } else {
            Logger::warning("Invalid request method for sendEmailtoLibrary");
            $this->jsonResponse(["message" => "Invalid Request"], false);
        }
    }

    public function loadTopBooks()
    {

        if ($this->isPost()) {
            $topBooksResult = HomeModel::getTopBooks();
            $topbooks = [];

            if ($topBooksResult) {
                while ($row = $topBooksResult->fetch_assoc()) {
                    $topbooks[] = $row;
                }
            }

            if (!empty($topbooks)) {
                // Return top books with type
                $this->jsonResponse([
                    "books" => $topbooks,
                    "type" => "top"
                ]);
            } else {
                // Fallback to latest arrivals
                $latestBooksResult = HomeModel::getLatestArrivals();
                $latestBooks = [];

                if ($latestBooksResult) {
                    while ($row = $latestBooksResult->fetch_assoc()) {
                        $latestBooks[] = $row;
                    }
                }

                $this->jsonResponse([
                    "books" => $latestBooks,
                    "type" => "latest"
                ]);
            }
        } else {
            Logger::warning("Invalid request method for loadTopBooks");
            $this->jsonResponse(["message" => "Invalid request method."], false);
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

        Logger::error("Logo image not found", ['requested_image' => $imageName]);
        http_response_code(404);
        echo "Image not found.";
        exit;
    }
}
