<?php

// Include the Router and other necessary files
require_once '../../router.php';
require_once '../../main.php';

require_once Config::getControllerPath("authController.php");
require_once Config::getControllerPath("memberDashboardController.php");
require_once Config::getControllerPath("memberProfileController.php");
require_once Config::getControllerPath("borrowHistoryController.php");
require_once Config::getControllerPath("reservationController.php");

// Initialize the Router
$router = new Router();

// Create controller instances
$authController = new authController();
$memberDashboardController = new MemberDashboardController();
$memberProfileController = new memberProfileController();
$borrowHistoryController = new BorrowHistoryController();
$reservationController = new ReservationController();

$router->add('memberlogin', [$authController, 'login']);
$router->add('loadbooks', [$memberDashboardController, 'getAllBooks']);
$router->add('loadreccomendedbooks', [$memberDashboardController, 'getRecommendedBooks']);
$router->add('loadMemberData', [$memberProfileController, 'loadMemberDetails']); 
$router->add('updateprofile', [$memberProfileController, 'updateProfile']); 
$router->add('serveprofimage', [$memberProfileController, 'serveProfileImage']);
$router->add('validatecurrentpw', [$memberProfileController, 'validateCurrentPassword']); 
$router->add('savenewpw', [$memberProfileController, 'resetPassword']); 
$router->add('loadissuebooks', [$borrowHistoryController, 'loadBorrowBooks']); 
$router->add('sendotp', [$authController, 'sendOTP']); 
$router->add('verifyotp', [$authController, 'verifyOTP']); 
$router->add('showPayment', [$authController, 'proceedPayment']); 
$router->add('registerMember', [$authController, 'registerMember']); 
$router->add('reserve', [$reservationController, 'reserveBook']); 
$router->add('reservedbooks', [$reservationController, 'loadReservedBooks']); 


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

$router->add('borrowhistory', function () {
    include Config::getViewPath("member", "borrow-history.php");
});

$router->add('register', function () {
    include Config::getViewPath("member", "register.php");
});

// Get the action from the URL
$action = $_GET['action'] ?? 'home';

// Dispatch the appropriate action
$router->dispatch($action);


?>
