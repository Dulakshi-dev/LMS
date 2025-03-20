<?php

if (!isset($_SESSION["staff"])) {
    header("Location: login.php");
    exit();
}

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
    <link rel="stylesheet" href="dash.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
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
        <a href="" class="nav-link text-white p-2 border-bottom align-items-center">
            <i class="fas fa-user mr-3"></i> Dashboard
        </a>
        <a href="<?php echo Config::indexPath() ?>?action=profile" class="nav-link text-white p-2 border-bottom align-items-center">
            <i class="fas fa-user mr-3"></i> My Profile
        </a>
        <?php

        foreach ($modules as $module) {
            $action = strtolower(str_replace(' ', '', $module));

        ?>
            <a href="<?php echo Config::indexPath() ?>?action=<?php echo htmlspecialchars($action); ?>" class="nav-link p-2 text-white border-bottom align-items-center">
                <i class="fas fa-tachometer-alt mr-3"></i>
                <?php echo htmlspecialchars($module); ?>
            </a>
        <?php
        }
        ?>

        <a href="<?php echo Config::indexPath() ?>?action=libsetup" class="nav-link text-white p-2 border-bottom align-items-center">
            <i class="fas fa-user mr-3"></i> Library Setup
        </a>
        <a href="" class="nav-link text-white p-2 border-bottom align-items-center">
            <i class="fas fa-user mr-3"></i> About Software
        </a>
        <?php


        ?>

    </div>
</body>

</html>