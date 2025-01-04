<?php
require_once "../../../main.php";
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Shelf Loom || Staff Login</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        .login-form {
        background: rgba(0, 0, 0, 0.8);
        padding: 30px;
        border-radius: 10px;
    
        }

        body{
            background-image: url('../../../public/images/stafflog.jpg'); 
            background-size: cover;
            background-position: center;
            background-repeat: no-repeat;
            background-attachment: fixed;
        }
</style>

    </style>
</head>

<body class="x">
    <?php
require_once Config::getViewPath("home", "header.view.php");
?>


    <div class="login-container my-5">
        <h1 class="text-dark text-center m-4">Hi! Welcome Back</h1>
        <div class="row p-3 justify-content-center align-items-center">
            <div class="col-lg-4 col-md-6 text-white login-form m-4">
                <h1 class="text-center"> Staff Login</h1>
                <form id="loginForm" action="<?php echo Config::indexPath()?>?action=loginProcess" method="POST" onsubmit="return staffLogin()">

                <div class="form-group">
                    <label for="username">User Name</label>
                    <input class="form-control mt-2" placeholder="Enter Staff ID" type="text" name="" id="username">
                    <span class="error text-danger" id="usernameError"></span>
                    
                </div>
                
                <div class="form-group mt-3">
                    <label for="password">Password</label>
                    <input class="form-control mt-2" placeholder="Enter your password" type="text" name="" id="password">
                    <span class="error text-danger" id="passwordError"></span>
                    
                </div>

                        <div class="row">
                            <div class = "text-start col-6">
                                <a href="<?php echo Config::indexPath() ?>?action=showregister" class="text-decoration-none">Create Account?</a>
                            </div>
                            <div class="text-end col-6">
                                <a href="<?php echo Config::indexPath() ?>?action=showforgotpw" class="text-decoration-none">Forgot Password?</a>
                            </div>
                        </div>

                        <button type="submit" class="btn btn-primary w-100 rounded-pill mt-3">Login</button>
                </form>
            </div>
        </div>
    </div>

    <?php
require_once Config::getViewPath("home", "footer.view.php");    ?>

<script src="<?php echo Config::getJsPath("../../../public/js/login.js"); ?>"></script>
</body>

</html>