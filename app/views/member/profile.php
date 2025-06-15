<?php

if (!isset($_SESSION['member'])) {
  header("Location: index.php?action=login");
  exit;
}

// Session Timeout (30 minutes)
if (isset($_SESSION['member']['last_activity']) && (time() - $_SESSION['member']['last_activity'] > 1800)) {
  session_unset();  // Clear session data
  session_destroy();
  header("Location: index.php?action=login");
  exit;
}

// Reset last activity time (only if user is active)
$_SESSION['member']['last_activity'] = time();

$member_id = $_SESSION["member"]["member_id"];

?>

<!DOCTYPE html>
<html lang="en">

<?php
$pageTitle = "Profile";
require_once Config::getViewPath("common", "head.php");
?>

<body class="bg-white">
  <script>
    window.addEventListener('load', function() {
      loadProfileData('<?php echo $member_id; ?>');
    });
  </script>
  <?php require_once Config::getViewPath("member", "header.php"); ?>

  <div class="d-flex">
    <!-- Side Panel -->
    <div>
      <div class="nav-bar d-block d-lg-none">
        <?php require_once Config::getViewPath("member", "sm_sidepanel.php"); ?>
      </div>
      <div class="nav-bar d-none d-lg-block">
        <?php require_once Config::getViewPath("member", "sidepanel.php"); ?>
      </div>
    </div>

    <div class="flex-grow-1 flex-column bg-light m-3 m-md-5" id="box1">
      <div class="container my-4">
        <div class="row">
          <div class="col-12 text-center border-bottom border-danger border-4 mb-4">
            <h2 class="p-2">Update Details</h2>
          </div>
        </div>

        <div class="row">
          <div class="col-sm-12 col-lg-3 text-center p-4">
            <div class="mb-4">
              <img id="profileimg" style="height: 250px; width: 250px; border-radius: 50%;" src="" alt="Profile Picture">
            </div>
            <div class="mb-4">
              <input type="file" id="uploadprofimg" name="uploadprofimg" class="form-control" onchange="showProfilePreview()">
            </div>
            <div class="mb-4">
              <label for="member_id">Member ID</label>
              <input id="member_id" name="member_id" type="text" class="form-control" disabled>
            </div>
          </div>
          <hr class="d-block d-md-none">

          <div class="col-md-9 mx-auto px-lg-5">
            <form id="profileForm" onsubmit="updateProfileDetails(event); return false;">
              <div class="row">
                <div class="col-lg-6 col-sm-6 mb-3">
                  <label for="fname">First Name</label>
                  <input id="fname" name="fname" class="form-control" type="text">
                  <span class="text-danger" id="fnameerror"></span>
                </div>
                <div class="col-lg-6 col-sm-6 mb-3">
                  <label for="lname">Last Name</label>
                  <input id="lname" name="lname" class="form-control" type="text">
                  <span class="text-danger" id="lnameerror"></span>
                </div>
              </div>

              <div class="row">
                <div class="col-lg-6 col-sm-6 mb-3">
                  <label for="nic">NIC</label>
                  <input id="nic" name="nic" class="form-control" type="text" disabled>
                  <span class="text-danger" id="nicerror"></span>
                </div>
                <div class="col-lg-6 col-sm-6 mb-3">
                  <label for="phone">Mobile</label>
                  <input id="phone" name="phone" class="form-control" type="text">
                  <span class="text-danger" id="phoneerror"></span>
                </div>
              </div>
              <div class="col-lg-12 col-sm-12 mb-3">
                <label for="email">Email</label>
                <input id="email" name="email" class="form-control" type="email" disabled>
                <span id="emailerror" class="text-danger"></span>
              </div>
              <div class="mb-3">
                <label for="address">Address</label>
                <textarea id="address" name="address" class="form-control" rows="2"></textarea>
                <span class="text-danger" id="addresserror"></span>
              </div>

              <div class="d-flex justify-content-end gap-2 pt-lg-4 mt-3">
                <button type="button" class="btn btn-danger" onclick="goToChangePassword(event)">Reset Password</button>
                <button type="submit" class="btn btn-primary px-5">Save</button>
              </div>
            </form>
          </div>
        </div>
      </div>
    </div>

    <!-- Box 2: Current Password -->
    <div id="box2" class="d-none">
      <div class="container mt-5 p-4 bg-white rounded" style="width: 800px;">
        <h3 class="pb-3">Change Password</h3>
        <form id="currentPasswordForm" onsubmit="validateCurrentPassword('<?php echo $member_id; ?>', event); return false;">
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
        <form id="newPasswordForm" onsubmit="saveNewPassword('<?php echo $member_id; ?>', event); return false;">
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

          <div id="errormsg-new" class="text-danger mt-2"></div>

          <div class="d-flex justify-content-end py-3 my-4">
            <button type="button" class="btn btn-primary px-5 mx-4" onclick="goBackToCurrent()">Back</button>
            <button type="submit" class="btn btn-danger px-5">Save</button>
          </div>
        </form>
      </div>
    </div>
  </div>

  <?php require_once Config::getViewPath("common", "footer.php"); ?>

  <script src="<?php echo Config::getJsPath("memberProfile.js"); ?>"></script>
  <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.0/js/bootstrap.bundle.min.js"></script>
</body>
</html>