<?php
require_once "../../main.php";
?>

<!DOCTYPE html>

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale = 1.0">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
    <title>Home</title>
    <style>
        .book {
            transition: transform 0.3s ease, opacity 0.3s ease;
        }

        .book:hover {
            transform: scale(1.05);
            /* Or any other transformation */
            opacity: 0.8;
        }
    </style>
</head>


<body onload="loadNewsUpdates();">
    

    <?php
    // Check if the session exists to decide which header to include
    if (isset($_SESSION['member'])) {
        // Logged-in user: Include the header for logged-in users
        require_once Config::getViewPath("member", "header.php");
    } else {
        // Not logged-in user: Include the header for guest users
        require_once Config::getViewPath("home", "header.view.php");
    }
    ?>
    <section class="bg-dark text-white pt-5 px-3 mt-3 d-none d-md-block w-100" style="background: url('<?php echo Config::getImagePath("home.jpg"); ?>') no-repeat center center/cover; height: 550px;">
        <h1 class="display-1 fw-bold">Welcome to</h1>
    </section>
    <section class="bg-dark text-white pt-5 px-3 mt-3 d-md-none" style="background: url('<?php echo Config::getImagePath("home.jpg"); ?>') no-repeat center center/cover; height: 350px;">
        <h1 class="display-1 fw-bold">Welcome to</h1>
    </section>

    <div class="container mx-auto my-5 d-block d-md-none">
        <h2 class="fs-1 mb-4" style="color: red;">Our Goal</h2>
        <div class="row align-items-center mb-4">
            <div class="col-md-6">
                <p class="px-3 p-md-1">At <?= $libraryName ?>, our goal is to create a user-friendly and efficient platform that simplifies library management and enhances the experience of accessing our vast collection of resources. Our system is designed to streamline the cataloging, borrowing, and returning of books, making it easier for library staff to manage inventory and for patrons to find the materials they need.</p>
            </div>
            <div class="col-md-6 d-flex justify-content-center">
                <img src="<?php echo Config::getImagePath("home-goal.jpg"); ?>" alt="Library Image 1" style="height: 400px;" class="img-fluid rounded">
            </div>
        </div>
        <div class="row align-items-center mt-4">
            <div class="col-md-6 order-md-2">
                <p class="px-3 p-md-1">Whether you're searching for the latest bestseller, researching a topic, or simply exploring new genres, our library management system ensures a seamless and enjoyable experience for all users. Join us in fostering a love for reading and learning in our community.</p>
            </div>
            <div class="col-md-6 order-md-1 d-flex justify-content-center">
                <img src="<?php echo Config::getImagePath("home-goal2.jpg"); ?>" alt="Library Image 2" style="height: 400px;" class="img-fluid rounded">
            </div>
        </div>
    </div>
    
    <div class="container mx-auto my-5 d-none d-md-block">
        <h2 class="fs-1 mb-4" style="color: red;">Our Goal</h2>
        <div class="row align-items-center mb-4">
            <div class="col-md-6">
                <p class="fs-5">At <?= $libraryName ?>, our goal is to create a user-friendly and efficient platform that simplifies library management and enhances the experience of accessing our vast collection of resources. Our system is designed to streamline the cataloging, borrowing, and returning of books, making it easier for library staff to manage inventory and for patrons to find the materials they need.</p>
            </div>
            <div class="col-md-6 d-flex justify-content-end">
                <img src="<?php echo Config::getImagePath("home-goal.jpg"); ?>" alt="Library Image 1" style="height: 500px;" class="img-fluid rounded">
            </div>
        </div>
        <div class="row align-items-center mt-4">
            <div class="col-md-6 order-md-2">
                <p class="fs-5">Whether you're searching for the latest bestseller, researching a topic, or simply exploring new genres, our library management system ensures a seamless and enjoyable experience for all users. Join us in fostering a love for reading and learning in our community.</p>
            </div>
            <div class="col-md-6 order-md-1">
                <img src="<?php echo Config::getImagePath("home-goal2.jpg"); ?>" alt="Library Image 2" style="height: 500px;" class="img-fluid rounded">
            </div>
        </div>
    </div>

    <section class="bg-light text-dark py-5">
        <div class="container">
            <h2 class="text-center text-warning mb-5">Latest News and Updates</h2>
            <div class="row g-4" id="news-container">
                <!-- News items will be dynamically inserted here -->
            </div>
        </div>
    </section>

    <div class="modal fade" id="newsModal" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="newsModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content text-center">
            <div class="d-flex justify-content-between align-items-center m-3">
                    <h3 class="mb-0">News Update</h3>
                    <i class="fa fa-close text-black" style="cursor: pointer;" data-bs-dismiss="modal" aria-label="Close"></i>
                </div>
                <div class="">
                    <h5 class="mb-0 text-primary" id="date"></h5>
                </div>
                <div class="">
                    <h2 class="mb-0 text-danger" id="title"></h2>
                </div>

                <div class="card m-4">
                    <div class="div">
                        <div class="p-4 text-center">
                            <div class="mb-3">
                                <img id="news-image" src="" alt="News Image" style="width: 250px; height: 300px; border-radius: 10px;">
                            </div>
                            <div class="div" id="description">

                            </div>
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </div>

    <?php
    // Footer
    require_once Config::getViewPath("home", "footer.view.php");
    ?>
    <script src="<?php echo Config::getJsPath("home.js"); ?>"></script>


    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/js/bootstrap.bundle.min.js"></script>
</body>