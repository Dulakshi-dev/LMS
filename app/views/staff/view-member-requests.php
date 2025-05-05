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
$pageTitle = "Member Requests";
require_once Config::getViewPath("common","head.php");
?>

<body onload="loadMemberRequests(1,'Pending');">
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
                <nav class="navbar p-4 navbar-light bg-light">
                    <span class=" mb-0 h5">Member Requests</span>
                    <a href="#" class="text-decoration-none h5"><i class="fa fa-home"></i> </a>
                </nav>
            </div>
            <div class="row m-4">
                <div class="col-md-6 mt-2">
                    <input name="nic" id="nic" class="form-control" type="text" placeholder="Type NIC">
                </div>
                <div class="col-md-6 mt-2">
                    <div class="d-flex">
                        <input name="userName" id="userName" class="form-control" type="text" placeholder="Type User Name">
                        <button type="button" name="search" class="btn btn-primary mx-3 px-3" onclick="loadMemberRequests(1,'Pending');"><i class="fa fa-search"></i></button>
                    </div>
                </div>
            </div>

            <div class="px-1 table-responsive">
                <table class="table">
                    <thead class="thead-light text-center">
                        <tr>
                            <th>NIC</th>
                            <th>User's Name</th>
                            <th>Address</th>
                            <th>Phone Number</th>
                            <th>Email</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody id="requestTableBody">
                       
                    </tbody>
                </table>
            </div>

            <div id="pagination"></div>

        </div>
    </div>
    <?php require_once Config::getViewPath("staff", "footer.php"); ?>

    <!-- Bootstrap and JavaScript -->
    <script src="<?php echo Config::getJsPath("member.js"); ?>"></script>
    <script src="<?php echo Config::getJsPath("pagination.js"); ?>"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>