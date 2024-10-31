<?php 
include "connection.php";

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $pw = $_POST["new-password"]; 
    $memberId = $_POST["memberID"]; 

    $rs = Database::search("SELECT * FROM member WHERE member_id='$memberId'"); 
    $data = $rs->fetch_assoc();   

    if ($data) {
        Database::iud("UPDATE member SET `password`='$pw' WHERE `member_id`='$memberId'"); 
        echo "success";
    } else {
        echo "User not found";
    }
}
?>
