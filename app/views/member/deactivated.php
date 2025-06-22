<?php
require_once '../../main.php'; // Include database connection

$libraryData = HomeModel::getLibraryInfo();
$libraryAddress = $libraryData['address'];
$libraryEmail = $libraryData['email'];
$libraryPhone = $libraryData['mobile'];

?>
<!DOCTYPE html>
<html lang="en">

<?php
$pageTitle = "Deactivated";
$pageCss = "deactivated.css";
require_once Config::getViewPath("common", "head.php");
?>


<body>
    <!-- <?php require_once Config::getViewPath("common", "head.php"); ?> -->
    <div class="d-flex justify-content-center align-items-center m-auto vh-100">
        <div class="glassmorphism-card text-center shadow-lg p-5">
            <h4 class="danger fw-bold"><i class="fas fa-exclamation-circle"></i> Your Library Account Has Been Deactivated!</h4>
            <p class="text-white m-4">Your library account has been deactivated by the administration.</p>
            <p class="text-white m-4">Library Contact: <br><?= $libraryEmail ?><br><?= $libraryPhone ?><br></p>
            <p class="text-white m-4"> Visit the Library or Call Us to Reactivate Your Account.</p>

            <div>
                <a href="<?php echo Config::indexPathMember() ?>?action=home" class="btn btn-outline-light rounded-pill w-50">
                    Go To Home ->
                </a>
            </div>


        </div>
    </div>

    <!-- <?php require_once Config::getViewPath("common", "footer.php"); ?> -->

</body>

</html>