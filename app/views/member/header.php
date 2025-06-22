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
    box-shadow: 0 0.5rem 1rem rgba(0, 0, 0, .15);
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
    border: gray 1px solid;
    max-height: 200px;
    overflow-y: auto;
    width: 350px;
    top: 50px;
    right: -130px;

    /* Hide scrollbar for WebKit (Chrome, Safari) */
    scrollbar-width: none;
    /* Firefox */
    -ms-overflow-style: none;
    /* Internet Explorer 10+ */
  }

  #notification-dropdown::-webkit-scrollbar {
    display: none;
    /* Chrome, Safari and Opera */
  }

  .bgcolor {
    background-color: rgb(255, 255, 255);
  }



  .nav-link {
    color: white !important;
  }

  .nav-link.active {
    text-decoration: underline;
    text-underline-offset: 4px;
    text-decoration-thickness: 2px;
  }


  .navbar-toggler {
    border-color: rgba(255, 255, 255, 0.1);
  }

  .me {
    margin-right: 57px;
  }
</style>

<nav class="py-1 bg-black">
  <div class="bg-black d-flex align-items-center justify-content-between">
    <!-- left -->
    <div>
      <a class="navbar-brand d-flex align-items-center" href="<?php echo Config::indexPathMember() ?>?action=home">
        <img src="<?= Config::indexPathMember() ?>?action=servelogo&image=<?= $logo ?>" alt="library logo" width="200" height="60">
      </a>
    </div>

    <!-- center -->
    <div class="d-none d-md-block">
      <ul class="navbar-nav flex-row flex-wrap text-center">
        <li class="nav-item px-3">
          <a class="nav-link <?= (isset($_GET['action']) && $_GET['action'] == 'home') ? 'active' : '' ?>" href="<?= Config::indexPathMember() ?>?action=home">Home</a>
        </li>
        <li class="nav-item px-3">
          <a class="nav-link <?= (isset($_GET['action']) && $_GET['action'] == 'openhours') ? 'active' : '' ?>" href="<?= Config::indexPathMember() ?>?action=openhours">Opening Hours</a>

        </li>
        <li class="nav-item px-3">
          <a class="nav-link <?= (isset($_GET['action']) && $_GET['action'] == 'contact') ? 'active' : '' ?>" href="<?= Config::indexPathMember() ?>?action=contact">Contact</a>
        </li>
        <li class="nav-item px-3">
          <a class="nav-link <?= (isset($_GET['action']) && $_GET['action'] == 'about') ? 'active' : '' ?>" href="<?= Config::indexPathMember() ?>?action=about">About</a>
        </li>
      </ul>
    </div>

    <!-- right -->
    <div class="">
      <div class="d-flex align-items-center gap-3">


        <!-- Notifications -->
        <div class="position-relative me-md-4">
          <a class="text-white text-decoration-none" href="#" id="notification-bell">
            <i class="fa fa-bell"></i>
            <span id="notification-count" class="badge bg-danger position-absolute top-0 start-100 translate-middle rounded-circle" style="display: none;"></span>
          </a>

          <div id="notification-dropdown" class="dropdown-menu dropdown-menu-end p-2 mt-2">
            <p class="dropdown-header">Notifications</p>
            <div id="notification-list"></div>
            <p class="text-muted mb-0">Check your emails for full message.</p>
          </div>
        </div>



        <!-- Profile -->
        <div class="dropdown-wrapper position-relative">
          <a href="#" class="d-flex align-items-center text-white text-decoration-none gap-1">
            <img src="index.php?action=serveprofimage&image=<?= !empty($profile_img) ? $profile_img : 'user.jpg'; ?>" alt="User" class="rounded-circle" style="height: 40px; width: 40px;">
            <i class="fa fa-sort-down ms-1"></i>
          </a>

          <div class="profile-dropdown mt-2">
            <div class="text-center border-bottom py-3">
              <img src="index.php?action=serveprofimage&image=<?= !empty($profile_img) ? $profile_img : 'user.jpg'; ?>" alt="User" class="rounded-circle mb-2" style="height: 60px; width: 60px;">
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

        <!-- LMS -->
        <div>
          <a class="nav-link text-danger <?= (isset($_GET['action']) && $_GET['action'] == 'dashboard') ? 'bgcolor px-2 mx-2 px-md-3 rounded py-1' : '' ?>" href="<?= Config::indexPathMember() ?>?action=dashboard">LMS</a>

        </div>
      </div>
    </div>
  </div>

  <div class="d-block d-md-none">
    <ul class="navbar-nav bg-black flex-row justify-content-around text-center w-100 ">
      <li class="nav-item">
        <a class="nav-link px-3 text-white <?=  (isset($_GET['action']) && $_GET['action'] == 'home') ? 'active' : '' ?>" href="<?= Config::indexPathMember() ?>?action=home">Home</a>
      </li>
      <li class="nav-item">
        <a class="nav-link px-3 text-white <?=  (isset($_GET['action']) && $_GET['action'] == 'openhours') ? 'active' : '' ?>" href="<?= Config::indexPathMember() ?>?action=openhours">Opening Hours</a>
      </li>
      <li class="nav-item">
        <a class="nav-link px-3 text-white <?=  (isset($_GET['action']) &&$_GET['action'] == 'contact') ? 'active' : '' ?>" href="<?= Config::indexPathMember() ?>?action=contact">Contact</a>
      </li>
      <li class="nav-item">
        <a class="nav-link px-3 text-white <?=  (isset($_GET['action']) &&$_GET['action'] == 'about') ? 'active' : '' ?>" href="<?= Config::indexPathMember() ?>?action=about">About</a>
      </li>
    </ul>
  </div>

</nav>

<script src="<?= Config::getJsPath("notification.js"); ?>"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.0/js/bootstrap.bundle.min.js"></script>