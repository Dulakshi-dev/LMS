
<?php 
include "connection.php"; 
 
$memID = $_GET["memberID"]; 
$nic = $_GET["nic"]; 


    $rs = Database::search("SELECT * FROM `user` INNER JOIN `login` ON `user`.`id` = `login`.`userId` WHERE `user_id` = '$memID' OR `nic`='$nic'"); 
    $num = $rs->num_rows; 
    if($num > 0){ 
        echo("User has been already registered with the given membership ID or NIC number"); 
    }else{ 
        echo("success"); 
    } 
    ?>