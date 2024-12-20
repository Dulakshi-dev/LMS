<?php
session_start();

if (!isset($_SESSION["staff"])) {
    header("Location: staff-login.php");
    exit();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
    <style>
        .box{
            height: 400px;
            width: 500px;
            background-color: rgb(33, 33, 69);
        }
    </style>
</head>
<body>

    <div id="box1">
      <?php include "dash_header.php"; ?>
        <div class="d-flex">
            <div class="nav-bar">
                <?php include "dash_sidepanel.php"; ?>
            </div>

            <div class="container-fluid">
            <div class="row">
              <nav class="navbar py-4 navbar-light bg-light">
                    
                    <span class="navbar-brand mb-0 h1">Dashboard <small class="text-muted">control panel</small></span>
                    <a href="#" class="text-decoration-none h5"><i class="fa fa-home"></i> Home</a>
                  
              </nav>
            </div>

           <div class="row">
           <div class="fw-bold bg-white d-flex justify-content-center mt-5">
                <div class="container mt-5">
                    <div class="row g-3 d-flex">
                        <div class="col-12 col-md-5 mr-3 text-right pt-3 box rounded">
                            <button  class="btn btn-light"><a class="text-decoration-none" href="add-book.php">Add Books</a></button>
                        </div>

                        <div class="col-12 col-md-5 ml-4 text-right pt-3 box rounded">
                          <button class="btn btn-light"><a class="text-decoration-none" href="view-books.php">View Books</a></button>
                        </div>
                    </div>
                </div>
            </div>
           </div>
            </div>
            

            
           
        </div>
    </div>

</body>
</html>


