<?php
require_once "../../main.php";
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>User Management</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
</head>

<body>
    <?php include "dash_header.php"; ?>

    <div class="d-flex bg-light">
        <div class="nav-bar vh-100">
            <?php include "dash_sidepanel.php"; ?>
        </div>
        <div class="container-fluid mx-5 mb-5 bg-white">
            <div class="row">
                <nav class="navbar p-4 navbar-light bg-light">
                    <span class="navbar-brand mb-0 h1">Dashboard <small class="text-muted">control panel</small></span>
                    <a href="#" class="text-decoration-none h5"><i class="fa fa-home"></i> Home</a>
                </nav>
            </div>
            <div class="row m-4">
                <div class="col-md-3 mt-2">
                        <input name="memberId" id="memberid" class="form-control" type="text" placeholder="Type Membership ID">
                </div>
                <div class="col-md-3 mt-2">
                    <input name="book_id" id="bookid" class="form-control" type="text" placeholder="Type Book ID">
                </div>
                <div class="col-md-6 mt-2">
                    <div class="d-flex">
                        <input name="title" id="title" class="form-control" type="text" placeholder="Type Book Title">
                        <button type="button" name="search" class="btn btn-primary mx-3 px-3"  onclick="loadReservations();"><i class="fa fa-search"></i></button>
                    </div>
                </div>
            </div>

            <div class="px-1">
                <!-- Table -->
                <table class="table table-bordered text-center">
                    <thead class="table-light">
                        <tr>
                            <th>#</th>
                            <th>Member ID</th>
                            <th>Book ID</th>
                            <th>Book Name</th>
                            <th>Reservation Date</th>
                            <th>Notified Date</th>
                            <th>Expiration Date</th>
                            <th>Status</th>

                        </tr>
                    </thead>
                    <tbody id="reservationTableBody">
                    </tbody>
                </table>
                <div id="pagination"></div>

            </div>

        </div>
    </div>


    <!-- Bootstrap and JavaScript -->
    <script src="<?php echo Config::getJsPath("reservation.js"); ?>"></script>
    <script src="<?php echo Config::getJsPath("pagination.js"); ?>"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>