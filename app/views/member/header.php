<?php

// Reset last activity time (only if user is active)
$_SESSION['member']['last_activity'] = time();

$fname = $_SESSION["member"]["fname"];
$lname = $_SESSION["member"]["lname"];
$profile_img = $_SESSION["member"]["profile_img"];

$libraryData = HomeModel::getLibraryInfo();
$libraryName = $libraryData['name'];
$libraryAddress = $libraryData['address'];
$libraryEmail = $libraryData['email'];
$libraryPhone = $libraryData['mobile'];
$logo = $libraryData['logo'];
$fee = $libraryData['membership_fee'];
$fine = $libraryData['fine_amount'];
?>

<style>
  .notification-item.unread {
    font-weight: bold;
    /* Light red for unread */
  }

  .notification-item.read {
    opacity: 0.5;
  }
</style>


<nav class="navbar navbar-expand-lg navbar-dark bg-dark px-3">
  <div class="container-fluid">
    <a class="navbar-brand d-flex align-items-center" href="#">
      <img src="<?php echo Config::indexPathMember() ?>?action=servelogo&image=<?= $logo ?>" alt="library logo" width="200" height="60">
    </a>

    <div class="collapse navbar-collapse justify-content-center" id="navbarNav">
      <ul class="navbar-nav">
        <li class="nav-item px-4">
          <a class="nav-link" id="li" href="<?php echo Config::indexPathMember() ?>?action=home">Home</a>
        </li>
        <li class="nav-item px-4">
          <a class="nav-link" id="li" href="<?php echo Config::indexPathMember() ?>?action=openhours">Opening Hours</a>
        </li>
        <li class="nav-item px-4">
          <a class="nav-link" id="li" href="<?php echo Config::indexPathMember() ?>?action=contact">Contact</a>
        </li>
        <li class="nav-item px-4">
          <a class="nav-link" id="li" href="<?php echo Config::indexPathMember() ?>?action=about">About</a>
        </li>
        <li class="nav-item px-4">
          <a class="nav-link" id="li" href="<?php echo Config::indexPathMember() ?>?action=dashboard">LMS</a>
        </li>
      </ul>
    </div>

    <div
      class="d-flex align-items-center">
      <div class="position-relative me-3">
        <a class="text-white text-decoration-none" href="#" id="notification-bell">
          <i class="fa fa-bell mr-3"></i>
          <span id="notification-count" class="badge bg-danger position-absolute top-0 start-100 translate-middle rounded-circle" style="display: none;"></span>
        </a>

        <!-- Dropdown for Notifications -->


        <!-- Dropdown for Notifications -->
        <div id="notification-dropdown" class="dropdown-menu p-2" style="display: none; position: absolute; right: 0; max-height: 300px; overflow-y: auto;">
          <p class="dropdown-header">Notifications</p>
          <div id="notification-list"></div>
          <p>Check your emails for full message.</p>
        </div>



      </div>

      <div class="d-flex align-items-center me-2 px-3">

        <img src="index.php?action=serveprofimage&image=<?= !empty($profile_img) ? $profile_img : 'user.jpg'; ?>"
          alt="User" id="headerprofileimg" class="rounded-circle me-2" style="height: 40px; width: 40px;">

        <span class="text-white px-2"><?php echo $fname . " " . $lname; ?></span>
      </div>
      <button
        class="navbar-toggler"
        type="button"
        data-bs-toggle="collapse"
        data-bs-target="#navbarNav"
        aria-controls="navbarNav"
        aria-expanded="false"
        aria-label="Toggle navigation">
        <span class="navbar-toggler-icon"></span>
      </button>
    </div>

  </div>
</nav>

<script src="<?php echo Config::getJsPath("notification.js"); ?>"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.0/js/bootstrap.bundle.min.js"></script>