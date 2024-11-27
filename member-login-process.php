<?php 
include "connection.php"; 
session_start(); 

$username = $_POST["username"]; 
$password = $_POST["password"]; 
$rememberme = isset($_POST["rememberme"]) ? $_POST["rememberme"] : "false";

$result = Database::search("SELECT * FROM `z` WHERE `member_id` = '".$username."'"); 

if ($result->num_rows == 1) { 
    $data = $result->fetch_assoc();
    
    if (password_verify($password, $data["password"])) {

        $_SESSION["user_id"] = $data["member_id"]; 

        if ($rememberme === "true") {
            setcookie("username", $username, time() + (60 * 60 * 24 * 365), "/");
            setcookie("password", $password, time() + (60 * 60 * 24 * 365), "/"); 
        } else {
            
            setcookie("username", "", time() - 3600, "/");
            setcookie("password", "", time() - 3600, "/");
        }

        echo "success";
    } else {
        echo "Invalid email or password"; 
    }
} else {
    echo "Invalid email or password"; 
}
?>
