<?php 
include "connection.php"; 

$membershipId = $_POST["membership-id"]; 
$firstName = $_POST["first-name"]; 
$lastName = $_POST["last-name"]; 
$email = $_POST["email"]; 
$phone = $_POST["phone"]; 
$address = $_POST["address"]; 
$nic = $_POST["nic"]; 

// Input validation
if (empty($firstName)) { 
    echo("Please enter the first name"); 
} else if (empty($lastName)) { 
    echo("Please enter the last name"); 
} else if (empty($email)) { 
    echo("Please enter the email"); 
} else if (empty($phone)) { 
    echo("Please enter the phone number"); 
} else if (empty($address)) { 
    echo("Please enter the address"); 
} else if (empty($nic)) { 
    echo("Please enter the NIC number"); 
} else { 
    // Update user details
    $rs2 = Database::search("SELECT * FROM users WHERE membership_id='$membershipId'"); 
    $data = $rs2->fetch_assoc();   
    if ($data) {
        Database::iud("UPDATE users SET first_name='$firstName', last_name='$lastName', phone='$phone', email='$email', address='$address', nic='$nic' WHERE membership_id='$membershipId'"); 
        echo("success");
    } else {
        echo("User not found");
    }
}
?>
