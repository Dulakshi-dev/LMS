<?php
?>
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
$member_id = $_SESSION["member"]["member_id"]

?>


<!DOCTYPE html>
<html lang="en">

<?php
$pageTitle = "My Library";
$pageCss = "my-library.css";
require_once Config::getViewPath("home","head.php");
?>

<body onload="loadSavedBooks();">
    <?php require_once Config::getViewPath("member", "header.php"); ?>

    <div class="d-flex">
        <div>
            <div class="nav-bar d-block d-lg-none">
                <?php require_once Config::getViewPath("member", "sm_sidepanel.php"); ?>
            </div>
            <div class="nav-bar d-none d-lg-block">
                <?php require_once Config::getViewPath("member", "sidepanel.php"); ?>
            </div>
        </div>

        <div class="container bg-light  mt-md-4 p-md-4">
            <!-- Home Link -->
            <div class="d-flex  justify-content-end align-items-center mb-4">
                <a href="#" class="page-link">
                    <i class="fa fa-home"></i>
                </a>
            </div>

            <!-- Search Bar -->
            <div class="row mb-5 g-3">
                <div class="col-12 col-md-4">
                    <input id="bookid" type="text" class="form-control" placeholder="Type Book ID" aria-label="Book ID">
                </div>
                <div class="col-12 col-md-4">
                    <input id="title" type="text" class="form-control" placeholder="Type Book Name" aria-label="Book Name">
                </div>
                <div class="col-10 col-md-3">
                    <input id="category" type="text" class="form-control" placeholder="Type Category" aria-label="Category">
                </div>
                <div class=" col-1 d-grid">
                    <button class="btn btn-primary" onclick="loadSavedBooks();">
                        <i class="fa fa-search"></i>
                    </button>
                </div>
            </div>

            <div id="myLibraryBody" class="bg-white py-2">

            </div>
            <div id="pagination"></div>

        </div>
    </div>


    <script src="<?php echo Config::getJsPath("myLibrary.js"); ?>"></script>
    <script src="<?php echo Config::getJsPath("pagination.js"); ?>"></script>


    <!-- <script>
        function showReserve2() {
            document.getElementById("reserve1").classList.add("d-none");
            document.getElementById("reserve2").classList.remove("d-none");
        }

        // Star rating functionality
        document.querySelectorAll('.fa-star').forEach(star => {
            star.addEventListener('click', function() {
                const value = this.getAttribute('data-value');
                document.querySelectorAll('.fa-star').forEach(s => {
                    s.classList.remove('text-warning');
                    if (s.getAttribute('data-value') <= value) {
                        s.classList.add('text-warning');
                    }
                });
            });
        });
    </script> -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>