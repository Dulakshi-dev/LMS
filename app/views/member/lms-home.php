<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
    <title>LMS | Home</title>
    <style>
        /* Custom style for horizontal scrolling on mobile */
        @media (max-width: 992px) {
            .scrollable-books {
                display: flex;
                flex-wrap: nowrap;
                overflow-x: auto;
                -webkit-overflow-scrolling: touch;
                padding-bottom: 15px;
            }
            .book-card {
                flex: 0 0 auto;
                width: 250px;
                margin-right: 15px;
            }
        }
    </style>
</head>

<body onload="loadTopBooks();">
    <?php require_once Config::getViewPath("home", "header.view.php"); ?>

    <!-- Hero Section -->
    <div class="container-fluid px-0 mx-auto">
        <div class="row g-0">
            <div class="col-12" style="background-image: url('<?php echo Config::getImagePath("test.jpg"); ?>'); height: 90vh; min-height: 600px; background-size: cover; background-position: center; background-repeat: no-repeat;">
                <div class="container h-100">
                    <div class="row h-100 align-items-center">
                        <div class="col-12 col-lg-6 offset-lg-6 text-center text-lg-start">
                            <div class=" p-4 rounded-3">
                                <h2 class="mb-4 fw-bold"><?= $libraryName ?> - Library Management System</h2>
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
                    <h1 class="fw-bold"><span class="text-danger">Top</span> <span class="text-dark">Books</span></h1>
                </div>
            </div>
            <div class="row justify-content-center">
                <div class="col-12">
                    <div class="scrollable-books row row-cols-1 row-cols-sm-2 row-cols-md-3 row-cols-lg- g-4" id="topBookBody">
                        <!-- Books will be loaded here by JavaScript -->
                        <!-- Each book card should be wrapped in a div with class "col book-card" -->
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="container-fluid p-1">
    <div class="row g-0">
        <div class="col-12" style="height: 100vh; width:100%; min-height: 40vh; background-image: url('<?php echo Config::getImagePath("quotes.jpg"); ?>'); background-size: cover; background-position: center;">
            <!-- Empty div - just shows the background image -->
        </div>
    </div>
</div>

    <?php require_once Config::getViewPath("home", "footer.view.php"); ?>
    <script src="<?php echo Config::getJsPath("home.js"); ?>"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>