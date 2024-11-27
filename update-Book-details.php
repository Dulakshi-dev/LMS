<?php
include "connection.php";

$isbn = $_POST["isbn"];
$title = $_POST["title"];
$author = $_POST["author"];
$pub_year = $_POST["pub_year"];
$qty = $_POST["qty"];
$category_id = $_POST["category_id"];
$status_id = $_POST["status_id"];

// Input validation
if (empty($isbn)) {
    echo "Please enter the ISBN.";
} elseif (empty($title)) {
    echo "Please enter the book title.";
} elseif (empty($author)) {
    echo "Please enter the author name.";
} elseif (empty($pub_year)) {
    echo "Please enter the publication year.";
} elseif (empty($qty)) {
    echo "Please enter the quantity.";
} elseif (empty($category_id)) {
    echo "Please select a category.";
} elseif (empty($status_id)) {
    echo "Please select a status.";
} else {
    // Check if the book exists
    $rs = Database::search("SELECT * FROM book WHERE isbn = '$isbn'");
    $num = $rs->num_rows;

    if ($num > 0) {
        // Update the book details
        Database::iud("UPDATE book SET 
            `title` = '$title', 
            `author` = '$author', 
            `pub_year` = '$pub_year', 
            `qty` = '$qty', 
            `category_id` = '$category_id', 
            `status_id` = '$status_id'
            WHERE `isbn` = '$isbn'");
        
        echo "Book details updated successfully.";
    } else {
        echo "Book not found.";
    }
}
?>
