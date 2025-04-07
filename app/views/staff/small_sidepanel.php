<?php
if (!isset($_SESSION["staff"])) {
    header("Location: login.php");
    exit();
}

$role_name = $_SESSION["staff"]["role_name"];
$modules = $_SESSION["modules"] ?? [["name" => "No modules available", "icon" => "default.png"]];
$current_action = $_GET['action'] ?? 'dashboard';
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
    body {
      margin: 0;
      padding: 0;
    }

    #sidepanel {
      position: fixed;
      top: 0;
      left: 0;
      width: 50px;
      height: 100vh;
      background-color: #212529;
      color: white;
      z-index: 1000;
      transition: width 0.3s;
    }

    #sidepanel:hover {
      width: 200px;
    }

    #sidepanel:hover .nav-text {
      display: inline-block !important;
    }

    .nav-link {
      white-space: nowrap;
      overflow: hidden;
    }

    .nav-icon {
      width: 24px;
      text-align: center;
    }

    .nav-text {
      display: none;
    }

    /* Push main content away to avoid being hidden */
    .main-content {
      margin-left: 50px;
      transition: margin-left 0.3s;
    }

    /* Optional: shift slightly on hover if you want */
    #sidepanel:hover ~ .main-content {
      margin-left: 50px; /* stays the same; content won’t move */
    }


  /* Make the side panel sticky for small screens */
@media (max-width: 767px) {
  #sidepanel {
    position: sticky;
    top: 70px; /* Adjust this value based on the height of your header */
  }
}


  </style>
</head>

<body>

  <!-- Side Panel -->
  <div id="sidepanel" class="d-flex flex-column p-2">
    <!-- Role Indicator -->
    <div class="text-center mb-4 py-2 border-bottom">
      <span class="fw-bold fs-5"><?= $role_name == "Librarian" ? "L" : "S"; ?></span>
    </div>

    <!-- Dashboard -->
    <a href="<?= Config::indexPath() ?>?action=dashboard"
       class="nav-link rounded my-2 d-flex align-items-center <?= $current_action == 'dashboard' ? 'active bg-primary' : 'text-white'; ?>">
      <div class="nav-icon">
        <i class="fas fa-gauge"></i>
      </div>
      <span class="nav-text ms-2">Dashboard</span>
    </a>

    <!-- Profile -->
    <a href="<?= Config::indexPath() ?>?action=profile"
       class="nav-link rounded my-2 d-flex align-items-center <?= $current_action == 'profile' ? 'active bg-primary' : 'text-white'; ?>">
      <div class="nav-icon">
        <i class="fas fa-user"></i>
      </div>
      <span class="nav-text ms-2">Profile</span>
    </a>

    <!-- Dynamic Modules -->
    <?php foreach ($modules as $module):
      $action = strtolower(str_replace(' ', '', $module["name"]));
      $iconPath = Config::getImagePath($module["icon"]);
    ?>
      <a href="<?= Config::indexPath() ?>?action=<?= htmlspecialchars($action); ?>"
         class="nav-link rounded my-2 d-flex align-items-center <?= $current_action == $action ? 'active bg-primary' : 'text-white'; ?>">
        <div class="nav-icon">
          <img src="<?= htmlspecialchars($iconPath); ?>" alt="Module Icon" width="20">
        </div>
        <span class="nav-text ms-2"><?= htmlspecialchars($module["name"]); ?></span>
      </a>
    <?php endforeach; ?>

    <!-- Library Setup -->
    <a href="<?= Config::indexPath() ?>?action=libsetup"
       class="nav-link rounded my-2 d-flex align-items-center <?= $current_action == 'libsetup' ? 'active bg-primary' : 'text-white'; ?>">
      <div class="nav-icon">
        <i class="fas fa-university"></i>
      </div>
      <span class="nav-text ms-2">Library Setup</span>
    </a>

    <!-- About Software -->
    <a href="<?= Config::indexPath() ?>?action=aboutsoftware"
       class="nav-link rounded my-2 d-flex align-items-center <?= $current_action == 'aboutsoftware' ? 'active bg-primary' : 'text-white'; ?>">
      <div class="nav-icon">
        <i class="fas fa-laptop-code"></i>
      </div>
      <span class="nav-text ms-2">About</span>
    </a>

    <!-- Spacer to push logout to bottom -->
    <div class="mt-auto"></div>

    <!-- Logout -->
    <a href="logout.php"
       class="nav-link rounded d-flex align-items-center text-white">
      <div class="nav-icon">
        <i class="fas fa-sign-out-alt"></i>
      </div>
      <span class="nav-text ms-2">Logout</span>
    </a>
  </div>



  <!-- Bootstrap JS -->
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
