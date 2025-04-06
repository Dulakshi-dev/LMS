<?php

if (!isset($_SESSION["staff"])) {
    header("Location: login.php");
    exit();
}
$role_name = $_SESSION["staff"]["role_name"];

if (isset($_SESSION["modules"]) && !empty($_SESSION["modules"])) {
    $modules = $_SESSION["modules"];
} else {
    $modules = ["No modules available"];
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css">
    <style>
        #sidepanel {
            display: block;
        }

        @media (max-width: 992px) {
            #sidepanel {
                display: none;
            }
        }
    </style>
</head>

<body>

    <div id="sidepanel" class=" bg-dark text-white" style="width: 250px; height: 100%;">

        <?php
        if ($role_name == "Librarian") {
        ?>
            <h4 class="mx-2">Librarian Panel </h4>
        <?php
        } else {
        ?>
            <h4 class="mx-2">Staff Panel </h4>
        <?php
        }
        ?>
        <button id="tog" class="navbar-toggler ml-2" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>

        <a href="<?php echo Config::indexPath() ?>?action=dashboard" class="nav-link text-white p-2 border-bottom align-items-center">
            <i class="fa fa-gauge ms-1 me-2" style="font-size: 18px;"></i> Dashboard
        </a>
        <a href="<?php echo Config::indexPath() ?>?action=profile" class="nav-link text-white p-2 border-bottom align-items-center">
            <i class="fas fa-user ms-1 me-2" style="font-size: 18px;"></i> My Profile
        </a>
        <?php
        foreach ($modules as $module) {
            $action = strtolower(str_replace(' ', '', $module["name"]));
            $iconPath = Config::getImagePath($module["icon"]); // Ensure this function is correctly defined

        ?>
            <a href="<?php echo Config::indexPath() ?>?action=<?php echo htmlspecialchars($action); ?>"
                class="nav-link p-2 text-white border-bottom align-items-center d-flex">
                <img src="<?php echo htmlspecialchars($iconPath); ?>"
                    alt="Module Icon" width="24" height="24" class="me-2">
                <span><?php echo htmlspecialchars($module["name"]); ?></span>
            </a>
        <?php
        }
        ?>



        <a href="<?php echo Config::indexPath() ?>?action=libsetup" class="nav-link text-white p-2 border-bottom align-items-center">
        <i class="fa-solid fa-university ms-1 me-2" style="font-size: 18px;"></i> Library Setup
        </a>
        <a href="<?php echo Config::indexPath() ?>?action=aboutsoftware" class="nav-link text-white p-2 border-bottom align-items-center">
            <i class="fa-solid fa-laptop-code me-2" style="font-size: 18px;"></i> About Software
        </a>

    </div>
</body>

</html>