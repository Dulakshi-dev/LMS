<?php
include "connection.php";

$memID = $_POST["memID"];
$nic = $_POST["nic"];
$address = $_POST["address"];
$phoneNumber = $_POST["phoneNumber"];
$email = $_POST["email"];
$fname = $_POST["fname"];
$lname = $_POST["lname"];
$password = $_POST["password"];
$hashedPassword = password_hash($password, PASSWORD_BCRYPT);

$receiptPath = '';
if (isset($_FILES['receipt']) && $_FILES['receipt']['error'] == UPLOAD_ERR_OK) {
    $receipt = $_FILES['receipt'];
    $targetDir = "assets/receipts/";
    $fileName = uniqid() . "_" . basename($receipt["name"]); 
    $targetFilePath = $targetDir . $fileName;

    if (!is_dir($targetDir)) {
        mkdir($targetDir, 0777, true);
    }

    if (move_uploaded_file($receipt["tmp_name"], $targetFilePath)) {
        $receiptPath = $targetFilePath;
    } else {
        echo "Error uploading receipt.";
        exit();
    }
}

Database::iud("INSERT INTO `user` (`nic`, `fname`, `lname`, `mobile`, `address`, `email`, `status_id`, `receipt`,`role_id`) VALUES 
               ('$nic', '$fname', '$lname', '$phoneNumber', '$address', '$email', '1', '$receiptPath','4')");

$rs = Database::search("SELECT `id` FROM `user` WHERE `nic` = '$nic' AND `email`='$email'");
$row = $rs->fetch_assoc(); 



Database::iud("INSERT INTO `login` (`user_id`, `password`, `userId`) VALUES ('$memID', '$password', '{$row["id"]}');");


echo "success";
?>
