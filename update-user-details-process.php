<?php 
include "connection.php"; 

$memberId = $_POST["membershipId"]; 
$full_name = $_POST['username'];
$email = $_POST["email"]; 
$phone = $_POST["phone"]; 
$address = $_POST["address"]; 
$nic = $_POST["nic"]; 

$name_parts = explode(" ", trim($full_name));

// Assign first and last name separately
$firstName = isset($name_parts[0]) ? $name_parts[0] : '';
$lastName = isset($name_parts[1]) ? $name_parts[1] : '';

// Input validation
if (empty($firstName)) { 
    echo "Please enter the first name"; 
} else if (empty($lastName)) { 
    echo "Please enter the last name"; 
} else if (empty($email)) { 
    echo "Please enter the email"; 
} else if (empty($phone)) { 
    echo "Please enter the phone number"; 
} else if (empty($address)) { 
    echo "Please enter the address"; 
} else if (empty($nic)) { 
    echo "Please enter the NIC number"; 
} else { 
    $rs = Database::search("SELECT * FROM member WHERE member_id='$memberId'"); 
    $data = $rs->fetch_assoc();  
    $num = $rs->num_rows;

    if ($num > 0) {
        Database::iud("UPDATE member SET `fname`='$firstName', `lname`='$lastName', `mobile`='$phone', `email`='$email', `address`='$address', `nic`='$nic' WHERE `member_id`='$memberId'"); 
        echo "success";
    } else {
        echo "User not found";
    }
}
?>

 