<?php

// Include the Router and other necessary files
require_once '../../router.php';
require_once '../../main.php';
require_once Config::getControllerPath("loginController.php");
require_once Config::getControllerPath("userController.php");
require_once Config::getControllerPath("bookController.php");
require_once Config::getControllerPath("circulationController.php");
require_once Config::getControllerPath("profileController.php");
require_once Config::getControllerPath("memberController.php");
require_once Config::getControllerPath("reservationController.php");



// Initialize the Router
$router = new Router();

// Create controller instances
$loginController = new LoginController();
$userController = new UserController();
$bookController = new BookController();
$circulationController = new CirculationController();
$profileController = new ProfileController();
$memberController = new MemberController();
$reservationController = new ReservationController();


// Define the routes and map them to controller methods
$router->add('loginProcess', [$loginController, 'login']);
$router->add('logout', [$loginController, 'logout']);
$router->add('loadusers', [$userController, 'loadUsers']);


//$router->add('staffmanagement', [$userController, 'loadUsers']);
$router->add('loadUserData', [$userController, 'loadUserDetails']); 
$router->add('updateUser', [$userController, 'UpdateUserDetails']);
$router->add('loadMailData', [$userController, 'loadMailData']); 
$router->add('sendMail', [$userController, 'sendMail']); 
$router->add('deactivateuser', [$userController, 'deactivateUser']);
$router->add('addBookData', [$bookController, 'addBookData']);
$router->add('loadBooks', [$bookController, 'getAllBooks']);
$router->add('loadBookData', [$bookController, 'loadBookDetails']);
$router->add('updateBook', [$bookController, 'updateBookDetails']); 
$router->add('searchBooks', [$bookController, 'searchBooks']);
$router->add('deactivatebook', [$bookController, 'deactivateBook']);
$router->add('register', [$loginController, 'register']);
$router->add('forgotpassword', [$loginController, 'forgotPassword']);
$router->add('resetpassword', [$loginController, 'resetPassword']);
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

$router->add('reservationmanagement', [$reservationController, 'getAllReservations']);


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
$router->add('showregister', function () {
    include Config::getViewPath("staff", "staff-register.php");
});
$router->add('showforgotpw', function () {
    include Config::getViewPath("staff", "forgot-password.php");
});
$router->add('showresetpw', function () {
    include Config::getViewPath("staff", "reset-password.php");
});
$router->add('bookcirculation', function () {
    include Config::getViewPath("staff", "circulation-management.php");
});
$router->add('showissuebook', function () {
    include Config::getViewPath("staff", "issue-book.php");
});
$router->add('profile', function () {
    include Config::getViewPath("staff", "profile.php");
});
$router->add('membermanagement', function () {
    include Config::getViewPath("staff", "member-management.php");
});

$router->add('staffmanagement', function () {
    include Config::getViewPath("staff", "user-management.php");
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

$router->add('viewmemberrequests', function () {
    include Config::getViewPath("staff", "view-member-requests.php");
});


// Get the action from the URL
$action = $_GET['action'] ?? 'login';

// Dispatch the appropriate action
$router->dispatch($action);


?>
