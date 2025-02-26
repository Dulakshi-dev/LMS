<?php

// Include the Router and other necessary files
require_once '../../router.php';
require_once '../../main.php';

require_once Config::getControllerPath("authController.php");
require_once Config::getControllerPath("memberDashboardController.php");
require_once Config::getControllerPath("memberProfileController.php");




// Initialize the Router
$router = new Router();

// Create controller instances
$authController = new authController();
$memberDashboardController = new memberBookController();
$memberProfileController = new memberProfileController();


$router->add('memberlogin', [$authController, 'login']);
$router->add('loadbooks', [$memberDashboardController, 'getAllBooks']);
$router->add('loadMemberData', [$memberProfileController, 'loadMemberDetails']); 
$router->add('updateprofile', [$memberProfileController, 'updateProfile']); 
$router->add('serveprofimage', [$memberProfileController, 'serveProfileImage']);
$router->add('validatecurrentpw', [$memberProfileController, 'validateCurrentPassword']); 
$router->add('savenewpw', [$memberProfileController, 'resetPassword']); 





$router->add('home', function () {
    include Config::getViewPath("member", "home.php");
});

$router->add('openhours', function () {
    include Config::getViewPath("member", "opening-hours.php");
});

$router->add('contact', function () {
    include Config::getViewPath("member", "contact.php");
});

$router->add('about', function () {
    include Config::getViewPath("member", "about.php");
});

$router->add('lmshome', function () {
    include Config::getViewPath("member", "lms-home.php");
});

$router->add('login', function () {
    include Config::getViewPath("member", "login.php");
});

$router->add('dashboard', function () {
    include Config::getViewPath("member", "dashboard.php");
});

$router->add('profile', function () {
    include Config::getViewPath("member", "profile.php");
});

// Get the action from the URL
$action = $_GET['action'] ?? 'home';

// Dispatch the appropriate action
$router->dispatch($action);


?>
