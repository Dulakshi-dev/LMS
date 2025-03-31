<?php
require_once "../../main.php";
$fname = $_SESSION["staff"]["fname"];
$lname = $_SESSION["staff"]["lname"];
$role_name = $_SESSION["staff"]["role_name"];
$profile_img = $_SESSION["staff"]["profile_img"];


$libraryData = LibrarySetupModel::getLibraryInfo();
$libraryName = $libraryData['name']; 
$libraryAddress = $libraryData['address']; 
$libraryEmail = $libraryData['email']; 
$libraryPhone = $libraryData['mobile']; 
$logo = $libraryData['logo']; 
$fee = $libraryData['membership_fee']; 
$fine = $libraryData['fine_amount']; 
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
            <a class="navbar-brand d-flex align-items-center" href="#">
      <img src="<?php echo Config::indexPathMember() ?>?action=servelogo&image=<?= $logo ?>" alt="library logo" width="200" height="60">
      </a>

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
                            <img src="index.php?action=serveprofimage&image=<?= !empty($profile_img) ? $profile_img : 'user.jpg'; ?>" 
                            alt="User" id="headerprofileimg" class="rounded-circle me-2" style="height: 40px; width: 40px;">                                <div class="text-left mx-3">
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
                    <img src="index.php?action=serveprofimage&image=<?= !empty($profile_img) ? $profile_img : 'user.jpg'; ?>" 
     alt="User" id="headerprofileimg" class="rounded-circle me-2" style="height: 40px; width: 40px;">

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