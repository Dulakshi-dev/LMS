<?php

if (!isset($_SESSION['member'])) {
    header("Location: index.php?action=login");
    exit;
}

// Session Timeout (30 minutes)
if (isset($_SESSION['member']['last_activity']) && (time() - $_SESSION['member']['last_activity'] > 1800)) {
    session_unset();  // Clear session data
    session_destroy();
    header("Location: index.php?action=login");
    exit;
}

// Reset last activity time (only if user is active)
$_SESSION['member']['last_activity'] = time();

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>About Software</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>

<body>
    <?php require_once Config::getViewPath("member", "header.php"); ?>

    <div class="d-flex">
        <!-- Side Panel -->
        <div class="nav-bar d-none d-md-block">
            <?php require_once Config::getViewPath("member", "sidepanel.php"); ?>
        </div>
        <div class="container mt-3">

            <div class="card">
                <div class="card-header bg-secondary text-white">
                    <h3>About the Library Management System</h3>
                </div>
                <div class="card-body">
                    <p>Welcome to the Library Management System! This platform allows members to search for books, reserve them, and manage their library interactions efficiently.</p>

                    <h4>Key Features for Members:</h4>
                    <ul>
                        <li>Diaplay recommended books, most borrowed books(top books), newly arrived books.</li>
                        <li>Display availability of books</li>
                        <li>Save favorite books to 'My Library'</li>
                        <li>Reserve books online</li>
                        <li>Renew the membership</li>
                        <li>Reset password</li>
                        <li>Update profile details</li>
                        <li>Register by doing the annual membership payment via online</li>
                        <li>Diaplay details about borrowing history and reservations</li>
                        <li>Search for books by title, author, language or category</li>
                        <li>Receive notifications about due dates and new arrivals</li>
                        <li>Receive notifications about membership renewal</li>
                    </ul>

                    <h4>How to Use the System?</h4>
                    <ul>
                        <li><strong>Home:</strong> Display the opening hours, about us, news updates, top books.</li>
                        <li><strong>Register:</strong> Register by entering personal details and doing the membership paymnet</li>
                        <li><strong>Logging In:</strong> Use your membership ID and password to access your account.</li>
                        <li><strong>Dashboard:</strong> Provide the recommended books, top books and newly arrived books.</li>
                        <li><strong>Searching Books:</strong> Use the search bar to find books by title, author, or category.</li>
                        <li><strong>Book:</strong> Dispaly the book details along with availability and reservation facility</li>
                        <li><strong>Reserving Books:</strong> Click the reserve button to hold a book for pickup or reserve unavailable book.</li>
                        <li><strong>Profile:</strong> Profile details can be updated along with reset password facility.</li>
                        <li><strong>Save Book:</strong> Interested books can be saved to 'My Library' by clicking on save button.</li>
                        <li><strong>Borrow History:</strong> Provide details about the borrowed books along with there status.</li>
                        <li><strong>Notifications:</strong> Stay updated on due dates and library announcements.</li>

                    </ul>

                    <h4>Technical Details:</h4>
                    <ul>
                        <li>Compatible with all modern web browsers</li>
                        <li>Accessible on mobile, tablet, and desktop devices</li>
                    </ul>

                    <h4>Need Help?</h4>
                    <p>If you have any questions, contact library support at <a href="mailto:support@example.com">support@example.com</a> or visit the help desk.</p>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>