<?php
$member_id = $_SESSION["member"]["member_id"];
$current_action = isset($_GET['action']) ? $_GET['action'] : 'dashboard'; // Get current action or default to 'dashboard'
?>


<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Sidebar</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css">
  <?php
$member_id = $_SESSION["member"]["member_id"];
$current_action = isset($_GET['action']) ? $_GET['action'] : 'dashboard';
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Sidebar</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css">

  <style>
    #sidepanel {
      width: 50px;
      min-height: 100vh;
      transition: width 0.3s;
    }

    #sidepanel:hover {
      width: 160px;
    }

    #sidepanel:hover .nav-text {
      display: block !important;
    }

    .nav-link {
      white-space: nowrap;
      overflow: hidden;
      border: 2px solid;
      border-color: red;
    }

    .nav-icon {
      width: 24px;
      text-align: center;
    }
  </style>
</head>

<body>

  <div id="sidepanel" class="bg-light text-dark d-flex flex-column p-2">

    <!-- Dashboard -->
    <a href="<?= Config::indexPathMember() ?>?action=dashboard"
       class="nav-link border-bottom border-top border-3 py-1 rounded my-2 d-flex align-items-center <?= $current_action == 'dashboard' ? 'bg-dark text-white' : 'text-dark'; ?>">
      <div class="nav-icon"><i class="fas fa-gauge"></i></div>
      <span class="nav-text ms-2 d-none">Dashboard</span>
    </a>

    <!-- Profile -->
    <a href="<?= Config::indexPathMember() ?>?action=profile"
       class="nav-link rounded border-bottom border-top border-3  py-1 my-2 d-flex align-items-center <?= $current_action == 'profile' ? 'bg-dark text-white' : 'text-dark'; ?>">
      <div class="nav-icon"><i class="fas fa-user"></i></div>
      <span class="nav-text ms-2 d-none">Profile</span>
    </a>

    <!-- My Library -->
    <a href="<?= Config::indexPathMember() ?>?action=viewMyLibrary"
       class="nav-link rounded border-bottom border-top border-3  py-1 my-2 d-flex align-items-center <?= $current_action == 'viewMyLibrary' ? 'bg-dark text-white' : 'text-dark'; ?>">
      <div class="nav-icon"><i class="fas fa-book"></i></div>
      <span class="nav-text ms-2 d-none">My Library</span>
    </a>

    <!-- Borrow History -->
    <a href="<?= Config::indexPathMember() ?>?action=viewborrowhistory"
       class="nav-link rounded border-bottom border-top border-3  py-1 my-2 d-flex align-items-center <?= $current_action == 'viewborrowhistory' ? 'bg-dark text-white' : 'text-dark'; ?>">
      <div class="nav-icon"><i class="fas fa-history"></i></div>
      <span class="nav-text ms-2 d-none">Borrow History</span>
    </a>

    <!-- Reserved Books -->
    <a href="<?= Config::indexPathMember() ?>?action=reservedbooks"
       class="nav-link rounded border-bottom border-top border-3  py-1 my-2 d-flex align-items-center <?= $current_action == 'reservedbooks' ? 'bg-dark text-white' : 'text-dark'; ?>">
      <div class="nav-icon"><i class="fas fa-bookmark"></i></div>
      <span class="nav-text ms-2 d-none">Reserved Books</span>
    </a>

    <!-- About Software -->
    <a href="<?= Config::indexPathMember() ?>?action=aboutsoftware"
       class="nav-link rounded border-bottom border-top border-3  py-1 my-2 d-flex align-items-center <?= $current_action == 'aboutsoftware' ? 'bg-dark text-white' : 'text-dark'; ?>">
      <div class="nav-icon"><i class="fas fa-laptop-code"></i></div>
      <span class="nav-text ms-2 d-none">About</span>
    </a>

    <!-- Spacer -->
    <div class="mt-auto"></div>

    <!-- User Profile -->
    <a href="<?= Config::indexPathMember() ?>?action=profile"
       class="nav-link rounded border-bottom border-top border-3  py-1 my-2 d-flex align-items-center <?= $current_action == 'aboutsoftware' ? 'bg-dark text-white' : 'text-dark'; ?>">
       <div class="d-flex  align-items-center mb-2">
      <img src="" alt="User" id="headerprofileimg" class="rounded-circle me-2" style="height: 30px; width: 30px;" />
      <span class="nav-text ms-2 d-none"><?= $fname . " " . $lname; ?></span>
    </div>
    </a>
    

    <!-- Logout -->
    <a href="index.php?action=logout"
       class="nav-link rounded border-bottom border-top border-3  py-1 d-flex align-items-center text-dark">
      <div class="nav-icon"><i class="fas fa-sign-out-alt"></i></div>
      <span class="nav-text ms-2 d-none">Logout</span>
    </a>

  </div>

  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>

</head>

<body>

  