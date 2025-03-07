<!-- forgot-password.php -->
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Shelf Loom || Forgot Password</title>
    <style>
        body {
            background-image: url('images/login_background.png');
            background-size: cover;
            background-position: center;
            background-repeat: no-repeat;
            background-attachment: fixed;
            overflow-x: hidden;
        }

        .login-form {
            background: rgba(0, 0, 0, 0.8);
            padding: 30px;
            border-radius: 20px;
            height: 400px;

        }

        .msg {
            background-color: rgb(60, 178, 33);
            font-size: 15px;
        }

        .paw {
            background-color: rgb(60, 178, 33);
            font-size: 13px;
        }
    </style>
</head>

<body>
    <?php
require_once Config::getViewPath("home", "header.view.php");
    ?>

    <div class="container">
        <div class="row  p-5  mt-4 justify-content-center">
            <div class="col-12 col-md-6 col-lg-4 text-white login-form ">
                <h1 class="text-center pb-3">Forgot Pasword</h1>
                <span class="bg-danger text-dark text-center" id="doesnt"></span>

                    <label for="email">Enter your Email Address</label>
                    <input class="form-control mt-3" placeholder="Enter your Email" type="email" name="email" id="email">
                    <button class="btn btn-primary mt-4 w-100" onclick="forgotpw();">Continue</button>
                <div id="responseMessage"></div>


               

            </div>
        </div>
    </div>
    <?php
    require_once Config::getViewPath("home", "footer-noscroll.view.php");
    ?>

<script src="<?php echo Config::getJsPath("login.js"); ?>"></script>

</body>

</html>