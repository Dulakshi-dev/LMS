<?php

$user_id = $_SESSION["user"]["user_id"];
$role_name = $_SESSION["user"]["role_name"];

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Update Details</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
    <style>
        #box1 {
            display: block;
        }

        #box2 {
            display: none;
        }
    </style>
</head>

<body>
    <script>
        window.addEventListener('load', function() {
            loadProfileData('<?php echo $user_id; ?>');
        });
    </script>

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
                    <div id="box1">
                        <div class="container-fluid fw-bold bg-white d-flex justify-content-center">
                            <div class="row">
                                <div class="text-center border-bottom border-danger border-4">
                                    <h2 class="p-2">Update Details</h2>
                                </div>
                                <div class="row">
                                    <div class="col-md-4 text-center">
                                        <div class="m-4">

                                            <img class="rounded-circle" id="profileimg" style="height: 200px; width: 200px;" src="" alt="Profile Picture">
                                        </div>
                                        <div class="m-4">
                                            <input type="file" id="uploadprofimg" name="uploadprofimg" class="form-control" onchange="showProfilePreview()">
                                        </div>
                                        <div class="m-4">
                                            <label for="staff_id">Staff ID</label>
                                            <input id="staff_id" name="staff_id" type="text" class="form-control" disabled>
                                        </div>
                                    </div>
                                    <div class="col-md-8 p-4">
                                        <div class="row">
                                            <div class="col-lg-6 col-sm-6 form-group">
                                                <label for="fname">First Name</label>
                                                <input id="fname" name="fname" class="form-control" type="text">
                                            </div>
                                            <div class="col form-group">
                                                <label for="last-name">Last Name</label>
                                                <input id="lname" name="lname" class="form-control" type="text">
                                            </div>
                                        </div>


                                        <div class="row">
                                            <div class="col-lg-6 col-sm-6 my-3 form-group">
                                                <label for="nic">NIC</label>
                                                <input id="nic" name="nic" class="form-control" type="email" disabled>
                                            </div>
                                            <div class="col my-3 form-group">
                                                <label for="phone">Mobile</label>
                                                <input id="phone" name="phone" class="form-control" type="text">
                                            </div>
                                        </div>

                                        <div class="row">
                                            <div class="col my-3 form-group">
                                                <label for="email">Email</label>
                                                <input id="email" name="email" class="form-control" type="text" disabled>
                                            </div>
                                        </div>

                                        <div class="form-group my-3">
                                            <label for="address">Address</label>
                                            <textarea id="address" class="form-control" name="address" rows="2"></textarea>
                                        </div>

                                        <div class="d-flex justify-content-end">
                                            <button class="btn btn-danger mx-4 mt-4" onclick="dashboard_change_password(event)">Reset Password</button>
                                            <button class="btn btn-primary mt-4 px-4" onclick="updateProfileDetails()">Save</button>
                                        </div>
                                    </div>

                                </div>
                            </div>
                        </div>
                    </div>
                    <div id="box2">
                        <div class="container mt-5 p-4 bg-white rounded">
                            <h3 class="pb-3">Change Password</h3>
                            <p class="text-muted">Your new password must be between 8 and 15 characters in length</p>
                            <form action="<?php echo Config::indexPath() ?>?action=changepassword" method="POST">
                                <div class="row d-flex mt-5">
                                    <div class="col-12 col-md-4 mb-2"><label for="new-password">New Password</label></div>
                                    <div class="col-12 col-md-8"><input id="new-password" class="form-control" type="password"></div>
                                </div>

                                <div class="row d-flex my-4">
                                    <div class="col-12 col-md-4 mb-2"><label for="confirm-password">Confirm Password</label></div>
                                    <div class="col-12 col-md-8"><input id="confirm-password" class="form-control" type="password"></div>
                                </div>

                                <div id="errormsg" class="text-danger"></div>

                                <div class="d-flex justify-content-end py-3 my-4">
                                    <button class="btn btn-primary px-5 mx-4" onclick="dashboard_change_password(event)">Back</button>
                                    <button class="btn btn-danger px-5">Save</button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>

        </div>

        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
        <script src="<?php echo Config::getJsPath("profile.js"); ?>"></script>
        <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
</body>

</html>