<?php
// Define variables for opening hours
$weekdays_opening_hours = "8.00 am - 6.00 pm";
$weekends_opening_hours = "8.00 am - 6.00 pm";
$weekdays_lending_hours = "8.00 am - 6.00 pm";
$weekends_lending_hours = "8.00 am - 6.00 pm";
$weekdays_returning_hours = "8.00 am - 4.00 pm";
$weekends_returning_hours = "8.00 am - 1.00 pm";
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Library Opening Hours</title>
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>

<body class="bg-light">

    <?php
    require_once Config::getViewPath("home", "header.view.php");
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
                <thead class="table-dark fs-3">
                    <tr>
                        <th>Time</th>
                        <th>Weekdays</th>
                        <th>Weekends</th>
                    </tr>
                </thead>
                <tbody class="fs-5">
                    <tr>
                        <td>Opening Hours</td>
                        <td><?php echo $weekdays_opening_hours; ?></td>
                        <td><?php echo $weekends_opening_hours; ?></td>
                    </tr>
                    <tr>
                        <td class="text-primary">Lending Books</td>
                        <td><?php echo $weekdays_lending_hours; ?></td>
                        <td><?php echo $weekends_lending_hours; ?></td>
                    </tr>
                    <tr>
                        <td class="text-danger">Returning Books</td>
                        <td><?php echo $weekdays_returning_hours; ?></td>
                        <td><?php echo $weekends_returning_hours; ?></td>
                    </tr>
                </tbody>
            </table>
        </div>

    </div>

    <?php
    require_once Config::getViewPath("home", "footer.view.php");
    ?>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>