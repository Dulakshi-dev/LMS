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

<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Navbar Example</title>
  <link
    href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.0/css/bootstrap.min.css"
    rel="stylesheet" />
  <link
    href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css"
    rel="stylesheet" />
</head>

<body onload="loadprofileimg('<?php echo addslashes($profile_img); ?>');">

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
          <a class="text-white text-decoration-none" href="#">
            <i class="fa fa-bell mr-3"></i>
          </a>
        </div>
        <div class="d-flex align-items-center me-2 px-3">
          <img
            src=""
            alt="User"
            id="headerprofileimg"
            class="rounded-circle me-2"
            style="height: 40px; width: 40px;" />
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
  <script src="<?php echo Config::getJsPath("test.js"); ?>"></script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.0/js/bootstrap.bundle.min.js"></script>
</body>

</html>