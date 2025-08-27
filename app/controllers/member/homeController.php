<?php

require_once __DIR__ . '/../../../main.php'; // Include the main system setup file

class HomeController extends Controller
{
    private $homeModel;

    public function __construct()
    {   // Load the HomeModel (handles database queries for home page data)
        require_once Config::getModelPath('member', 'homemodel.php');
        $this->homeModel = new HomeModel();
    }

    public function loadOpeningHours()
    {

        if ($this->isPost()) { // Only allow POST requests
            $result = HomeModel::getOpeningHours();// Get library opening hours

            if ($result) {   
                $this->jsonResponse(["data" => $result]);// Send back opening hours as JSON
            } else {
                Logger::warning("No opening hours found");
                $this->jsonResponse(["message" => "No Opening hours found."], false);
            }
        } else { // Wrong request method → log + return error
            Logger::warning("Invalid request method for loadOpeningHours"); // If no opening hours found → log warning + return error
            $this->jsonResponse(["message" => "Invalid request."], false);
        }
    }

    public function loadNewsUpdates()
    {   
         // Only allow POST requests
        if ($this->isPost()) {
            $result = HomeModel::getNewsUpdates(); // Get news updates

            if ($result) { // Send back news updates as JSON
                $this->jsonResponse(["newsData" => $result]);
            } else { // If no news found → log warning + return error
                Logger::warning("No news found");
                $this->jsonResponse(["message" => "No News Found."], false);
            }
        } else {    
            Logger::warning("Invalid request method for loadNewsUpdates"); // Wrong request method → log + return error
            $this->jsonResponse(["message" => "Invalid request."], false);
        }
    }

    public function serveNewsImage()
    {
        $imageName = $this->getGet('image', ''); // Get image name from request

        $basePath = Config::getNewsImagePath(); // Build full file path to news images
        $filePath = realpath($basePath . basename($imageName));

        // Check if file exists and is inside the allowed folder
        if ($filePath && strpos($filePath, realpath($basePath)) === 0 && file_exists($filePath)) {
            header('Content-Type: ' . mime_content_type($filePath));
            readfile($filePath);
            exit;
        }
        // If file not found → log error and return 404
        Logger::error("News image not found", ['requested_image' => $imageName]);
        http_response_code(404);
        echo "Image not found.";
        exit;
    }

    public function getLibraryInfo()
    {

        if ($this->isPost()) { // Only allow POST requests
            $libraryData = HomeModel::getLibraryInfo(); // Get library information (address, contact, etc.)

            if (!empty($libraryData)) { // Send back library info
                $this->jsonResponse(["libraryData" => $libraryData]);
            } else { // No info found → log warning + return error
                Logger::warning("No library information found");
                $this->jsonResponse(["message" => "No information found."], false);
            }
        } else {// Wrong request method
            Logger::warning("Invalid request method for getLibraryInfo");
            $this->jsonResponse(["message" => "Invalid request method."], false);
        }
    }

    public function sendEmailtoLibrary()
    {
        // Load email service for sending messages
        require_once Config::getServicePath('emailService.php');

        if ($this->isPost()) {  // Only allow POST requests
            // Get form data from reques
            $name = $this->getPost('name');
            $email = $this->getPost('email');
            $msg = $this->getPost('msg');

            // Log form data for debugging
            Logger::info("Contact form data received", ['name' => $name, 'email' => $email]);

            $libraryData = HomeModel::getLibraryInfo(); // Get library email address
            $libraryEmail = $libraryData['email'] ?? 'default@example.com';

            $subject = "Contact Us Message"; // Prepare email subject and body
            $body = '
                <h3 style="text-align: center;">You have received a new message through the Contact Us form on your website.</h3> 
                <div style="max-width: 600px; margin: 0 auto; padding: 20px; text-align: left;">
                    <p style ="font-weight: bold;">Details:</p>
                    <p>User Name: ' . htmlspecialchars($name) . '</p>
                    <p>User Email: ' . htmlspecialchars($email) . '</p>
                    <p>Message:<br><span style="font-weight: bold;">' . nl2br(htmlspecialchars($msg)) . '</span></p>
                    <p>Please review the details and take the necessary action.</p>
                </div>';
            // Send email using EmailService
            $emailService = new EmailService();
            $emailSent = $emailService->sendEmail($libraryEmail, $subject, $body, $email, $name);

            if ($emailSent) { // Success → log and return success message
                Logger::info("Email sent successfully", ['to' => $libraryEmail]);
                $this->jsonResponse(["message" => "Email sent successfully!"]);
            } else {  // Failure → log error and return failure message
                Logger::error("Failed to send email", ['to' => $libraryEmail]);
                $this->jsonResponse(["message" => "Failed to send email."], false);
            }
        } else { // Wrong request method
            Logger::warning("Invalid request method for sendEmailtoLibrary");
            $this->jsonResponse(["message" => "Invalid Request"], false);
        }
    }

    public function loadTopBooks()
    {
         // Only allow POST requests
        if ($this->isPost()) {
            $topBooksResult = HomeModel::getTopBooks(); // Get top borrowed books
            $topbooks = [];

            if ($topBooksResult) {
                while ($row = $topBooksResult->fetch_assoc()) {
                    $topbooks[] = $row;
                }
            }

            if (!empty($topbooks)) { // If top books found → return them
                // Return top books with type
                $this->jsonResponse([  // Return latest books instead
                    "books" => $topbooks,
                    "type" => "top"
                ]);
            } else {
                // If no top books → fallback to latest arrivals
                $latestBooksResult = HomeModel::getLatestArrivals();
                $latestBooks = [];

                if ($latestBooksResult) {
                    while ($row = $latestBooksResult->fetch_assoc()) {
                        $latestBooks[] = $row;
                    }
                }

                $this->jsonResponse([ // Return latest books instead
                    "books" => $latestBooks,
                    "type" => "latest"
                ]);
            }
        } else {// Wrong request method
            Logger::warning("Invalid request method for loadTopBooks");
            $this->jsonResponse(["message" => "Invalid request method."], false);
        }
    }

    public function serveLogo()
    {        // Get logo file name from request
        $imageName = $this->getGet('image', '');

        $basePath = Config::getLogoPath();// Build file path for logo
        $filePath = realpath($basePath . basename($imageName));
         // Check if logo exists and is vali
        if ($filePath && strpos($filePath, realpath($basePath)) === 0 && file_exists($filePath)) {
            header('Content-Type: ' . mime_content_type($filePath));
            readfile($filePath);
            exit;
        }
        // If logo not found → log error and return 404
        Logger::error("Logo image not found", ['requested_image' => $imageName]);
        http_response_code(404);
        echo "Image not found.";
        exit;
    }
}
