<?php 
include "connection.php";

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $pw = $_POST["new-password"]; 
    $memberId = $_POST["memberID"]; 

    $rs = Database::search("SELECT * FROM `login` WHERE `user_id`='$memberId'"); 
    $data = $rs->fetch_assoc();   

    if ($data) {
        Database::iud("UPDATE `login` SET `password`='$pw' WHERE `user_id`='$memberId'"); 
        echo "success";
    } else {
        echo "User not found";
    }
}
?>
