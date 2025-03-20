<?php
require_once "../../main.php";



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
    </style>
</head>

<body>

    <div id="box1">
        <?php include "dash_header.php"; ?>
        <div class="d-flex bg-light">
            <div class="nav-bar vh-100">
                <?php include "dash_sidepanel.php"; ?>
            </div>

            <div class="container-fluid mx-5 mb-5 bg-white">
                <div class="row">
                    <nav class="navbar p-4 navbar-light bg-light">
                        <span class="navbar-brand mb-0 h1">Dashboard <small class="text-muted">control panel</small></span>
                        <a href="#" class="text-decoration-none h5"><i class="fa fa-home"></i> Home</a>
                    </nav>
                </div>

                <div class="row g-5 m-5 justify-content-center">
                    <!-- Add Books -->
                    <div class="col-sm-12 col-md-6">
                        <div class="card text-white bg-dark text-center shadow-lg rounded-4 py-5">
                            <i class="fas fa-user-plus text-primary display-1"></i>
                            <p class="fw-bold fs-5 mt-3">Resister Users</p>
                            <div class="d-flex justify-content-center">
                                <a href="<?php echo Config::indexPath() ?>?action=viewmemberrequests" class="btn btn-primary w-50 rounded-pill mt-2">Issue</a>
                            </div>

                        </div>
                    </div>

                    <!-- View All Books -->
                    <div class="col-sm-12 col-md-6">
                        <div class="card text-white bg-dark text-center shadow-lg rounded-4 py-5">
                            <i class="fas fa-list text-success display-1"></i>
                            <p class="fw-bold fs-5 mt-3">View Members</p>
                            <div class="d-flex justify-content-center">
                                <a href="<?php echo Config::indexPath() ?>?action=viewmembers" class="btn btn-success w-50 rounded-pill mt-2">View</a>
                            </div>

                        </div>
                    </div>

                    <!-- Add New Category -->
                    <div class="col-sm-12 col-md-6">
                        <div class="card text-white bg-dark text-center shadow-lg rounded-4 py-5">
                            <i class="fas fa-user-minus text-warning display-1"></i>
                            <p class="fw-bold fs-5 mt-3">View Detective users</p>
                            <div class="d-flex justify-content-center">
                                <a href="<?php echo Config::indexPath() ?>?action=viewmemberrequests" class="btn btn-warning w-50 rounded-pill mt-2">Issue</a>
                            </div>

                        </div>
                    </div>

                    <!-- View Deactivated Books -->
                    <div class="col-sm-12 col-md-6">
                        <div class="card text-white bg-dark text-center shadow-lg rounded-4 py-5">
                            <i class="fas fa-users-slash text-info display-1"></i>
                            <p class="fw-bold fs-5 mt-3">view Reject users</p>
                            <div class="d-flex justify-content-center">
                                <a href="<?php echo Config::indexPath() ?>?action=viewmembers" class="btn btn-info w-50 rounded-pill mt-2">View</a>

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