<?php

if (!isset($_SESSION['staff'])) {
    header("Location: index.php");
    exit;
}

// Session Timeout (30 minutes)
if (isset($_SESSION['staff']['last_activity']) && (time() - $_SESSION['staff']['last_activity'] > 1800)) {
    session_unset();
    session_destroy();
    header("Location: index.php");
    exit;
}

// Reset last activity time (only if user is active)
$_SESSION['staff']['last_activity'] = time();

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
    <style>
        .box {
            height: 400px;
            width: 500px;
            background-color: rgb(33, 33, 69);
        }
        .nav-bar {
            height: 100%;
        }
    </style>
</head>

<body>

    <div id="box1">
        <?php include "dash_header.php"; ?>
        <div class="d-flex bg-light">
            <div>
                <!-- Large and Medium Screens -->
                <div class="d-none d-md-block">
                    <?php include "dash_sidepanel.php"; ?>
                </div>

                <!-- Small Screens Only -->
                <div class="d-block d-md-none">
                    <?php include "small_sidepanel.php"; ?>
                </div>
            </div>

            <div class="container-fluid mx-5 mb-5 bg-white ">
                <div class="row">
                    <nav class="navbar p-md-4 navbar-light bg-light w-100">
                        <div class="d-flex align-items-center w-100 justify-content-between">
                            <span class="mb-0 h5">Payment Management</span>

                            <div class="d-flex align-items-center">
                                <button id="generateReport" class="btn btn-outline-dark me-3" onclick="generatePaymentReport();">
                                    <i class="fa fa-print"></i> Generate Report
                                </button>

                                <a href="#" class="text-decoration-none h5">
                                    <i class="fa fa-home"></i>
                                </a>
                            </div>
                        </div>
                    </nav>
                </div>

                <div class="row g-5 m-5 justify-content-center">
                    <!-- Add Books -->
                    <div class="col-sm-12 col-md-6">
                        <div class="card text-white bg-dark text-center shadow-lg rounded-4 py-5">
                            <i class="fas fa-user-plus text-info display-1"></i>
                            <p class="fw-bold fs-5 mt-3">Register Users</p>
                            <div class="d-flex justify-content-center">
                                <a href="<?php echo Config::indexPath() ?>?action=viewmemberrequests" class="btn btn-info rounded-pill mt-2 col-lg-3">Register</a>
                            </div>

                        </div>
                    </div>

                    <!-- View All Books -->
                    <div class="col-sm-12 col-md-6">
                        <div class="card text-white bg-dark text-center shadow-lg rounded-4 py-5">
                            <i class="fas fa-list display-1" style="color: #2AC23A;"></i>
                            <p class="fw-bold fs-5 mt-3">Members</p>
                            <div class="d-flex justify-content-center">
                                <a href="<?php echo Config::indexPath() ?>?action=viewmembers"
                                    class="btn col-lg-3 rounded-pill mt-2"
                                    style="background-color: #2AC23A;  color: white;">
                                    View
                                </a>
                            </div>

                        </div>
                    </div>

                    <!-- Add New Category -->
                    <div class="col-sm-12 col-md-6">
                        <div class="card text-white bg-dark text-center shadow-lg rounded-4 py-5">
                            <i class="fas fa-user-minus text-warning display-1"></i>
                            <p class="fw-bold fs-5 mt-3">Deactive Members</p>
                            <div class="d-flex justify-content-center">
                                <a href="<?php echo Config::indexPath() ?>?action=viewdeactivemembers" class="btn btn-warning col-lg-3 rounded-pill mt-2">View</a>
                            </div>

                        </div>
                    </div>

                    <!-- View Deactivated Books -->
                    <div class="col-sm-12 col-md-6">
                        <div class="card text-white bg-dark text-center shadow-lg rounded-4 py-5">
                            <i class="fas fa-users-slash display-1" style="color: #FD0D0D;"></i>
                            <p class="fw-bold fs-5 mt-3">Rejected Users</p>
                            <div class="d-flex justify-content-center">
                                <a href="<?php echo Config::indexPath() ?>?action=viewrejectedrequests"
                                    class="btn col-sm-6 col-lg-3 rounded-pill mt-2"
                                    style="background-color: #FD0D0D;  color: white;">
                                    View
                                </a>
                            </div>

                        </div>
                    </div>
                </div>


            </div>




        </div>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

</body>

</html>