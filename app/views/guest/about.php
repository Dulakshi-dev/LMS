<!DOCTYPE html>
<html lang="en">

<?php
$pageTitle = "About Us";
require_once Config::getViewPath("common","head.php");
?>

<body class="overflow-x-hidden">
<?php
    // Check if the session exists to decide which header to include
    if (isset($_SESSION['member'])) {
        // Logged-in user: Include the header for logged-in users
        require_once Config::getViewPath("member", "header.php");
    } else {
        // Not logged-in user: Include the header for guest users
        require_once Config::getViewPath("guest", "header.view.php");
    }
    ?>
    <div class="container-fluid text-light" 
        style="background-image: url('<?php echo Config::getImagePath("about-us.jpg"); ?>'); 
               background-size: cover; 
               background-repeat: no-repeat; 
               min-height: 100vh;">

        <div class="row justify-content-center align-items-center min-vh-100 px-3">
            <div class="col-lg-8 col-md-10 mt-5 bg-dark bg-opacity-75 border p-4 rounded d-none d-md-block">
                <h1 class="text-center">About Us</h1>
                <p class="fs-5 mt-4">Welcome to <strong><?= $libraryName ?></strong>, where knowledge meets convenience. Our library management system is dedicated to revolutionizing the way you interact with library resources. At <?= $libraryName ?>, we believe in the power of information and the joy of reading. Our mission is to provide an accessible, user-friendly platform that caters to the needs of both library staff and patrons.</p>
                <p class="fs-5 mt-4">Our system is designed with efficiency in mind, streamlining tasks such as cataloging, borrowing, and returning books. This allows our dedicated staff to focus on what matters most: helping you discover new knowledge and enjoy your reading journey. We offer a comprehensive catalog of books, e-books, and other resources, all easily searchable and accessible.</p>
                <p class="fs-5 mt-4">We are committed to continuous improvement and innovation, ensuring that our library management system remains at the forefront of technology. Our team works tirelessly to enhance the user experience, providing features that make managing and using library resources simpler and more efficient.</p>
            </div>
            
            <div class="col-lg-8 col-md-10 my-5 bg-dark bg-opacity-75 border p-4 rounded d-block d-md-none">
                <h1 class="text-center">About Us</h1>
                <p class="fs-6 mt-4">Welcome to <strong><?= $libraryName ?></strong>, where knowledge meets convenience. Our library management system is dedicated to revolutionizing the way you interact with library resources. At <?= $libraryName ?>, we believe in the power of information and the joy of reading. Our mission is to provide an accessible, user-friendly platform that caters to the needs of both library staff and patrons.</p>
                <p class="fs-6 mt-4">Our system is designed with efficiency in mind, streamlining tasks such as cataloging, borrowing, and returning books. This allows our dedicated staff to focus on what matters most: helping you discover new knowledge and enjoy your reading journey. We offer a comprehensive catalog of books, e-books, and other resources, all easily searchable and accessible.</p>
                <p class="fs-6 mt-4">We are committed to continuous improvement and innovation, ensuring that our library management system remains at the forefront of technology. Our team works tirelessly to enhance the user experience, providing features that make managing and using library resources simpler and more efficient.</p>
            </div>
            <div></div>
        </div>
    </div>

    <?php require_once Config::getViewPath("common", "footer.php"); ?>
</body>

</html>
