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
    <title>Dashboard</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">

    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">

    <style>

        .box-1{
            background: rgba(26, 50, 65, 1);
            color: white;
        }
        .box-2{
            height: 400px;
            width: 550px;
            border-radius: 20px;
        }
        
        .box-3{
            height: 200px;
            width: 150px;
        }
        
        .box-4{
            height: 400px;
            width: 750px;
            background: rgba(26, 50, 65, 1);
            border-radius: 10px;
        }
    </style>
</head>

<body>
    <?php include "dash_header.php"; ?>

    <div class="d-flex">
        <div class="nav-bar">
            <?php include "dash_sidepanel.php"; ?>
        </div>
        <div class="container-fluid mb-5 bg-white">
            <div class="row">
                <nav class="navbar p-4 navbar-light bg-light">
                    <span class="navbar-brand mb-0 h1">Staff Management <small class="text-muted">control panel</small></span>
                    <a href="#" class="text-decoration-none h5"><i class="fa fa-home"></i> Home</a>
                </nav>
            </div>
            <div class="p-2 mt-3">
                <div class="row">
                    <div class="col-lg-3">
                        <div class="d-flex justify-content-between p-2 box-1 rounded">
                            <div>
                                <h1>120</h1>
                                <p>Issued Books</p>
                            </div>
                            <div>
                            <i class="fa fa-bolt"></i>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-3">
                        <div class="d-flex justify-content-between p-2 box-1 rounded">
                            <div>
                                <h1>120</h1>
                                <p>Issued Books</p>
                            </div>
                            <div>
                            <i class="fa fa-bolt"></i>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-3">
                        <div class="d-flex justify-content-between p-2 box-1 rounded">
                            <div>
                                <h1>120</h1>
                                <p>Issued Books</p>
                            </div>
                            <div>
                            <i class="fa fa-bolt"></i>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-3">
                        <div class="d-flex justify-content-between p-2 box-1 rounded">
                            <div>
                                <h1>120</h1>
                                <p>Issued Books</p>
                            </div>
                            <div>
                            <i class="fa fa-bolt"></i>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="row gap-5 my-5 align-items-center justify-content-center">
                    <div class="col-lg-5 bg-light ">
                        <div class=" mt-4 box-2 p-3">

                        </div>
                    </div>
                    <div class="col-lg-5 bg-light">
                        <div class=" mt-4 box-2 p-3">

                        </div>
                    </div>
                </div>

                <div class="row gap-3 align-items-center mt-5 justify-content-center">
                    <h3>Top choices</h3>
                    <div class="col-lg-2 col-md-3 col-sm-2 text-center bg-light">
                        <div class="">
                            <img class="box-3" src="<?php echo Config::getImagePath("stafflog.jpg"); ?>" alt="">
                        </div>
                        <div>
                            <p>Author name</p>
                        </div>
                    </div>
                    <div class="col-lg-2 col-md-3 col-sm-2 text-center bg-light">
                        <div class="">
                            <img class="box-3" src="<?php echo Config::getImagePath("stafflog.jpg"); ?>" alt="">
                        </div>
                        <div>
                            <p>Author name</p>
                        </div>
                    </div>
                    <div class="col-lg-2 col-md-3 col-sm-2 text-center bg-light">
                        <div class="">
                            <img class="box-3" src="<?php echo Config::getImagePath("stafflog.jpg"); ?>" alt="">
                        </div>
                        <div>
                            <p>Author name</p>
                        </div>
                    </div>
                    <div class="col-lg-2 col-md-3 col-sm-2 text-center bg-light">
                        <div class="">
                            <img class="box-3" src="<?php echo Config::getImagePath("stafflog.jpg"); ?>" alt="">
                        </div>
                        <div>
                            <p>Author name</p>
                        </div>
                    </div>
                    <div class="col-lg-2 col-md-3 col-sm-2 text-center bg-light">
                        <div class="">
                            <img class="box-3" src="<?php echo Config::getImagePath("stafflog.jpg"); ?>" alt="">
                        </div>
                        <div>
                            <p>Author name</p>
                        </div>
                    </div>
                    
                    
                    
                </div>

                <div class="d-flex align-items-center mt-5 justify-content-center">
                    <div class="mt-4">
                        <div class="box-4">
                            
                        </div>
                    </div>
                </div>
            </div>

        </div>

    </div>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>