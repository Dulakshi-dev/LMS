<?php
require_once "../../main.php";
$fname = $_SESSION["staff"]["fname"];
$lname = $_SESSION["staff"]["lname"];
$role_name = $_SESSION["staff"]["role_name"];
$profile_img = $_SESSION["staff"]["profile_img"];

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">

    <style>
        nav ul li a img {
            height: 40px;
            width: 40px;
            border-radius: 50%;
        }

        .con {
            width: 250px;
        }

        #signup {
            display: none;
            position: absolute;
        }
    </style>
</head>

<body id="body" onload="loadprofileimg('<?php echo addslashes($profile_img); ?>');">
    <header class="text-white">
        <div class="row bg-dark m-0 pt-2 align-items-center">
            <div class="col navbar navbar-expand-lg navbar-dark">
                <h4 class="mx-2">Librarian Panel<button id="tog" class="navbar-toggler ml-2" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
                        <span class="navbar-toggler-icon"></span>
                    </button></h4>
            </div>
            <div class="col d-flex justify-content-end">
                <nav>
                    <ul class="inline d-flex align-items-center">

                        <li class="list-inline-item">
                            <a class="text-white text-decoration-none" href="#">
                                <i class="fa fa-bell mr-3"></i>
                            </a>
                        </li>

                        <li class="list-inline-item d-flex align-items-center">
                            <a id="prof" class="text-white text-decoration-none d-flex align-items-center" href="#">
                                <img src="" class="mr-2 rounded-circle" alt="" id="headerprofileimg" style="width: 40px; height: 40px;">
                                <div class="text-left">
                                    <span class="d-block"><?php echo $fname . " " . $lname; ?></span>
                                    <small><?php echo $role_name; ?></small>
                                </div>
                            </a>
                        </li>
                    </ul>
                </nav>
            </div>
        </div>

        <!-- Dropdown Content -->
        <div class="justify-content-end d-flex text-right">
            <div class="align-items-center bg-dark p-2 border" id="signup">
                <div class="text-center text-white">
                    <img src="img/use.jpg" class="bg-dark m-2" style="width: 40px; border-radius: 50%;" alt="">
                    <h4>Name - Librarian</h4>
                </div>
                <div class="">
                    <button type="button" class="btn btn-primary px-3 mx-2">Profile</button>
                    <button type="button" class="btn btn-primary mx-2">Sign Out</button>
                </div>
            </div>
        </div>

    </header>
    <script src="<?php echo Config::getJsPath("test.js"); ?>"></script>
</body>

</html>