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

$user_id = $_SESSION["staff"]["staff_id"];
$role_name = $_SESSION["staff"]["role_name"];

?>

<!DOCTYPE html>
<html lang="en">

<?php
$pageTitle = "Profile";
$pageCss = "profile.css";
require_once Config::getViewPath("common", "head.php");
?>

<body>
    <script>
        window.addEventListener('load', function() {
            loadProfileData('<?php echo $user_id; ?>');
        });
    </script>

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

        <div class="container-fluid mx-md-5 mb-5 bg-white">
            <div class="row">
                <nav class="navbar p-4 navbar-light bg-light">
                    <span class="navbar-brand mb-0 h1">Profile </span>
                    <a href="#" class="text-decoration-none h5"><i class="fa fa-home"></i></a>
                </nav>
            </div>
            
            <!-- Box 1: Profile Details -->
            <div id="box1">
                <div class="container-fluid fw-bold bg-white d-flex justify-content-center">
                    <div class="row">
                        <div class="text-center border-bottom border-danger border-4">
                            <h2 class="p-2">Update Details</h2>
                        </div>
                        <div class="row">
                            <div class="col-lg-4 col-md-12 text-center">
                                <div class="m-4">
                                    <img class="rounded-circle" id="profileimg" style="height: 200px; width: 200px;" src="" alt="Profile Picture">
                                </div>
                                <div class="m-4">
                                    <input type="file" id="uploadprofimg" name="uploadprofimg" class="form-control" onchange="showProfilePreview()">
                                    <span id="uploadprofimg_error" class="text-danger"></span>
                                </div>
                                <div class="m-4">
                                    <label for="staff_id">Staff ID</label>
                                    <input id="staff_id" name="staff_id" type="text" class="form-control" disabled>
                                </div>
                            </div>
                            <div class="col-md-8 p-4">
                                <form id="profileForm" onsubmit="updateProfileDetails(event); return false;">
                                    <div class="row">
                                        <div class="col-lg-6 col-sm-6 form-group">
                                            <label for="fname">First Name</label>
                                            <input id="fname" name="fname" class="form-control" type="text">
                                            <span id="fname_error" class="text-danger"></span>
                                        </div>
                                        <div class="col form-group">
                                            <label for="lname">Last Name</label>
                                            <input id="lname" name="lname" class="form-control" type="text">
                                            <span id="lname_error" class="text-danger"></span>
                                        </div>
                                    </div>

                                    <div class="row">
                                        <div class="col-lg-6 col-sm-6 my-3 form-group">
                                            <label for="nic">NIC</label>
                                            <input id="nic" name="nic" class="form-control" type="text" disabled>
                                        </div>
                                        <div class="col my-3 form-group">
                                            <label for="phone">Mobile</label>
                                            <input id="phone" name="phone" class="form-control" type="text">
                                            <span id="phone_error" class="text-danger"></span>
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
                                        <span id="address_error" class="text-danger"></span>
                                    </div>

                                    <div class="d-flex justify-content-end">
                                        <button type="button" class="btn btn-danger mx-4 mt-4" onclick="goToChangePassword(event)">Reset Password</button>
                                        <button type="submit" class="btn btn-primary mt-4 px-4">Save</button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            
            <!-- Box 2: Current Password -->
            <div id="box2" class="d-none">
                <div class="container mt-5 p-4 bg-white rounded" style="width: 800px;">
                    <h3 class="pb-3">Change Password</h3>
                    <form id="currentPasswordForm" onsubmit="validateCurrentPassword('<?php echo $user_id; ?>', event); return false;">
                        <div class="row d-flex mt-5">
                            <div class="col-12 col-md-4 mb-2"><label for="currentpassword">Current Password</label></div>
                            <div class="col-12 col-md-8">
                                <input id="currentpassword" name="currentpassword" class="form-control" type="password">
                            </div>
                        </div>

                        <div id="errormsgcurrent" class="text-danger mt-2"></div>

                        <div class="d-flex justify-content-end py-3 my-4">
                            <button type="button" class="btn btn-primary px-5 mx-4" onclick="goBack()">Back</button>
                            <button type="submit" class="btn btn-danger px-5">Next</button>
                        </div>
                    </form>
                </div>
            </div>

            <!-- Box 3: New Password -->
            <div id="box3" class="d-none">
                <div class="container mt-5 p-4 bg-white rounded" style="width: 800px;">
                    <h3 class="pb-3">Change Password</h3>
                    <p class="text-muted">Your new password must be between 8 and 15 characters in length</p>
                    <form id="newPasswordForm" onsubmit="saveNewPassword('<?php echo $user_id; ?>', event); return false;">
                        <div class="row d-flex mt-5">
                            <div class="col-12 col-md-4 mb-2"><label for="new-password">New Password</label></div>
                            <div class="col-12 col-md-8">
                                <input id="new-password" class="form-control" type="password">
                                <span id="new-password-error" class="text-danger"></span>
                                <div id="passwordRulesContainer" class="mt-2" style="display: none;">
                                    <ul class="list-unstyled mb-0">
                                        <li id="rule-length" class="text-danger">At least 8 characters</li>
                                        <li id="rule-uppercase" class="text-danger">At least one uppercase letter</li>
                                        <li id="rule-lowercase" class="text-danger">At least one lowercase letter</li>
                                        <li id="rule-digit" class="text-danger">At least one number</li>
                                        <li id="rule-special" class="text-danger">At least one special character</li>
                                    </ul>
                                </div>
                            </div>
                        </div>

                        <div class="row d-flex my-4">
                            <div class="col-12 col-md-4 mb-2"><label for="confirm-password">Confirm Password</label></div>
                            <div class="col-12 col-md-8">
                                <input id="confirm-password" class="form-control" type="password">
                                <span id="confirm-password-error" class="text-danger"></span>
                            </div>
                        </div>

                        <div class="d-flex justify-content-end py-3 my-4">
                            <button type="button" class="btn btn-primary px-5 mx-4" onclick="goBackToCurrent()">Back</button>
                            <button type="submit" class="btn btn-danger px-5">Save</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <?php require_once Config::getViewPath("staff", "footer.php"); ?>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <script src="<?php echo Config::getJsPath("staffProfile.js"); ?>"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
</body>
</html>