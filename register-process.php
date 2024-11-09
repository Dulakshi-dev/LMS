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
    $targetDir = "assets/receipts/"; // Directory to save receipts
    $fileName = uniqid() . "_" . basename($receipt["name"]); // Create unique file name
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

Database::iud("INSERT INTO `member` (`member_id`, `nic`, `fname`, `lname`, `mobile`, `address`, `email`, `password`, `status`, `receipt`) VALUES 
               ('$memID', '$nic', '$fname', '$lname', '$phoneNumber', '$address', '$email', '$hashedPassword', '0', '$receiptPath')");

echo "success";
?>
