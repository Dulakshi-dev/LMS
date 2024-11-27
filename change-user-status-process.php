<?php 
include "connection.php"; 
 
$id = $_GET["id"]; 
$status = $_GET["s"]; 
 
$rs = Database::search("SELECT * FROM `user` WHERE `id`= '$id'"); 
$num = $rs->num_rows; 
 
if($num >0){ 
    $row = $rs->fetch_assoc(); 
    $status = 1; 
    if($row["status_id"]==1){ 
        $status = 2; 
    } 
    Database::iud("UPDATE `user` SET `status_id`='$status' WHERE `id`='$id'"); 
    echo("success"); 
}else{ 
    echo ("User not found!"); 
} 