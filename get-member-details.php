<?php
session_start();
include "connection.php";

$id = $_GET["id"]; 

if (!empty($id)) {
    $rs = Database::search("SELECT * FROM `member` WHERE `id` = '$id'");
    $num = $rs->num_rows;
    if ($num > 0) {
        $row = $rs->fetch_assoc();
        echo json_encode($row);  // Return JSON-encoded user details
    } else {
        echo json_encode(["error" => "No user found"]);
    }
} else {
    echo json_encode(["error" => "Invalid ID"]);
}
?>
