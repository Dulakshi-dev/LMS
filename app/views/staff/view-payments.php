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
$pageTitle = "Payments";
require_once Config::getViewPath("common","head.php");
?>

<body onload="loadPayments();">
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
        <div class="container-fluid w-75  mb-5 bg-white">

            <div class="row">
                <nav class="navbar p-md-4 navbar-light bg-light w-100">
                    <div class="d-flex align-items-center w-100 justify-content-between">
                        <span class="mb-0 h5">Payment Management</span>

                        <div class="d-flex align-items-center">
                            <button id="generateReport" class="btn btn-outline-dark me-3" onclick="generatePaymentReport();">
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
                <div class="col-md-6 mt-2">
                    <input name="memberId" id="memberId" class="form-control" type="text" placeholder="Membership ID">
                </div>
                <div class="col-md-6 mt-2">
                    <div class="d-flex">
                        <input name="transactionId" id="transactionId" class="form-control" type="text" placeholder="Transaction ID">
                        <button type="submit" name="search" class="btn btn-primary mx-3 px-3" onclick="loadPayments();"><i class="fa fa-search"></i></button>
                    </div>
                </div>
            </div>

            <div class="table-responsive">
                <table class="table table-bordered text-center" id="paymentTable">
                    <thead class="thead-light text-center">
                        <tr>
                            <th>Transaction ID</th>
                            <th>Member ID</th>
                            <th>Amount</th>
                            <th>Payed At</th>
                            <th>Next Due Date</th>
                        </tr>
                    </thead>
                    <tbody id="paymentTableBody">

                    </tbody>
                </table>
            </div>
            <div id="pagination"></div>

        </div>
    </div>
    <?php require_once Config::getViewPath("staff", "footer.php"); ?>

    <!-- Bootstrap and JavaScript -->
    <script src="<?php echo Config::getJsPath("staffPayment.js"); ?>"></script>
    <script src="<?php echo Config::getJsPath("pagination.js"); ?>"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>