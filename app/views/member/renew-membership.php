<?php
$id = $_GET["id"];
?>

<!DOCTYPE html>
<html lang="en">
<?php
$pageTitle = "Renew Membership";
$pageCss = "renew-membership.css";
require_once Config::getViewPath("common","head.php");
?>

<body class="d-flex justify-content-center align-items-center vh-100">
    <div class="glassmorphism-card text-center shadow-lg p-5">
        <h2 class="danger fw-bold"><i class="fas fa-exclamation-circle"></i> Your library membership is about to expire!</h2>
        <p class="text-white m-4">Please renew your membership for <strong>LKR 1000.</strong></p>
        <button class="btn btn-primary text-white rounded-pill w-50" onclick="proceedPayment(<?php echo $id; ?>)">Proceed to Payment</button>
    </div>

    <?php require_once Config::getViewPath("common", "footer-noscroll.view.php"); ?>

    <script src="<?php echo Config::getJsPath("memberPayment.js"); ?>"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script src="https://www.payhere.lk/lib/payhere.js"></script></body>
</html>
