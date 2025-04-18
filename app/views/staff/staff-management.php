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
$pageTitle = "Staff Management";
$pageCss = "staff-management.css";
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
                    <nav class="navbar p-2 p-md-4 navbar-light bg-light">
                        <span class="navbar-brand mb-0 h1">Staff Management <small class="text-muted">control panel</small></span>
                        <a href="#" class="text-decoration-none h5"><i class="fa fa-home"></i></a>
                    </nav>
                </div>

                <div class="row m-4">
                    <div class="col-md-2">
                        <p class="mr-3 fw-bold mt-1">New Staff Member?</p>
                    </div>
                    <div class="col-md-4 ">
                        <input class="form-control" type="email" id="email" placeholder="Enter the email">
                        <span id="emailError" class="text-danger"></span>

                    </div>
                    <div class="col-md-2 ">
                        <label class="form-label mt-1">Select Role</label>

                    </div>
                    <div class="col-md-2">
                        <div class="form-check">
                            <input class="form-check-input" type="radio" id="roleLibraryStaff" name="role" value="Library Staff" required>
                            <label class="form-check-label" for="roleLibraryStaff">Library Staff</label>
                        </div>
                        <div class="form-check">
                            <input class="form-check-input" type="radio" id="roleLibrarian" name="role" value="Librarian" required>
                            <label class="form-check-label" for="roleLibrarian">Librarian</label>
                        </div>
                        <span id="roleError" class="text-danger"></span>

                    </div>
                    <div class="col-md-2 text-end">
                        <button class="btn btn-dark" onclick="sendKey();"><i class="fas fa-paper-plane me-2"></i>Send Key</button>
                    </div>
                </div>



                <div class="row g-5 m-1 m-md-5 justify-content-center">


                    <!-- View All Books -->
                    <div class="col-sm-12 col-md-6">
                        <div class="card text-white bg text-center shadow-lg rounded-4 py-5">
                            <i class="fas fa-list display-1" style="color: #2AC23A;"></i>
                            <p class="fw-bold fs-5 mt-3">View Staff</p>
                            <div class="d-flex justify-content-center">
                                <a href="<?php echo Config::indexPath() ?>?action=viewstaff"
                                    class="btn rounded-pill px-5 mt-2"
                                    style="background-color: #2AC23A;  color: white;">
                                    View
                                </a>
                            </div>

                        </div>
                    </div>

                    <!-- Add New Category -->
                    <div class="col-sm-12 col-md-6">
                        <div class="card text-white bg text-center shadow-lg rounded-4 py-5">
                            <i class="fas fa-user-minus text-warning display-1"></i>
                            <p class="fw-bold fs-5 mt-3">View Detective Staff</p>
                            <div class="d-flex justify-content-center">
                                <a href="<?php echo Config::indexPath() ?>?action=viewdeactivatedstaff" class="btn btn-warning px-5 rounded-pill mt-2">View</a>
                            </div>

                        </div>
                    </div>



                </div>
            </div>
        </div>
    </div>
      <?php require_once Config::getViewPath("common", "footer.view.php"); ?>

    <script src="<?php echo Config::getJsPath("staff.js"); ?>"></script>

    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

</body>

</html>