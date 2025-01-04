<?php

// Include the Router and other necessary files
require_once '../router.php';
require_once '../main.php';
require_once Config::getControllerPath("loginController.php");
require_once Config::getControllerPath("userController.php");
require_once Config::getControllerPath("bookController.php");
require_once Config::getControllerPath("circulationController.php");
require_once Config::getControllerPath("profileController.php");


// Initialize the Router
$router = new Router();

// Create controller instances
$loginController = new LoginController();
$userController = new UserController();
$bookController = new BookController();
$circulationController = new CirculationController();
$profileController = new ProfileController();


// Define the routes and map them to controller methods
$router->add('login', [$loginController, 'showLogin']);
$router->add('loginProcess', [$loginController, 'login']);
$router->add('logout', [$loginController, 'logout']);
$router->add('dashboard', [$loginController, 'showDashboard']);
$router->add('usermanagement', [$userController, 'getAllUsers']);
$router->add('searchUsers', [$userController, 'searchUsers']);
$router->add('loadUserData', [$userController, 'loadUserDetails']); 
$router->add('updateUser', [$userController, 'UpdateUserDetails']);
$router->add('loadMailData', [$userController, 'loadMailData']); 
$router->add('sendMail', [$userController, 'sendMail']); 
$router->add('changeStatus', [$userController, 'changeUserStatus']); 
$router->add('bookmanagement', [$bookController, 'showBookManagement']);
$router->add('addBook', [$bookController, 'showAddBook']);
$router->add('addBookData', [$bookController, 'addBookData']);
$router->add('viewBook', [$bookController, 'getAllBooks']);
$router->add('loadBookData', [$bookController, 'loadBookDetails']);
$router->add('updateBook', [$bookController, 'updateBookDetails']); 
$router->add('searchBooks', [$bookController, 'searchBooks']);
$router->add('showregister', [$loginController, 'showregister']);
$router->add('register', [$loginController, 'register']);
$router->add('showforgotpw', [$loginController, 'showForgotPassword']);
$router->add('forgotpassword', [$loginController, 'forgotPassword']);
$router->add('showresetpw', [$loginController, 'showResetPassword']);
$router->add('resetpassword', [$loginController, 'resetPassword']);
$router->add('bookcirculation', [$circulationController, 'showCirculationManagement']);
$router->add('showissuebook', [$circulationController, 'showIssueBook']);
$router->add('loadborrowbookdata', [$circulationController, 'loadBookDetails']);
$router->add('loadborrowmemberdata', [$circulationController, 'loadMemberDetails']);
$router->add('issuebook', [$circulationController, 'issueBook']);
$router->add('viewissuebook', [$circulationController, 'getAllBorrowBooks']);
$router->add('serveimage', [$bookController, 'serveBookCover']);
$router->add('profile', [$profileController, 'showProfile']);
$router->add('updateprofile', [$profileController, 'updateProfile']);
$router->add('serveprofimage', [$profileController, 'serveProfileImage']);
$router->add('searchBorrowBooks', [$circulationController, 'searchBorrowBooks']);
$router->add('getallcategories', [$bookController, 'getAllCategories']);







// Get the action from the URL
$action = $_GET['action'] ?? 'login';

// Dispatch the appropriate action
$router->dispatch($action);


?>
