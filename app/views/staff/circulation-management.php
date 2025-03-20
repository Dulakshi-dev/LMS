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

                <div class="row">
                    <div class="fw-bold bg-white d-flex justify-content-center mt-5">
                        <div class="container mt-5">
                            <div class="row g-3 d-flex justify-content-evenly">
                                <div class="card text-white text-center p-4 shadow-lg rounded-4 d-flex flex-column justify-content-center align-items-center" id="box-1" style="width: 300px; height: 250px;">
                                    <i class="fas fa-plus display-1 text-secondary"></i>
                                    <p class="fw-bold fs-5 mt-3 text-dark">Issue Books</p>
                                    <a href="<?php echo Config::indexPath() ?>?action=showissuebook" class="btn btn-secondary  w-50 rounded-pill mt-2">Issue</a>
                                </div>
                                <div class="card text-white text-center p-4 shadow-lg rounded-4 d-flex flex-column justify-content-center align-items-center" id="box-1" style="width: 300px; height: 250px;">

                                    <i class="fa fa-book display-1 text-warning"></i>
                                    <p class="fw-bold fs-5 mt-3 text-dark">View Issued Books</p>
                                    <a href="<?php echo Config::indexPath() ?>?action=viewissuebooks" class="btn btn-warning w-50 rounded-pill mt-2">View</a>
                                </div>
                            </div>
                            <div class="d-flex justify-content-center m-5">
                                <div class="card text-white text-center p-4 shadow-lg rounded-4 d-flex flex-column justify-content-center align-items-center" id="box-1" style="width: 300px; height: 250px;">

                                    <i class="fa fa-cubes display-1 text-success"></i>
                                    <p class="fw-bold fs-5 mt-3 text-dark">View Reservation</p>
                                    <a href="<?php echo Config::indexPath() ?>?action=viewreservations" class="btn btn-success w-50 rounded-pill mt-2">View</a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>




        </div>
    </div>

</body>

</html>