<?php
include "connection.php";
$book_id = $_POST["book_id"];
$isbn = $_POST["isbn"];
$title = $_POST["title"];
$author = $_POST["author"];
$pub_year = $_POST["pub_year"];
$qty = $_POST["quantity"];
$des = $_POST["description"];
$category_id = $_POST["category_id"];


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
} else {
    // Check if the book exists
    $rs = Database::search("SELECT * FROM book WHERE book_id = '$book_id'");
    $num = $rs->num_rows;

    if ($num > 0) {
        // Update the book details
        Database::iud("UPDATE book SET
            `isbn` = '$isbn',
            `title` = '$title', 
            `author` = '$author', 
            `pub_year` = '$pub_year', 
            `qty` = '$qty', 
            `category_id` = '$category_id', 
             `description` = '$des'
            WHERE `book_id` = '$book_id'");
        
        echo "success";
    } else {
        echo "Book not found.";
    }
}
?>
