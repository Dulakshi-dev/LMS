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

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
    <style>
        .bg{
            background: rgba(26, 50, 65, 1);
        }
    </style>
</head>

<body>

    <div id="box1">
        <?php include "dash_header.php"; ?>
        <div class="d-flex bg-light">
        <div>
            <div class="nav-bar d-none d-lg-block">
                <?php include "dash_sidepanel.php"; ?>
            </div>

            <div class="nav-bar d-block d-lg-none">
                <?php include "small_sidepanel.php"; ?>
            </div>

        </div>

            <div class="container-fluid mx-md-5 mb-5 bg-white">
                <div class="row">
                    <nav class="navbar p-1 p-md-4 navbar-light bg-light">
                        <span class="navbar-brand mb-0 h1">Book Management <small class="text-muted">control panel</small></span>
                        <a href="#" class="text-decoration-none h5"><i class="fa fa-home"></i></a>
                    </nav>
                </div>

                <div class="row g-5 m-1 m-md-5 justify-content-center">
                    <!-- Add Books -->
                    <div class="col-sm-12 col-md-6">
                        <div class="card text-white bg text-center shadow-lg rounded-4 py-5">
                            <i class="fas fa-plus text-info display-1"></i>
                            <p class="fw-bold fs-5 mt-3">Add Books</p>
                            <div class="d-flex justify-content-center">
                                <a href="<?php echo Config::indexPath() ?>?action=addBook" class="btn btn-info px-5 rounded-pill mt-2">Add</a>
                            </div>

                        </div>
                    </div>

                    <!-- View All Books -->
                    <div class="col-sm-12 col-md-6">
                        <div class="card text-white bg text-center shadow-lg rounded-4 py-5">

                            <i class="fas fa-list display-1" style="color: #2AC23A;"></i>
                            <p class="fw-bold fs-5 mt-3">All Books</p>
                            <div class="d-flex justify-content-center">
                                <a href="<?php echo Config::indexPath() ?>?action=viewBooks"
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
                            <i class="fas fa-layer-group text-warning display-1"></i>
                            <p class="fw-bold fs-5 mt-3">Add New Category</p>
                            <div class="d-flex justify-content-center">
                                <a href="<?php echo Config::indexPath() ?>?action=addCategory" class="btn btn-warning px-5 rounded-pill mt-2">Add</a>
                            </div>

                        </div>
                    </div>

                  

                    <!-- View Deactivated Books -->
                    <div class="col-sm-12 col-md-6">
                        <div class="card text-white bg text-center shadow-lg rounded-4 py-5">
                            <i class="fas fa-book-open display-1" style="color: #FD0D0D;"></i>
                            <p class="fw-bold fs-5 mt-3">Deactivated Books</p>
                            <div class="d-flex justify-content-center">
                                <a href="<?php echo Config::indexPath() ?>?action=viewdeactivatedbooks"
                                    class="btn px-5 rounded-pill mt-2"
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

</body>

</html>