<?php

// Include the Router and other necessary files
require_once '../../router.php';
require_once '../../main.php';

require_once Config::getControllerPath("member","authController.php");
require_once Config::getControllerPath("member","bookController.php");
require_once Config::getControllerPath("member","profileController.php");
require_once Config::getControllerPath("member","borrowHistoryController.php");
require_once Config::getControllerPath("member","reservationController.php");
require_once Config::getControllerPath("member","myLibraryController.php");
require_once Config::getControllerPath("member","PaymentController.php");
require_once Config::getControllerPath("member","homeController.php");
require_once Config::getControllerPath("member","notificationController.php");

// Initialize the Router
$router = new Router();

// Create controller instances
$authController = new AuthController();
$bookController = new BookController();
$profileController = new ProfileController();
$borrowHistoryController = new BorrowHistoryController();
$reservationController = new ReservationController();
$myLibraryController = new MyLibraryController();
$paymentController = new PaymentController();
$homeController = new HomeController();
$notificationController = new NotificationController();

$router->add('memberlogin', [$authController, 'login']);
$router->add('loaddashboardbooks', [$bookController, 'getDashboardBooks']);
$router->add('getallbooks', [$bookController, 'getAllBooks']);
$router->add('loadMemberData', [$profileController, 'loadMemberDetails']); 
$router->add('updateprofile', [$profileController, 'updateProfile']); 
$router->add('serveprofimage', [$profileController, 'serveProfileImage']);
$router->add('validatecurrentpw', [$profileController, 'validateCurrentPassword']); 
$router->add('savenewpw', [$profileController, 'resetPassword']); 
$router->add('validateemail', [$authController, 'sendOTP']); 
$router->add('validatenic', [$authController, 'validatenic']); 

$router->add('verifyotp', [$authController, 'verifyOTP']); 
$router->add('loadBorrowHistory', [$borrowHistoryController, 'loadBorrowBooks']); 
$router->add('showPayment', [$paymentController, 'proceedPayment']); 
$router->add('registerMember', [$authController, 'registerMember']); 
$router->add('reserve', [$reservationController, 'reserveBook']); 
$router->add('loadreservedbooks', [$reservationController, 'loadReservedBooks']); 
$router->add('save', [$myLibraryController, 'saveBook']); 
$router->add('savedbooks', [$myLibraryController, 'loadSavedBooks']); 
$router->add('unsave', [$myLibraryController, 'unSaveBook']); 
$router->add('payment_notify', [$paymentController, 'paymentNotify']);
$router->add('renew', [$paymentController, 'renewPayment']); 
$router->add('forgotpassword', [$authController, 'forgotPassword']);
$router->add('resetpassword', [$authController, 'resetPassword']);
$router->add('serveimage', [$bookController, 'serveBookCover']);
$router->add('getallcategories', [$bookController, 'getAllCategories']);
$router->add('getlanguages', [$bookController, 'getLanguages']);
$router->add('cancelreservation', [$reservationController, 'cancelReservation']); 
$router->add('getopeninghours', [$homeController, 'loadOpeningHours']); 
$router->add('getnewsupdates', [$homeController, 'loadNewsUpdates']); 
$router->add('servenewsimage', [$homeController, 'serveNewsImage']);
$router->add('getlibraryinfo', [$homeController, 'getLibraryInfo']); 
$router->add('contactlibrary', [$homeController, 'sendEmailtoLibrary']); 
$router->add('gettopbooks', [$homeController, 'loadTopBooks']); 
$router->add('servelogo', [$homeController, 'serveLogo']);
$router->add('loadnotifications', [$notificationController, 'loadNotification']); 
$router->add('markasread', [$notificationController, 'markAsRead']); 
$router->add('getunreadcount', [$notificationController, 'getUnreadCount']); 
$router->add('logout', [$authController, 'logout']); 
$router->add('searchbook', [$bookController, 'getSearchResult']); 


$router->add('home', function () {
    include Config::getViewPath("guest", "home.php");
});

$router->add('openhours', function () {
    include Config::getViewPath("guest", "opening-hours.php");
});

$router->add('contact', function () {
    include Config::getViewPath("guest", "contact.php");
});

$router->add('about', function () {
    include Config::getViewPath("guest", "about.php");
});

$router->add('lmshome', function () {
    include Config::getViewPath("guest", "lms-home.php");
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

$router->add('aboutsoftware', function () {
    include Config::getViewPath("member", "about_software.php");
});

// Get the action from the URL
$action = $_GET['action'] ?? 'home';

// Dispatch the appropriate action
$router->dispatch($action);


?>
