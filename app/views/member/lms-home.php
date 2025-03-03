<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
    <title>LMS | Home</title>
</head>

<body>
    <?php require_once Config::getViewPath("home", "header.view.php"); ?>

    <!-- Hero Section -->
    <div class="container-fluid p-0">
        <div class="row">
            <div class="col-12" style="background-image: url('<?php echo Config::getImagePath("test.jpg"); ?>'); height: 90vh; background-size: cover; background-position: center; background-repeat: no-repeat;">
                <div class="row vh-100 d-flex justify-content-center align-items-center">
                    <div class="col-12 col-lg-6 offset-lg-6 text-center text-white">
                        <h1 class="mb-4 fw-bold">Library LMS - Institute Name</h1>
                        <p class="fs-5">Register now to access our full range of features and <br> start exploring our vast collection of resources</p>
                        <div class="mt-4">
                            <button class="btn btn-dark rounded-5 px-4" onclick="window.location.href='<?php echo Config::indexPathMember() ?>?action=register'">Register</button>
                            <button class="btn btn-secondary ms-3 rounded-5 px-4" onclick="window.location.href='<?php echo Config::indexPathMember() ?>?action=login'">Login</button>

                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Top Books Section -->
    <div class="container-fluid bg-dark text-white py-5">
    <div class="row">
        <h1 class="text-start ms-4 fw-bold"><span class="text-danger">Top</span> Books</h1>
    </div>
    <div class="row justify-content-center mt-5">
        <!-- Card 1 -->
        <div class="col-md-3 mb-4">
            <div class="card">
                <img src="<?php echo Config::getImagePath("contact.jpg"); ?>" class="card-img-top" alt="Book 1">
                <div class="card-body">
                    <h5 class="card-title">Book Title 1</h5>
                    <p class="card-text">Quick description of the book goes here.</p>
                </div>
            </div>
        </div>
        <!-- Card 2 -->
        <div class="col-md-3 mb-4">
            <div class="card">
                <img src="<?php echo Config::getImagePath("contact.jpg"); ?>" class="card-img-top" alt="Book 2">
                <div class="card-body">
                    <h5 class="card-title">Book Title 2</h5>
                    <p class="card-text">Quick description of the book goes here.</p>
                </div>
            </div>
        </div>
        <!-- Card 3 -->
        <div class="col-md-3 mb-4">
            <div class="card">
                <img src="<?php echo Config::getImagePath("contact.jpg"); ?>" class="card-img-top" alt="Book 3">
                <div class="card-body">
                    <h5 class="card-title">Book Title 3</h5>
                    <p class="card-text">Quick description of the book goes here.</p>
                </div>
            </div>
        </div>
        <!-- Card 4 -->
        <div class="col-md-3 mb-4">
            <div class="card">
                <img src="<?php echo Config::getImagePath("contact.jpg"); ?>" class="card-img-top" alt="Book 4">
                <div class="card-body">
                    <h5 class="card-title">Book Title 4</h5>
                    <p class="card-text">Quick description of the book goes here.</p>
                </div>
            </div>
        </div>
        <!-- Card 5 -->
        <div class="col-md-3 mb-4">
            <div class="card">
                <img src="<?php echo Config::getImagePath("contact.jpg"); ?>" class="card-img-top" alt="Book 5">
                <div class="card-body">
                    <h5 class="card-title">Book Title 5</h5>
                    <p class="card-text">Quick description of the book goes here.</p>
                </div>
            </div>
        </div>
        <!-- Card 6 -->
        <div class="col-md-3 mb-4">
            <div class="card">
                <img src="<?php echo Config::getImagePath("contact.jpg"); ?>" class="card-img-top" alt="Book 6">
                <div class="card-body">
                    <h5 class="card-title">Book Title 6</h5>
                    <p class="card-text">Quick description of the book goes here.</p>
                </div>
            </div>
        </div>
        <div class="col-md-3 mb-4">
            <div class="card">
                <img src="<?php echo Config::getImagePath("contact.jpg"); ?>" class="card-img-top" alt="Book 6">
                <div class="card-body">
                    <h5 class="card-title">Book Title 6</h5>
                    <p class="card-text">Quick description of the book goes here.</p>
                </div>
            </div>
        </div>
        <div class="col-md-3 mb-4">
            <div class="card">
                <img src="<?php echo Config::getImagePath("contact.jpg"); ?>" class="card-img-top" alt="Book 6">
                <div class="card-body">
                    <h5 class="card-title">Book Title 6</h5>
                    <p class="card-text">Quick description of the book goes here.</p>
                </div>
            </div>
        </div>
    </div>
</div>


    <!-- Footer Section -->
    <div class="p-0 m-4" style="height: 100vh; background-image: url('<?php echo Config::getImagePath("quotes.jpg"); ?>'); background-size: cover; background-position: center;">
</div>


    <?php require_once Config::getViewPath("home", "footer.view.php"); ?>
    
    <script src="https://www.payhere.lk/lib/payhere.js"></script>

    <script src="<?php echo Config::getJsPath("memberRegister.js"); ?>"></script>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>
