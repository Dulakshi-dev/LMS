<?php

// Include the Router and other necessary files
require_once '../../router.php';
require_once '../../main.php';
require_once Config::getControllerPath("staff","authController.php");
require_once Config::getControllerPath("staff","staffController.php");
require_once Config::getControllerPath("staff","bookController.php");
require_once Config::getControllerPath("staff","circulationController.php");
require_once Config::getControllerPath("staff","profileController.php");
require_once Config::getControllerPath("staff","memberController.php");
require_once Config::getControllerPath("staff","reservationController.php");
require_once Config::getControllerPath("staff","librarySetupController.php");
require_once Config::getControllerPath("staff","dashboardController.php");
require_once Config::getControllerPath("staff","paymentController.php");
require_once Config::getControllerPath("staff","notificationController.php");

// Initialize the Router
$router = new Router();

// Create controller instances
$authController = new AuthController();
$staffController = new StaffController();
$bookController = new BookController();
$circulationController = new CirculationController();
$profileController = new ProfileController();
$memberController = new MemberController();
$reservationController = new ReservationController();
$librarySetupController = new LibrarySetupController();
$dashboardController = new DashboardController();
$paymentController = new PaymentController();
$notificationController = new NotificationController();

// Define the routes and map them to controller methods
$router->add('loginProcess', [$authController, 'login']);
$router->add('logout', [$authController, 'logout']);
$router->add('loadusers', [$staffController, 'loadStaff']);
$router->add('loadUserData', [$staffController, 'loadStaffDetails']); 
$router->add('updateUser', [$staffController, 'UpdateStaffDetails']);
$router->add('loadMailData', [$staffController, 'loadMailData']); 
$router->add('sendMailMember', [$memberController, 'sendMail']); 
$router->add('sendMailStaff', [$staffController, 'sendMail']); 
$router->add('deactivateuser', [$staffController, 'deactivateStaff']);
$router->add('addBookData', [$bookController, 'addBookData']);
$router->add('loadBooks', [$bookController, 'getAllBooks']);
$router->add('loadBookData', [$bookController, 'loadBookDetails']);
$router->add('addCategoryData', [$bookController, 'addCategory']);
$router->add('updateBook', [$bookController, 'updateBookDetails']); 
$router->add('searchBooks', [$bookController, 'searchBooks']);
$router->add('deactivatebook', [$bookController, 'deactivateBook']);
$router->add('register', [$authController, 'register']);
$router->add('forgotpassword', [$authController, 'forgotPassword']);
$router->add('validatedetails', [$authController, 'validatedetails']);

$router->add('resetpassword', [$authController, 'resetPassword']);
$router->add('loadborrowbookdata', [$circulationController, 'loadBookDetails']);
$router->add('loadborrowmemberdata', [$circulationController, 'loadMemberDetails']);
$router->add('issuebook', [$circulationController, 'issueBook']);
$router->add('loadissuedbooks', [$circulationController, 'getAllBorrowBooks']);
$router->add('returnbook', [$circulationController, 'returnBook']);
$router->add('serveimage', [$bookController, 'serveBookCover']);
$router->add('updateprofile', [$profileController, 'updateProfile']);
$router->add('serveprofimage', [$profileController, 'serveProfileImage']);
$router->add('validatecurrentpw', [$profileController, 'validateCurrentPassword']); 
$router->add('savenewpw', [$profileController, 'resetPassword']); 
$router->add('searchBorrowBooks', [$circulationController, 'searchBorrowBooks']);
$router->add('getallcategories', [$bookController, 'getAllCategories']);
$router->add('getlanguages', [$bookController, 'getLanguages']);
$router->add('loadmembers', [$memberController, 'getAllMembers']);
$router->add('loadMemberData', [$memberController, 'loadMemberDetails']); 
$router->add('updateMember', [$memberController, 'UpdateMemberDetails']);
$router->add('loadMemberMailData', [$memberController, 'loadMailData']); 
$router->add('changeMemberStatus', [$memberController, 'changeMemberStatus']); 
$router->add('approvemembership', [$memberController, 'approveMembership']); 
$router->add('deactivatemember', [$memberController, 'deactivateMember']);
$router->add('rejectmember', [$memberController, 'rejectMember']);
$router->add('loadmemberrequests', [$memberController, 'getMemberRequests']);
$router->add('loadreservations', [$reservationController, 'getAllReservations']);
$router->add('loadcategories', [$bookController, 'getAllCategories']);
$router->add('activatebook', [$bookController, 'activateBook']);
$router->add('deletecategory', [$bookController, 'deleteCategory']);
$router->add('activatestaff', [$staffController, 'activateStaff']);
$router->add('activatemember', [$memberController, 'activateMember']);
$router->add('activaterequest', [$memberController, 'activateRequest']);
$router->add('sendkey', [$staffController, 'sendEnrollmentKey']);
$router->add('changeopeninghours', [$librarySetupController, 'changeOpeningHours']);
$router->add('changenewsupdates', [$librarySetupController, 'changeNewsUpdates']);
$router->add('changelibraryinfo', [$librarySetupController, 'changeLibraryInfo']);
$router->add('sendemailtoallstaff', [$librarySetupController, 'sendMailsToAllStaff']);
$router->add('sendemailtoallmembers', [$librarySetupController, 'sendMailsToAllMembers']);
$router->add('getopeninghours', [$librarySetupController, 'loadOpeningHours']); 
$router->add('getlibraryinfo', [$librarySetupController, 'getLibraryInfo']);
$router->add('servelogo', [$librarySetupController, 'serveLogo']);
$router->add('getchartdata', [$dashboardController, 'getUserChartData']);
$router->add('getcounts', [$dashboardController, 'getDashboardCounts']);
$router->add('gettopbooks', [$dashboardController, 'loadTopBooks']); 
$router->add('loadpayments', [$paymentController, 'getAllPayments']);

