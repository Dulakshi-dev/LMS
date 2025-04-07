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
    #sidepanel1 {
      width: 250px;
      min-height: 100vh;
    }

    @media (max-width: 992px) {
      #sidepanel1 {
        display: none;
      }
    }

    .nav-link.active {
      background-color: #0d6efd !important;
      color: white !important;
    }
  </style>
</head>

<body>

  <div id="sidepanel1" class="bg-dark text-white d-flex flex-column p-2">
    <h4 class="mx-2"><?= $role_name == "Librarian" ? "Librarian Panel" : "Staff Panel"; ?></h4>

    <a href="<?php echo Config::indexPath() ?>?action=dashboard"
      class="nav-link text-white p-2 border-bottom d-flex align-items-center <?= $current_action == 'dashboard' ? 'active' : ''; ?>">
      <i class="fa fa-gauge me-2"></i> Dashboard
    </a>

    <a href="<?php echo Config::indexPath() ?>?action=profile"
      class="nav-link text-white p-2 border-bottom d-flex align-items-center <?= $current_action == 'profile' ? 'active' : ''; ?>">
      <i class="fas fa-user me-2"></i> My Profile
    </a>

    <?php foreach ($modules as $module): 
      $action = strtolower(str_replace(' ', '', $module["name"]));
      $iconPath = Config::getImagePath($module["icon"]);
    ?>
      <a href="<?= Config::indexPath() ?>?action=<?= htmlspecialchars($action); ?>"
        class="nav-link text-white p-2 border-bottom d-flex align-items-center <?= $current_action == $action ? 'active' : ''; ?>">
        <img src="<?= htmlspecialchars($iconPath); ?>" alt="Module Icon" width="24" height="24" class="me-2">
        <?= htmlspecialchars($module["name"]); ?>
      </a>
    <?php endforeach; ?>

    <a href="<?php echo Config::indexPath() ?>?action=libsetup"
      class="nav-link text-white p-2 border-bottom d-flex align-items-center <?= $current_action == 'libsetup' ? 'active' : ''; ?>">
      <i class="fa-solid fa-university me-2"></i> Library Setup
    </a>

    <a href="<?php echo Config::indexPath() ?>?action=aboutsoftware"
      class="nav-link text-white p-2 border-bottom d-flex align-items-center <?= $current_action == 'aboutsoftware' ? 'active' : ''; ?>">
      <i class="fa-solid fa-laptop-code me-2"></i> About Software
    </a>
  </div>

</body>
</html>