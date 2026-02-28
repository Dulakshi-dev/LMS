<?php
require_once '../../main.php'; // Include database connection

$libraryData = HomeModel::getLibraryInfo();

$fee = $libraryData['membership_fee'];
?>

<!DOCTYPE html>
<html lang="en">

<?php
$pageTitle = "Expired";
$pageCss = "expired.css";
require_once Config::getViewPath("common","head.php");
?>

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
    <script src="<?php echo Config::getJsPath("memberPayment.js"); ?>"></script>
</body>


</html>