<?php

// Include the Router and other necessary files
require_once '../../router.php';
require_once '../../main.php';

require_once Config::getControllerPath("authController.php");
require_once Config::getControllerPath("memberBookController.php");
require_once Config::getControllerPath("memberProfileController.php");
require_once Config::getControllerPath("borrowHistoryController.php");
require_once Config::getControllerPath("memberReservationController.php");
require_once Config::getControllerPath("myLibraryController.php");
require_once Config::getControllerPath("PaymentController.php");
require_once Config::getControllerPath("homeController.php");


// Initialize the Router
$router = new Router();

// Create controller instances
$authController = new AuthController();
$memberBookController = new MemberBookController();
$memberProfileController = new memberProfileController();
$borrowHistoryController = new BorrowHistoryController();
$memberReservationController = new MemberReservationController();
$myLibraryController = new MyLibraryController();
$paymentController = new PaymentController();
$homeController = new HomeController();

$router->add('memberlogin', [$authController, 'login']);
$router->add('loaddashboardbooks', [$memberBookController, 'getDashboardBooks']);
$router->add('getallbooks', [$memberBookController, 'getAllBooks']);
$router->add('loadMemberData', [$memberProfileController, 'loadMemberDetails']); 
$router->add('updateprofile', [$memberProfileController, 'updateProfile']); 
$router->add('serveprofimage', [$memberProfileController, 'serveProfileImage']);
$router->add('validatecurrentpw', [$memberProfileController, 'validateCurrentPassword']); 
$router->add('savenewpw', [$memberProfileController, 'resetPassword']); 
$router->add('sendotp', [$authController, 'sendOTP']); 
$router->add('verifyotp', [$authController, 'verifyOTP']); 
$router->add('loadBorrowHistory', [$borrowHistoryController, 'loadBorrowBooks']); 
$router->add('showPayment', [$paymentController, 'proceedPayment']); 
$router->add('registerMember', [$authController, 'registerMember']); 
$router->add('reserve', [$memberReservationController, 'reserveBook']); 
$router->add('loadreservedbooks', [$memberReservationController, 'loadReservedBooks']); 
$router->add('save', [$myLibraryController, 'saveBook']); 
$router->add('savedbooks', [$myLibraryController, 'loadSavedBooks']); 
$router->add('unsave', [$myLibraryController, 'unSaveBook']); 
$router->add('payment_notify', [$paymentController, 'paymentNotify']);
$router->add('renewmembership', [$paymentController, 'renewPayment']); 
$router->add('forgotpassword', [$authController, 'forgotPassword']);
$router->add('changepassword', [$authController, 'resetPassword']);
$router->add('serveimage', [$memberBookController, 'serveBookCover']);
$router->add('getallcategories', [$memberBookController, 'getAllCategories']);
$router->add('getlanguages', [$memberBookController, 'getLanguages']);
$router->add('cancelreservation', [$memberReservationController, 'cancelReservation']); 
$router->add('getopeninghours', [$homeController, 'loadOpeningHours']); 
$router->add('getnewsupdates', [$homeController, 'loadNewsUpdates']); 
$router->add('servenewsimage', [$homeController, 'serveNewsImage']);
$router->add('getlibraryinfo', [$homeController, 'getLibraryInfo']); 
$router->add('contactlibrary', [$homeController, 'sendEmailtoLibrary']); 
$router->add('gettopbooks', [$homeController, 'loadTopBooks']); 
$router->add('servelogo', [$homeController, 'serveLogo']);



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

$router->add('renewmembership', function () {
    include Config::getViewPath("member", "renew-membership.php");
});

$router->add('showforgotpw', function () {
    include Config::getViewPath("member", "forgot-password.php");
});

$router->add('showresetpw', function () {
    include Config::getViewPath("member", "reset-password.php");
});

$router->add('viewborrowhistory', function () {
    include Config::getViewPath("member", "borrow-history.php");
});

$router->add('viewMyLibrary', function () {
    include Config::getViewPath("member", "my-library.php");
});

$router->add('showallbooks', function () {
    include Config::getViewPath("member", "view-all-books.php");
});

$router->add('reservedbooks', function () {
    include Config::getViewPath("member", "reserved-books.php");
});

$router->add('expired', function () {
    include Config::getViewPath("member", "expired.php");
});

$router->add('deactivated', function () {
    include Config::getViewPath("member", "deactivated.php");
});

// Get the action from the URL
$action = $_GET['action'] ?? 'home';

// Dispatch the appropriate action
$router->dispatch($action);


?>
