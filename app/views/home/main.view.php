<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Home body</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
</head>
<body class="bg-light">

    <section class="bg-dark text-white pt-5 px-3 mt-3" style="background: url('<?php echo Config::getImagePath("home.jpg"); ?>') no-repeat center center/cover; height: 550px;">
        <h1 class="display-1 fw-bold">Welcome to</h1>
    </section>

    <div class="container my-5">
        <h2 class="fs-1 mb-4" style="color: red;">Our Goal</h2>
        <div class="row align-items-center mb-4">
            <div class="col-md-6">
                <p class="fs-5">At [Library Name], our goal is to create a user-friendly and efficient platform that simplifies library management and enhances the experience of accessing our vast collection of resources. Our system is designed to streamline the cataloging, borrowing, and returning of books, making it easier for library staff to manage inventory and for patrons to find the materials they need.</p>
            </div>
            <div class="col-md-6 d-flex justify-content-end">
                <img src="<?php echo Config::getImagePath("home-goal.jpg"); ?>" alt="Library Image 1" style="height: 700px;" class="img-fluid rounded">
            </div>
        </div>
        <div class="row align-items-center">
            <div class="col-md-6 order-md-2">
                <p class="fs-5">Whether you're searching for the latest bestseller, researching a topic, or simply exploring new genres, our library management system ensures a seamless and enjoyable experience for all users. Join us in fostering a love for reading and learning in our community.</p>
            </div>
            <div class="col-md-6 order-md-1">
                <img src="<?php echo Config::getImagePath("home-goal2.jpg"); ?>" alt="Library Image 2" style="height: 700px;" class="img-fluid rounded">
            </div>
        </div>
    </div>

    <section class="bg-secondary text-white pb-4">
        <div class="container-fluid py-5">
            <h2 class="text-white mb-4">Latest News and Updates</h2>
            <div class="row p-4">
                <div class="col-md-4 mb-4">
                    <div class="card bg-dark text-white ">
                        <img src="<?php echo Config::getImagePath("home-goal.jpg"); ?>" style="height: 500px;" class="" alt="News Image 1">
                        <div class="card-body">
                            <h5 class="card-title">NOV 16</h5>
                            <p class="card-text">Text here</p>
                        </div>
                    </div>
                </div>
                <div class="col-md-4 mb-4">
                    <div class="card bg-dark text-white">
                        <img src="<?php echo Config::getImagePath("home-goal.jpg"); ?>" class="" style="height: 500px;" alt="News Image 2">
                        <div class="card-body">
                            <h5 class="card-title">NOV 16</h5>
                            <p class="card-text">Text here</p>
                        </div>
                    </div>
                </div>
                <div class="col-md-4 mb-4">
                    <div class="card bg-dark text-white">
                        <img src="<?php echo Config::getImagePath("home-goal.jpg"); ?>" class="" style="height: 500px;" alt="News Image 3">
                        <div class="card-body">
                            <h5 class="card-title">NOV 16</h5>
                            <p class="card-text">Text here</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
