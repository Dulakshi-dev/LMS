<?php

// Include the Router and other necessary files
require_once '../router.php';
require_once '../main.php';
require_once Config::getControllerPath("loginController.php");
require_once Config::getControllerPath("userController.php");
require_once Config::getControllerPath("bookController.php");


// Initialize the Router
$router = new Router();

// Create controller instances
$loginController = new LoginController();
$userController = new UserController();
$bookController = new BookController();

// Define the routes and map them to controller methods
$router->add('login', [$loginController, 'showLogin']);
$router->add('loginProcess', [$loginController, 'login']);
$router->add('logout', [$loginController, 'logout']);
$router->add('dashboard', [$loginController, 'showDashboard']);
$router->add('usermanagement', [$userController, 'getAllUsers']);
$router->add('search', [$userController, 'searchUsers']);
$router->add('loadUserData', [$userController, 'loadUserDetails']); 
$router->add('updateUser', [$userController, 'UpdateUserDetails']);
$router->add('bookmanagement', [$bookController, 'showBookManagement']);
$router->add('addBook', [$bookController, 'showAddBook']);
$router->add('addBookData', [$bookController, 'addBookData']);
$router->add('viewBook', [$bookController, 'getAllBooks']);
$router->add('loadBookData', [$bookController, 'loadBookDetails']);
$router->add('updateBook', [$bookController, 'updateBookDetails']); 
$router->add('loadMailData', [$userController, 'loadMailData']); 
$router->add('sendMail', [$userController, 'sendMail']); 



// Get the action from the URL
$action = $_GET['action'] ?? 'login';

// Dispatch the appropriate action
$router->dispatch($action);


?>
