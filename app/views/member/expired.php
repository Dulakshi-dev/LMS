<?php
require_once '../../main.php'; // Include database connection

$libraryData = HomeModel::getLibraryInfo();

$fee = $libraryData['membership_fee'];
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
            background: rgba(0, 0, 0, 0.8);
            backdrop-filter: blur(10px);
            border-radius: 15px;
            padding: 30px;
            max-width: 600px;
            color: white;
        }

        .danger {
            color: red;
        }

        /* Inline style for placeholder customization */
        #memberID::placeholder {
            color: rgb(255, 255, 255);
            /* Set a dark color for placeholder text */
            opacity: 1;
            /* Make sure placeholder text is fully opaque */
        }
    </style>
</head>

<body class="d-flex justify-content-center align-items-center vh-100">
    <div class="glassmorphism-card text-center shadow-lg p-5">
        <h3 class="danger fw-bold"><i class="fas fa-exclamation-circle"></i> Your library membership has expired!</h3>
        <p class="text-white m-4">Please renew your membership for <strong>LKR <?= $fee ?>.</strong></p>
        
        <!-- Input div that is initially hidden -->
        <div class="d-none" id="inputdiv">
            <input type="text" class="form-control rounded-pill bg-transparent mt-3 text-light" name="memberID" id="memberID" placeholder="Enter Member ID">
            <button class="btn btn-primary text-white rounded-pill w-50 mt-3" onclick="renew()">Proceed to Payment</button>
        </div>

        <!-- Button to show the input div -->
        <button class="btn btn-primary text-white rounded-pill w-50 mt-3 proceedbtn" id="proceedbtn" onclick="showMembershipInput()">Proceed to Payment</button>
    </div>

    <script>
        function showMembershipInput() {
            // Show the input div and hide the proceed button
            document.getElementById("inputdiv").classList.remove("d-none");
            document.getElementById("proceedbtn").classList.add("d-none");
        }
        function renew(){
            var id = document.getElementById("memberID").value.trim();
            proceedPayment(id);
        }
    </script>
    
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script src="https://www.payhere.lk/lib/payhere.js"></script>
    <script src="<?php echo Config::getJsPath("payment.js"); ?>"></script>
</body>


</html>