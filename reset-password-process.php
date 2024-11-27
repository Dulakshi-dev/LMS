<?php 
include "connection.php"; 
 
$pw = $_POST["pw"]; 
$cpw = $_POST["cpw"]; 
$vcode = $_POST["vcode"]; 
 
if(empty($vcode)){ 
    echo("Please resend a forgot password email"); 
}else{ 
  
    $rs = Database::search("SELECT * FROM `user` WHERE `vcode`= '$vcode'"); 
    
    $num = $rs->num_rows; 
 
    if($num > 0){ 
        $row = $rs->fetch_assoc(); 
        Database::iud("UPDATE `login` SET `password` = '$pw'  WHERE `userId` = '".$row["id"]."' "); 
        Database::iud("UPDATE `user` SET `vcode` = NULL WHERE `id` = '".$row["id"]."' "); 
        echo("Your password has been reset successfully."); 
    }else{ 
        echo("User not found"); 
    } 
}