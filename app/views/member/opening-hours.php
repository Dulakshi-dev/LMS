<?php
    // Define variables for opening hours
    $weekdays_opening_hours = "8.00 am - 6.00 pm";
    $weekends_opening_hours = "8.00 am - 6.00 pm";
    $weekdays_lending_hours = "8.00 am - 6.00 pm";
    $weekends_lending_hours = "8.00 am - 6.00 pm";
    $weekdays_returning_hours = "8.00 am - 4.00 pm";
    $weekends_returning_hours = "8.00 am - 1.00 pm";
    $image_path = "img/opening_hours.png"; 
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Opening Hours</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>

<?php include "../home/header.view.php"; ?>

    <div class="container text-center my-5">
        <h1 class="display-4 mb-4">Opening Hours</h1>

        <div class="row mb-4">
            <div class="col">
                <h4>Opening Hours</h4>
                <table class="table table-bordered table-striped">
                    <thead>
                        <tr>
                            <th>Time</th>
                            <th>Weekdays</th>
                            <th>Weekends</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td>Opening Hours</td>
                            <td><?php echo $weekdays_opening_hours; ?></td>
                            <td><?php echo $weekends_opening_hours; ?></td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>

        <div class="row mb-4">
            <div class="col">
                <h4>Book Issuing</h4>
                <table class="table table-bordered table-striped">
                    <thead>
                        <tr>
                            <th>Time</th>
                            <th>Weekdays</th>
                            <th>Weekends</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td>Lending Books</td>
                            <td><?php echo $weekdays_lending_hours; ?></td>
                            <td><?php echo $weekends_lending_hours; ?></td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>

        <div class="row mb-5">
            <div class="col">
                <h4>Returning Books</h4>
                <table class="table table-bordered table-striped">
                    <thead>
                        <tr>
                            <th>Time</th>
                            <th>Weekdays</th>
                            <th>Weekends</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td>Returning Books</td>
                            <td><?php echo $weekdays_returning_hours; ?></td>
                            <td><?php echo $weekends_returning_hours; ?></td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

<?php include "../home/footer.view.php"; ?>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>

</body>
</html>
