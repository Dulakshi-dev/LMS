

<?php 
include "connection.php"; 
session_start(); 
 
$username = $_POST["username"]; 
$password = $_POST["password"]; 
$rememberme = $_POST["rememberme"];

// $_SESSION['user_id'] = $username;       // Set user ID in session
// $_SESSION['password'] = $password;    // Set username in session
// $_SESSION['loggedin'] = true; 

$result = Database::search("SELECT * FROM `member` WHERE `member_id`='".$username."' AND `password`='".$password."'"); 
    
$num_of_rows = $result->num_rows; 
if($num_of_rows == 1){ 
    $data = $result->fetch_assoc(); 
    $_SESSION["user_id"] = $data["member_id"]; 
  

    if ($rememberme == "true") {

        setcookie("username", $username, time() + (60 * 60 * 24 * 365));
        setcookie("password", $password, time() + (60 * 60 * 24 * 365));
    } else {

        setcookie("username", "", -1);
        setcookie("password", "", -1);
    }

    echo "success"; 
}else{ 
    echo "Invalid email or password"; 
} 
