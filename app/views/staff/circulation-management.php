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
$pageTitle = "Book Management";
$pageCss = "circulation-management.css";
require_once Config::getViewPath("common","head.php");
?>


<body>

    <div id="box1">
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

            <div class="container-fluid mx-md-5 mb-5 bg-white">
                <div class="row">
                    <nav class="navbar p-md-4 navbar-light bg-light">
                        <span class="h5 mb-0 ">Circulation Management</span>
                        <a href="#" class="text-decoration-none h5"><i class="fa fa-home"></i></a>
                    </nav>
                </div>

                <div class="row g-5 m-1 m-md-5 justify-content-center">
                    <!-- Add Books -->
                    <div class="col-sm-12 col-md-6">
                        <div class="card text-white bg text-center shadow-lg rounded-4 py-5">
                            <i class="fas fa-book-open text-info display-1"></i>
                            <p class="fw-bold fs-5 mt-3">Issue Book</p>
                            <div class="d-flex justify-content-center">
                                <a href="<?php echo Config::indexPath() ?>?action=showissuebook" class="btn btn-info px-5 rounded-pill mt-2">Add</a>
                            </div>

                        </div>
                    </div>

                    <!-- View All Books -->
                    <div class="col-sm-12 col-md-6">
                        <div class="card text-white bg text-center shadow-lg rounded-4 py-5">

                            <i class="fas fa-list display-1" style="color: #2AC23A;"></i>
                            <p class="fw-bold fs-5 mt-3">Issued Books</p>
                            <div class="d-flex justify-content-center">
                                <a href="<?php echo Config::indexPath() ?>?action=viewissuebooks"
                                    class="btn px-5 rounded-pill mt-2"
                                    style="background-color: #2AC23A;  color: white;">
                                    View
                                </a>
                            </div>
                        </div>
                    </div>

                    <!-- Add New Category -->
                    <div class="col-sm-12 col-md-6">
                        <div class="card text-white bg text-center shadow-lg rounded-4 py-5">
                            <i class="fas fa-cubes text-warning display-1"></i>
                            <p class="fw-bold fs-5 mt-3">Reservation</p>
                            <div class="d-flex justify-content-center">
                                <a href="<?php echo Config::indexPath() ?>?action=viewreservations" class="btn btn-warning px-5 rounded-pill mt-2">View</a>
                            </div>

                        </div>
                    </div>


                </div>

            </div>
        </div>
    </div>
    <?php require_once Config::getViewPath("common", "stafffoot.php"); ?>

</body>

</html>