$router->add('login', function () {
    include Config::getViewPath("staff", "login.php");
});
$router->add('dashboard', function () {
    include Config::getViewPath("staff", "dashboard.php");
});
$router->add('bookmanagement', function () {
    include Config::getViewPath("staff", "book-management.php");
});
$router->add('addBook', function () {
    include Config::getViewPath("staff", "add-book.php");
});
$router->add('addCategory', function () {
    include Config::getViewPath("staff", "add-category.php");
});
$router->add('showregister', function () {
    include Config::getViewPath("staff", "staff-register.php");
});
$router->add('showforgotpw', function () {
    include Config::getViewPath("staff", "forgot-password.php");
});
$router->add('showresetpw', function () {
    include Config::getViewPath("staff", "reset-password.php");
});
$router->add('circulationmanagement', function () {
    include Config::getViewPath("staff", "circulation-management.php");
});
$router->add('showissuebook', function () {
    include Config::getViewPath("staff", "issue-book.php");
});
$router->add('profile', function () {
    include Config::getViewPath("staff", "profile.php");
});

$router->add('libsetup', function () {
    include Config::getViewPath("staff", "library-setup.php");
});
$router->add('membermanagement', function () {
    include Config::getViewPath("staff", "member-management.php");
});

$router->add('staffmanagement', function () {
    include Config::getViewPath("staff", "staff-management.php");
});

$router->add('viewBooks', function () {
    include Config::getViewPath("staff", "view-books.php");
});

$router->add('viewissuebooks', function () {
    include Config::getViewPath("staff", "view-issue-books.php");
});

$router->add('viewmembers', function () {
    include Config::getViewPath("staff", "view-members.php");
});

$router->add('viewstaff', function () {
    include Config::getViewPath("staff", "view-staff.php");
});

$router->add('viewdeactivatedstaff', function () {
    include Config::getViewPath("staff", "view-deactive-staff.php");
});

$router->add('viewdeactivemembers', function () {
    include Config::getViewPath("staff", "view-deactive-members.php");
});

$router->add('viewdeactivatedbooks', function () {
    include Config::getViewPath("staff", "view-deactive-books.php");
});

$router->add('viewrejectedrequests', function () {
    include Config::getViewPath("staff", "view-rejected-requests.php");
});

$router->add('viewmemberrequests', function () {
    include Config::getViewPath("staff", "view-member-requests.php");
});

$router->add('viewreservations', function () {
    include Config::getViewPath("staff", "view-reservations.php");
});

$router->add('aboutsoftware', function () {
    include Config::getViewPath("staff", "about_software.php");
});

$router->add('paymentmanagement', function () {
    include Config::getViewPath("staff", "view-payments.php");
});

// Get the action from the URL
$action = $_GET['action'] ?? 'login';

// Dispatch the appropriate action
$router->dispatch($action);
?>
