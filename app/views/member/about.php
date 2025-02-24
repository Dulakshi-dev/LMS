<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>About Us - Library</title>
    <link rel="stylesheet" href="bootstrap.css">
    <style>
        body{
    overflow-x: hidden;
}
.main {
    background-image: url("<?php echo Config::getImagePath("about.png"); ?>");
    overflow-x: hidden;
    height: 180vh;
    background-repeat: no-repeat;
    background-size: cover; 
    background-position-y: -200px; 
    text-align: justify;
 
}

@media (max-width: 600px) {
    .main {
      
        height: 200vh; 
        background-position-y: 0px;
        background-position-x: -200px; 
    }
}

.about{
  
    margin: 20px auto;
    padding: 20px;
    background-color: none;
    box-shadow: 0 0 10px rgba(0,0,0,0.1);
    height: 200vh;
}
    </style>
</head>
<body>
    <?php
require_once Config::getViewPath("home", "header.view.php");
?>
    <div class = "container-fluid">
        <div class="row main">
            <div class="col-8 offset-2 mt-5">
                
                <h1 class="text-light">About Us</h1>
                <p class="fs-5 text-light mt-5">Welcome to SHELFLOOM , where knowledge meets convenience. Our library management system is dedicated to revolutionizing the way you interact with library resources. At SHELFLOOM, we believe in the power of information and the joy of reading. Our mission is to provide an accessible, user-friendly platform that caters to the needs of both library staff and patrons.</p>
                <p class="fs-5 text-light mt-5">Our system is designed with efficiency in mind, streamlining tasks such as cataloging, borrowing, and returning books. This allows our dedicated staff to focus on what matters most: helping you discover new knowledge and enjoy your reading journey. We offer a comprehensive catalog of books, e-books, and other resources, all easily searchable and accessible.</p>
                <p class="fs-5 text-light mt-5">We are committed to continuous improvement and innovation, ensuring that our library management system remains at the forefront of technology. Our team works tirelessly to enhance the user experience, providing features that make managing and using library resources simpler and more efficient.</p>
            </div>
        </div>
    </div>
   
    <?php
require_once Config::getViewPath("home", "footer.view.php");
?>
        
    
    
    </body>
</html>
