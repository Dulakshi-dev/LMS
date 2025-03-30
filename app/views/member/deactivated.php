<?php
require_once '../../main.php'; // Include database connection

$libraryData = HomeModel::getLibraryInfo();
$libraryAddress = $libraryData['address'];
$libraryEmail = $libraryData['email'];
$libraryPhone = $libraryData['mobile'];

?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Membership Expired</title>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.0/css/bootstrap.min.css" rel="stylesheet" />
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css" rel="stylesheet" />
    <style>
        body {
            background-image: url('<?php echo Config::getImagePath("signup.jpg"); ?>');
            /* Replace with your actual image path */
            background-size: cover;
            background-position: center;
            background-repeat: no-repeat;
            background-attachment: fixed;
            min-height: 100vh;
        }

        .glassmorphism-card {
            background: rgba(0, 0, 0, 0.69);
            backdrop-filter: blur(10px);
            border-radius: 15px;
            padding: 30px;
            max-width: 700px;
            color: white;
        }

        .danger {
            color: red;
        }
    </style>
</head>

<body class="d-flex justify-content-center align-items-center vh-100">
    <div class="glassmorphism-card text-center shadow-lg p-5">
        <h4 class="danger fw-bold"><i class="fas fa-exclamation-circle"></i> Your Library Account Has Been Deactivated!</h4>
        <p class="text-white m-4">Your library account has been deactivated by the administration.</p>
        <p class="text-white m-4">Library Contact: <br><?= $libraryEmail ?><br><?= $libraryPhone ?><br></p>
        <p class="text-white m-4"> Visit the Library or Call Us to Reactivate Your Account.</p>

    </div>
</body>

</html>