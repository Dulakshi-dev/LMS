<?php
// Reset last activity time
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
  .profile-dropdown {
    position: absolute;
    right: 0;
    top: 100%;
    width: 250px;
    background: #fff;
    border: 1px solid #dee2e6;
    border-radius: 0.5rem;
    box-shadow: 0 0.5rem 1rem rgba(0,0,0,.15);
    z-index: 1000;
    display: none;
  }

  .dropdown-wrapper:hover .profile-dropdown {
    display: block;
  }


  .notification-item.unread {
    font-weight: bold;
  }

  .notification-item.read {
    opacity: 0.5;
  }

  #notification-dropdown {
    max-height: 300px;
    overflow-y: auto;
    min-width: 300px;
  }

  .nav-link {
    color: white !important;
  }

  .navbar-toggler {
    border-color: rgba(255, 255, 255, 0.1);
  }
  .me{
    margin-right: 57px;
  }
</style>

<header>
  <nav class="navbar navbar-expand-lg navbar-dark bg-black">
    <div class="container-fluid">
      <!-- Logo -->
      <a class="navbar-brand d-flex align-items-center" href="#">
        <img src="<?= Config::indexPathMember() ?>?action=servelogo&image=<?= $logo ?>" alt="library logo" width="200" height="60">
      </a>

      <!-- Mobile Toggle -->
      <button
        class="navbar-toggler position-absolute top-0 end-0 mt-4 me-2"
        type="button"
        data-bs-toggle="collapse"
        data-bs-target="#navbarNav"
        aria-controls="navbarNav"
        aria-expanded="false"
        aria-label="Toggle navigation">
        <span class="navbar-toggler-icon"></span>
      </button>

      <!-- Nav Links -->
      <div class="collapse navbar-collapse justify-content-center" id="navbarNav">
        <ul class="navbar-nav">
          <li class="nav-item px-3">
            <a class="nav-link" href="<?= Config::indexPathMember() ?>?action=home">Home</a>
          </li>
          <li class="nav-item px-3">
            <a class="nav-link" href="<?= Config::indexPathMember() ?>?action=openhours">Opening Hours</a>
          </li>
          <li class="nav-item px-3">
            <a class="nav-link" href="<?= Config::indexPathMember() ?>?action=contact">Contact</a>
          </li>
          <li class="nav-item px-3">
            <a class="nav-link" href="<?= Config::indexPathMember() ?>?action=about">About</a>
          </li>
          <li class="nav-item px-3">
            <a class="nav-link" href="<?= Config::indexPathMember() ?>?action=dashboard">LMS</a>
          </li>
        </ul>
      </div>

      <!-- Right Section -->
      <div class="d-flex align-items-center">
        <!-- Notifications -->
        <div class="position-relative me-4">
          <a class="text-white text-decoration-none" href="#" id="notification-bell">
            <i class="fa fa-bell"></i>
            <span id="notification-count" class="badge bg-danger position-absolute top-0 start-100 translate-middle rounded-circle" style="display: none;"></span>
          </a>

          <div id="notification-dropdown" class="dropdown-menu dropdown-menu-end p-2 mt-2" style="display: none;">
            <p class="dropdown-header">Notifications</p>
            <div id="notification-list"></div>
            <p class="text-muted mb-0">Check your emails for full message.</p>
          </div>
        </div>

        <!-- Profile Dropdown -->
        <div class="dropdown-wrapper position-relative me me-lg-1">
          <a href="#" class="d-flex align-items-center text-white text-decoration-none">
            <img src="index.php?action=serveprofimage&image=<?= !empty($profile_img) ? $profile_img : 'user.jpg'; ?>"
                 alt="User" class="rounded-circle me-2" style="height: 40px; width: 40px;"><i class="fa fa-sort-down ms-1 d-lg-none"></i>
            <div class="d-none d-lg-block text-start me-2">
              <div><?= $fname . " " . $lname; ?></div>
              <small class="text-light">Member</small>
            </div>
            <i class="fa fa-sort-down ms-1 d-none d-lg-block"></i>
          </a>

          <div class="profile-dropdown mt-2">
            <div class="text-center border-bottom py-3">
              <img src="index.php?action=serveprofimage&image=<?= !empty($profile_img) ? $profile_img : 'user.jpg'; ?>"
                   alt="User" class="rounded-circle mb-2" style="height: 60px; width: 60px;">
              <h6 class="mb-0"><?= $fname . " " . $lname; ?></h6>
              <small class="text-muted">Member</small>
            </div>
            <div class="p-3 d-grid gap-2">
              <a href="<?= Config::indexPathMember() ?>?action=profile" class="btn btn-outline-primary">
                <i class="fas fa-user me-2"></i>My Profile
              </a>
              <a href="<?= Config::indexPathMember() ?>?action=logout" class="btn btn-outline-danger">
                <i class="fas fa-sign-out-alt me-2"></i>Sign Out
              </a>
            </div>
          </div>
        </div>
      </div>
    </div>
  </nav>
</header>

<script src="<?= Config::getJsPath("notification.js"); ?>"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.0/js/bootstrap.bundle.min.js"></script>
