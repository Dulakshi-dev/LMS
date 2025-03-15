<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>About Us - Library</title>
    <link rel="stylesheet" href="bootstrap.css">
</head>

<body class="overflow-x-hidden">
    <?php require_once Config::getViewPath("home", "header.view.php"); ?>

    <div class="container-fluid text-light" 
        style="background-image: url('<?php echo Config::getImagePath("about-us.jpg"); ?>'); 
               background-size: cover; 
               background-repeat: no-repeat; 
               min-height: 120vh;">

        <div class="row justify-content-center align-items-center min-vh-100 px-3">
            <div class="col-lg-8 col-md-10 mt-5 bg-dark bg-opacity-50 p-4 rounded">
                <h1 class="text-center">About Us</h1>
                <p class="fs-5 mt-4">Welcome to <strong>SHELFLOOM</strong>, where knowledge meets convenience. Our library management system is dedicated to revolutionizing the way you interact with library resources. At SHELFLOOM, we believe in the power of information and the joy of reading. Our mission is to provide an accessible, user-friendly platform that caters to the needs of both library staff and patrons.</p>
                <p class="fs-5 mt-4">Our system is designed with efficiency in mind, streamlining tasks such as cataloging, borrowing, and returning books. This allows our dedicated staff to focus on what matters most: helping you discover new knowledge and enjoy your reading journey. We offer a comprehensive catalog of books, e-books, and other resources, all easily searchable and accessible.</p>
                <p class="fs-5 mt-4">We are committed to continuous improvement and innovation, ensuring that our library management system remains at the forefront of technology. Our team works tirelessly to enhance the user experience, providing features that make managing and using library resources simpler and more efficient.</p>
            </div>
            <div></div>
        </div>
    </div>

    <?php require_once Config::getViewPath("home", "footer.view.php"); ?>
</body>

</html>
