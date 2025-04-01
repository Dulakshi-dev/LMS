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

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>User Management</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
</head>

<body onload="loadPayments();">
    <?php include "dash_header.php"; ?>

    <div class="d-flex bg-light">
        <div class="nav-bar vh-100">
            <?php include "dash_sidepanel.php"; ?>
        </div>
        <div class="container-fluid mx-5 mb-5 bg-white">

            <div class="row">
                <nav class="navbar p-4 navbar-light bg-light">
                    <span class="navbar-brand mb-0 h1">Membership Payments</span>
                    <a href="#" class="text-decoration-none h5"><i class="fa fa-home"></i> Home</a>
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

            <div class="px-1">
                <table class="table">
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

    <!-- Bootstrap and JavaScript -->
    <script src="<?php echo Config::getJsPath("staffPayment.js"); ?>"></script>
    <script src="<?php echo Config::getJsPath("pagination.js"); ?>"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>