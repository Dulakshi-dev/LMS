<?php
include "connection.php";
session_start();

$username = $_POST["username"]; 
$password = $_POST["password"];

$result = Database::search("SELECT * FROM `user_details` WHERE `user_id` = '" . $username . "' AND `password` = '" . $password . "'");
$num_of_rows = $result->num_rows;

if($num_of_rows == 1){ 
    $data = $result->fetch_assoc(); 
    $_SESSION["staff"] = $data; 

    $role_id = $_SESSION["staff"]["role_id"];

    $result2 = Database::search("SELECT `module_name` FROM `module` JOIN `role_has_module` ON `module`.`module_id` = `role_has_module`.`module_id` WHERE `role_id` = '" . $role_id . "'");
    
    $modules = [];
    while($row = $result2->fetch_assoc()){
        $modules[] = $row["module_name"];
    }
    
    
    $_SESSION["module"] = $modules; 

    echo "success";
} else {
    echo "Invalid user ID or password";
}
?>
