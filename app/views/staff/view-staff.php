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
</head>

<body onload="loadUsers(1,'Active');">
    <?php include "dash_header.php"; ?>

    <div class="d-flex bg-light">
    <div>
            <!-- Large and Medium Screens -->
            <div class="d-none d-md-block">
                <?php include "dash_sidepanel.php"; ?>
            </div>

            <!-- Small Screens Only -->
            <div class="d-block d-md-none">
                <?php include "small_sidepanel.php"; ?>
            </div>
        </div>
        <div class="container-fluid mx-5 mb-5 bg-white ">
            <div class="row">
                <nav class="navbar p-4 navbar-light bg-light">
                    <span class="navbar-brand mb-0 h1">Staff Management <small class="text-muted">control panel</small></span>
                    <a href="#" class="text-decoration-none h5"><i class="fa fa-home"></i> Home</a>
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
                        <button type="button" name="search" class="btn btn-primary mx-3 px-3" onclick="loadUsers(1,'Active');"><i class="fa fa-search"></i></button>
                    </div>
                </div>
            </div>

            <div class="px-1">
                <table class="table">
                    <thead class="thead-light text-center">
                        <tr>
                            <th>Staff ID</th>
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


    <!-- Modal Update details-->
    <div class="modal fade" id="updateDetailsModal" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content p-3">
                <div class="d-flex justify-content-between align-items-center m-3">
                    <h3 class="mb-0">Edit User Detail</h3>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>

                </div>
                <div class="border border-2"></div>
                <div class="p-3">
                    <div class="row">
                        <div class="col-md-6">
                            <label for="userID">Membership ID</label>
                            <input type="text" class="form-control" id="userID" value="" disabled>
                        </div>
                        <div class="col-md-6">
                            <label for="nic">NIC</label>
                            <input type="text" class="form-control" id="NIC" value="">
                            <span id="nicError" class="text-danger"></span> <!-- NIC Error -->
                        </div>
                    </div>
                    <div class="my-2">
                        <label for="userName">User's Name</label>
                        <input type="text" class="form-control" id="username" value="">
                        <span id="usernameError" class="text-danger"></span> <!-- Username Error -->
                    </div>
                    <div class="row my-2">
                        <div class="col-md-6">
                            <label for="email">Email</label>
                            <input type="email" class="form-control" id="email" value="">
                            <span id="emailError" class="text-danger"></span> <!-- Email Error -->
                        </div>
                        <div class="col-md-6">
                            <label for="phoneNumber">Phone Number</label>
                            <input type="tel" class="form-control" id="phoneNumber" value="">
                            <span id="phoneError" class="text-danger"></span> <!-- Phone Error -->
                        </div>
                    </div>
                    <div class="my-2">
                        <label for="address">Address</label>
                        <textarea class="form-control" id="address" rows="3"></textarea>
                        <span id="addressError" class="text-danger"></span> <!-- Address Error -->
                    </div>
                    <div class="d-flex justify-content-end">
                        <button type="button" class="btn btn-primary mt-3 px-4" onclick="updateUserDetails();">Update User Details</button>
                    </div>
                </div>

            </div>
        </div>
    </div>

    <!-- Modal Mail-->
    <div class="modal fade" id="mailModal" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="d-flex justify-content-between align-items-center m-3">
                    <h3 class="mb-0">Send Email</h3>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="border border-2"></div>
                <div class="p-4">
                    <div class="mb-3">
                        <label for="name" class="form-label">User Name</label>
                        <input type="text" class="form-control" id="name" name="name" value="" disabled>
                    </div>
                    <div class="mb-3">
                        <label for="email" class="form-label">User Email</label>
                        <input type="text" class="form-control" id="emailadd" name="emailadd" value="" disabled>
                    </div>
                    <div class="mb-3">
                        <label for="subject" class="form-label">Subject</label>
                        <input type="text" class="form-control" id="subject" name="subject">
                        <span id="subjectError" class="text-danger"></span> <!-- Subject error message -->
                    </div>
                    <div class="mb-3">
                        <label for="message" class="form-label">Message</label>
                        <textarea class="form-control" id="message" name="message" rows="3"></textarea>
                        <span id="messageError" class="text-danger"></span> <!-- Message error message -->
                    </div>
                    <div class="text-end">
                        <button type="button" class="btn btn-primary px-4 mt-3" onclick="sendEmail()">Send</button>
                    </div>
                </div>

            </div>
        </div>
    </div>


    <!-- Bootstrap and JavaScript -->
    <script src="<?php echo Config::getJsPath("staff.js"); ?>"></script>
    <script src="<?php echo Config::getJsPath("pagination.js"); ?>"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>