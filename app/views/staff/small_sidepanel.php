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
    #sidepanel {
      width: 50px;
      min-height: 100vh;
      transition: width 0.3s;
      position: sticky;
      top: 0;
    }

    #sidepanel:hover {
      width: 220px;
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

    .bg {
      background: rgba(26, 50, 65, 1);
    }
  </style>
</head>

<body>

  <div id="sidepanel" class="bg text-white d-flex flex-column p-2">
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
      <span class="nav-text ms-2 d-none">Dashboard</span>
    </a>

    <!-- Profile -->
    <a href="<?= Config::indexPath() ?>?action=profile"
      class="nav-link rounded my-2 d-flex align-items-center <?= $current_action == 'profile' ? 'active bg-primary' : 'text-white'; ?>">
      <div class="nav-icon">
        <i class="fas fa-user"></i>
      </div>
      <span class="nav-text ms-2 d-none">Profile</span>
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
        <span class="nav-text ms-2 d-none"><?= htmlspecialchars($module["name"]); ?></span>
      </a>
    <?php endforeach; ?>

    <!-- Library Setup -->
    <a href="<?= Config::indexPath() ?>?action=libsetup"
      class="nav-link rounded my-2 d-flex align-items-center <?= $current_action == 'libsetup' ? 'active bg-primary' : 'text-white'; ?>">
      <div class="nav-icon">
        <i class="fas fa-university"></i>
      </div>
      <span class="nav-text ms-2 d-none">Library Setup</span>
    </a>

    <!-- About Software -->
    <a href="<?= Config::indexPath() ?>?action=aboutsoftware"
      class="nav-link rounded my-2 d-flex align-items-center <?= $current_action == 'aboutsoftware' ? 'active bg-primary' : 'text-white'; ?>">
      <div class="nav-icon">
        <i class="fas fa-laptop-code"></i>
      </div>
      <span class="nav-text ms-2 d-none">About</span>
    </a>

    <!-- Spacer to push content up -->
    <div class="mt-auto"></div>

  </div>

  <!-- Bootstrap JS -->
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>