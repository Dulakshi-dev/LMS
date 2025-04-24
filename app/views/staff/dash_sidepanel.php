<?php
if (!isset($_SESSION["staff"])) {
    header("Location: login.php");
    exit();
}

$role_name = $_SESSION["staff"]["role_name"];
$modules = $_SESSION["modules"] ?? [["name" => "No modules available", "icon" => "default.png"]];
$current_action = $_GET['action'] ?? 'dashboard';
?>


<style>
    #sidepanel1 {
        width: 250px;
        min-height: 100vh;
        position: sticky;
        top: 0;
    }

    .nav-link.active {
        background-color: #0d6efd !important;
        color: white !important;
    }

    .bg {
        background: rgba(26, 50, 65, 1);
    }
</style>

<body>

  <div id="sidepanel1" class="bg text-white d-flex flex-column p-2">
    <h4 class="mx-2"><?= $role_name == "Librarian" ? "Librarian Panel" : "Staff Panel"; ?></h4>

    <a href="<?php echo Config::indexPath() ?>?action=dashboard"
      class="nav-link text-white p-2 border-bottom d-flex align-items-center <?= $current_action == 'dashboard' ? 'active' : ''; ?>">
      <i class="fa fa-gauge me-2 ms-1"></i> Dashboard
    </a>

    <a href="<?php echo Config::indexPath() ?>?action=profile"
      class="nav-link text-white p-2 border-bottom d-flex align-items-center <?= $current_action == 'profile' ? 'active' : ''; ?>">
      <i class="fas fa-user me-2 ms-1"></i> My Profile
    </a>

    <?php foreach ($modules as $module): 
      $action = strtolower(str_replace(' ', '', $module["name"]));
      $iconPath = Config::getImagePath($module["icon"]);
    ?>
      <a href="<?= Config::indexPath() ?>?action=<?= htmlspecialchars($action); ?>"
        class="nav-link text-white p-2 border-bottom d-flex align-items-center <?= $current_action == $action ? 'active' : ''; ?>">
        <img src="<?= htmlspecialchars($iconPath); ?>" alt="Module Icon" width="22" height="22" class="me-2">
        <?= htmlspecialchars($module["name"]); ?>
      </a>
    <?php endforeach; ?>

    <a href="<?php echo Config::indexPath() ?>?action=aboutsoftware"
      class="nav-link text-white p-2 border-bottom d-flex align-items-center <?= $current_action == 'aboutsoftware' ? 'active' : ''; ?>">
      <i class="fa-solid fa-laptop-code me-2 "></i> About Software
    </a>
  </div>

</body>
