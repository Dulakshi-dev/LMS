<!DOCTYPE html>
<html lang="en">

<?php
$pageTitle = "Welcome";
require_once Config::getViewPath("common","head.php");
?>

<body onload="loadTopBooks();">
    <?php require_once Config::getViewPath("guest", "header.view.php"); ?>

    <!-- Hero Section -->
    <div class="container-fluid px-0 mx-auto">
        <div class="row g-0">
            <div class="col-12" style="background-image: url('<?php echo Config::getImagePath("test.jpg"); ?>'); height: 90vh; min-height: 500px; background-size: cover; background-position: center; background-repeat: no-repeat;">
                <div class="container h-100">
                    <div class="row h-100 align-items-center">
                        <div class="col-12 col-lg-6 offset-lg-6 text-center text-lg-start">
                            <div class=" p-4 rounded-3 text-center">
                                <h2 class="mb-4 fw-bold"><?= $libraryName ?> <br> Library Management System</h2>
                                <p class="fs-5 mb-4">Register now to access our full range of features and start exploring our vast collection of resources</p>
                                <div class="mt-5">
                                    <button class="btn btn-dark rounded-5 px-4" onclick="window.location.href='<?php echo Config::indexPathMember() ?>?action=register'">Register</button>
                                    <button class="btn btn-secondary ms-3 rounded-5 px-4" onclick="window.location.href='<?php echo Config::indexPathMember() ?>?action=login'">Login</button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Top Books Section -->
    <div class="container-fluid bg-light py-5">
        <div class="container">
            <div class="row mb-4">
                <div class="col-12">
                    <h1 class="fw-bold" id="topicTopBooks"><span class="text-danger">Top</span> <span class="text-dark">Books</span></h1>
                    <h1 class="fw-bold d-none" id="topicNewArrivals"><span class="text-danger">New</span> <span class="text-dark">Arrivals</span></h1>
                </div>
              
            </div>
            <div class="row">
                <div class="col-12">
                    <div class="row g-4" id="bookBody">
                        <!-- Books will be loaded here by JavaScript -->
                    </div>
                   
                </div>
            </div>
        </div>
    </div>

    <div class="container-fluid p-1">
    <div class="row g-0">
        <div class="col-12 d-none d-md-block" style="height: 100vh; width:100%; background-image: url('<?php echo Config::getImagePath("quotes.jpg"); ?>'); background-size: cover; background-position: center;">
            <!-- Empty div - just shows the background image -->
        </div>
        <div class="col-12 d-md-none" style="height: 50vh; width:100%; background-image: url('<?php echo Config::getImagePath("quotes.jpg"); ?>'); background-size: cover; background-position: center;">
            <!-- Empty div - just shows the background image -->
        </div>
    </div>
</div>

    <?php require_once Config::getViewPath("common", "footer.php"); ?>
    <script src="<?php echo Config::getJsPath("home.js"); ?>"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>