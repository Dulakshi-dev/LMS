<?php

// Required !
require_once "../../main.php";
?>

<!DOCTYPE html>

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale = 1.0"><link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
    <title>Home</title>
</head>

<?php

// Header
require_once Config::getViewPath("home", "header.view.php");
// Body
require_once Config::getViewPath("home", "main.view.php");
// Footer
require_once Config::getViewPath("home", "footer.view.php");
?>