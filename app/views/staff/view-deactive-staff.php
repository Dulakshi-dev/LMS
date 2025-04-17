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

$totalPages = $totalPages ?? 1;
$page = $page ?? 1;
?>


<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>User Management</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
    <style>
        .nav-bar {
            height: 100%;
        }
    </style>
</head>

<body onload="loadUsers(1,'Deactive');">
    <?php include "dash_header.php"; ?>

    <div class="d-flex bg-light">
        <div>
            <div class="nav-bar d-none d-lg-block">
                <?php include "dash_sidepanel.php"; ?>
            </div>

            <div class="nav-bar d-block d-lg-none">
                <?php include "small_sidepanel.php"; ?>
            </div>

        </div>
        <div class="container-fluid mb-5 bg-white w-75 ">
            <div class="row">
                <nav class="navbar p-md-4 navbar-light bg-light w-100">
                    <div class="d-flex align-items-center w-100 justify-content-between">
                        <span class="mb-0 h5">Deactive Staff Members</span>

                        <div class="d-flex align-items-center">
                            <button id="generateReport" class="btn btn-outline-dark me-3" onclick="generateDeactiveStaffReport();">
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
                    <input id="memberId" name="memberId" class="form-control" type="text" placeholder="Type Staff ID">
                </div>
                <div class="col-md-3 mt-2">
                    <input id="nic" name="nic" class="form-control" type="text" placeholder="Type NIC">
                </div>
                <div class="col-md-6 mt-2">
                    <div class="d-flex">
                        <input id="userName" name="userName" class="form-control" type="text" placeholder="Type Staff Name">
                        <button type="button" name="search" class="btn btn-primary mx-3 px-3" onclick="loadUsers(1,'Deactive');"><i class="fa fa-search"></i></button>
                    </div>
                </div>
                </form>
            </div>

            <div class=" table-responsive">
                <table class="table" id="staffTable">
                    <thead class="thead-light text-center">
                        <tr>
                            <th>Satff ID</th>
                            <th>NIC</th>
                            <th>User's Name</th>
                            <th>Address</th>
                            <th>Phone Number</th>
                            <th>Email</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody id="userTableBody">

                    </tbody>
                </table>
            </div>

            <div id="pagination"></div>

        </div>
    </div>

    <!-- Bootstrap and JavaScript -->
    <script src="<?php echo Config::getJsPath("staff.js"); ?>"></script>
    <script src="<?php echo Config::getJsPath("pagination.js"); ?>"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>