<?php 
include "connection.php"; 

$memberId = $_POST["membership-id"]; 
$firstName = $_POST["first-name"]; 
$lastName = $_POST["last-name"]; 
$email = $_POST["email"]; 
$phone = $_POST["phone"]; 
$address = $_POST["address"]; 
$nic = $_POST["nic"]; 

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
    $imgPath = $data["profile_img"] ?? "assets/profimg/user.jpg";

    if (isset($_FILES["img"]) && !empty($_FILES["img"]["name"])) { 
        if (!empty($imgPath) && file_exists($imgPath) && $imgPath != "assets/profimg/user.jpg") { 
            unlink($imgPath); 
        }    
        $imgPath = "assets/profimg/" . uniqid() . "-" . basename($_FILES["img"]["name"]);     
        move_uploaded_file($_FILES["img"]["tmp_name"], $imgPath); 
    } 

    if ($data) {
        Database::iud("UPDATE member SET `fname`='$firstName', `lname`='$lastName', `mobile`='$phone', `email`='$email', `address`='$address', `nic`='$nic',`profile_img`='$imgPath' WHERE `member_id`='$memberId'"); 
        echo "success";
    } else {
        echo "User not found";
    }
}
?>

 