<?php

if (!isset($_SESSION['staff'])) {
    header("Location: index.php");
    exit;
}

// Session Timeout (30 minutes)
if (isset($_SESSION['staff']['last_activity']) && (time() - $_SESSION['staff']['last_activity'] > 1800)) {
    session_unset();  // Clear session data
    session_destroy();
    header("Location: index.php");
    exit;
}

// Reset last activity time (only if user is active)
$_SESSION['staff']['last_activity'] = time();
?>

<!DOCTYPE html>
<html lang="en">

<?php
$pageTitle = "Reservations";
require_once Config::getViewPath("home","head.php");
?>

<body>
    <?php include "dash_header.php"; ?>

    <div class="d-flex bg-light">
        <div>
        <div class="h-100 d-none d-lg-block">
                <?php include "dash_sidepanel.php"; ?>
            </div>

            <div class="h-100 d-block d-lg-none">
                <?php include "small_sidepanel.php"; ?>
            </div>

        </div>
        <div class="container-fluid w-75 mb-5 bg-white ">
            <div class="row">
                <nav class="navbar p-md-4 navbar-light bg-light w-100">
                    <div class="d-flex align-items-center w-100 justify-content-between">
                        <span class="mb-0 h5">Issued Books</span>

                        <div class="d-flex align-items-center">
                            <button id="generateReport" class="btn btn-outline-dark me-3" onclick="generateReservedBookReport();">
                                <i class="fa fa-print"></i> Generate Report
                            </button>

                            <a href="#" class="text-decoration-none h5">
                                <i class="fa fa-home"></i>
                            </a>
                        </div>
                    </div>
                </nav>
            </div>
            <div class="row m-4">
                <div class="col-md-3 mt-2">
                    <input name="memberId" id="memberid" class="form-control" type="text" placeholder="Type Membership ID">
                </div>
                <div class="col-md-3 mt-2">
                    <input name="book_id" id="bookid" class="form-control" type="text" placeholder="Type Book ID">
                </div>
                <div class="col-md-4 mt-2">
                    <div class="d-flex">
                        <input name="title" id="title" class="form-control" type="text" placeholder="Type Book Title">
                        <button type="button" name="search" class="btn btn-primary mx-3 px-3" onclick="loadReservations();"><i class="fa fa-search"></i></button>
                    </div>
                </div>

                <div class="div col-md-2 d-flex mt-1">
                    <select class="form-select" id="statusSelection">
                        <option value="all">All</option>
                        <option value="Reserved">Reserved</option>
                        <option value="Collected">Collected</option>
                        <option value="Waitlst">Wait List</option>
                        <option value="Expired">Expired</option>
                        <option value="Canceled">Canceled</option>
                    </select>
                </div>
            </div>

            <div class="px-1 table-responsive">
                <!-- Table -->
                <table class="table table-bordered text-center" id="reserveBookTable">
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