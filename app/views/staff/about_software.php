<?php

if (!isset($_SESSION['staff'])) {
    header("Location: index.php");
    exit;
}

// Session Timeout (30 minutes)
if (isset($_SESSION['staff']['last_activity']) && (time() - $_SESSION['staff']['last_activity'] > 1800)) {
    session_unset();  // Clear session data
    session_destroy();
    header("Location: index.php");
    exit;
}

// Reset last activity time (only if user is active)
$_SESSION['staff']['last_activity'] = time();
?>

<!DOCTYPE html>
<html lang="en">

<?php
$pageTitle = "About Software";
require_once Config::getViewPath("common","head.php");
?>

<body>
    <?php include "dash_header.php"; ?>
    <div class="d-flex bg-light">
    <div>
            <div class="h-100 d-none d-lg-block">
                <?php include "dash_sidepanel.php"; ?>
            </div>

            <div class="h-100 d-block d-lg-none">
                <?php include "small_sidepanel.php"; ?>
            </div>

        </div>
        <div class="container my-3">
            <div class="card">
                <div class="card-header bg-secondary text-white">
                    <h3>About the Library Management System</h3>
                </div>
                <div class="card-body">
                    <p>The Library Management System is designed to assist staff in managing books, members, and transactions efficiently. It enables staff members to update user details, handle book inventories, send notifications, and generate reports for administrative purposes.</p>

                    <h4>Key Features:</h4>
                    <ul>
                        <li>User and membership management</li>
                        <li>Book cataloging and stock control</li>
                        <li>Book reservation management</li>
                        <li>Paymet Tracking</li>
                        <li>Update Profile Details</li>
                        <li>Update the library information</li>
                        <li>Automated notifications via email</li>
                        <li>Secure role-based access</li>
                        <li>Reporting and analytics</li>
                    </ul>

                    <h4>Getting Started:</h4>
                    <ul>
                        <li>Register by entering the personal details and the enrollment key provided.</li>
                        <li>Log in using your staff credentials.</li>
                        <li>Use profile section to update profile data and reset password.</li>
                        <li>Navigate to the dashboard to access various modules.</li>
                        <li>Use the book management section to update the library inventory.</li>
                        <li>Use the member management section to manage the library members and approve membership requests.</li>
                        <li>Use the circulation management section to manage the book circulation along with reservations.</li>
                        <li>Send notifications to users when needed.</li>
                    </ul>

                    <h4>Technical Details:</h4>
                    <ul>
                        <li>Backend: PHP, MySQL</li>
                        <li>Frontend: HTML, CSS, JavaScript, Bootstrap</li>
                        <li>System Requirements: Modern web browser, Internet connection</li>
                    </ul>

                    <h4>Support & Troubleshooting:</h4>
                    <p>If you need assistance, contact the IT support team at <a href="mailto:shelfloomgp13@gmail.com">support@example.com</a> or call +94-XXX-XXXXXX.</p>
                </div>
            </div>
        </div>
    </div>
    <?php require_once Config::getViewPath("staff", "footer.php"); ?>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>