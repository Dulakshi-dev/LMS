<!DOCTYPE html>
<html lang="en">

<?php
$pageTitle = "Opening Hours";
require_once Config::getViewPath("home","head.php");
?>

<body class="bg-light" onload="loadOpeningHours();">

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

    <div class="container-fluid bg-dark text-white text-center d-flex align-items-center justify-content-center"
        style="background-image: url('<?php echo Config::getImagePath('open-hour.jpg'); ?>'); 
           background-size: cover; 
           background-position: center; 
           height: 600px;">
        <h1 class="display-3 bg-dark bg-opacity-50 p-3 rounded-3">Library Opening Hours</h1>
    </div>

    <!-- Table Section -->
    <div class="container my-5">
        <h2 class="text-center pb-4">Library Opening & Service Hours</h2>
        <div class="my-4">
            <table class="table table-bordered table-striped text-center">
                <thead class="table-dark fs-4">
                    <tr>
                        <th>Day</th>
                        <th>FROM</th>
                        <th>TO</th>
                    </tr>
                </thead>
                
                <tbody class="fs-5">
                    <tr>
                        <td>Week Days</td>
                        <td id="weekdayfrom"></td>
                        <td id="weekdayto"></td>
                    </tr>
                    <tr>
                        <td class="text-primary">Week Ends</td>
                        <td id="weekendfrom"></td>
                        <td id="weekendto"></td>
                    </tr>
                    <tr>
                        <td class="text-danger">Holidays</td>
                        <td id="holidayfrom"></td>
                        <td id="holidayto"></td>
                    </tr>
                </tbody>
            </table>
        </div>

    </div>

    <?php
    require_once Config::getViewPath("home", "footer.view.php");
    ?>

<script src="<?php echo Config::getJsPath("home.js"); ?>"></script>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>