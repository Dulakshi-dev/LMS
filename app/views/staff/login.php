<?php
require_once "../main.php";
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Shelf Loom || Staff Login</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            background-image: url('<?php echo Config::getImagePath("stafflog.jpg"); ?>');
            background-size: cover;
            background-position: center;
            background-repeat: no-repeat;
            background-attachment: fixed;
            overflow-x: hidden;
        }

        .login-form {
            background: rgba(0, 0, 0, 0.8);
            padding: 30px;
            border-radius: 10px;
        }
</style>

    </style>
</head>

<body class="x">
    <?php
require_once Config::getViewPath("home", "header.view.php");
?>


    <div class="container-fluid login-container">
        <h1 class="text-dark text-center m-4">Hi! Welcome Back</h1>
        <div class="row p-3 justify-content-center align-items-center">
            <div class="col-lg-4 col-md-6 text-white login-form">
                <h1 class="text-center"> Staff Login</h1>
                <form id="loginForm" action="<?php echo Config::indexPath()?>?action=loginProcess" method="POST" onsubmit="return staffLogin()">

                        <label for="username">Username:</label><br>
                        <input class="form-control mt-2" type="text" name="username" id="username" placeholder="Enter Staff ID"><br><br>

                        <label for="password">Password:</label><br>
                        <input class="form-control mt-2" type="password" name="password" id="password" placeholder="Enter Password"><br><br>

                        <div class="mt-1 bg-danger-subtle p-1 rounded-3" id="errormsgdiv">
                            <p id="errormsg" class="text-danger text-center mt-1">
                                <?php if (isset($error)) echo $error; ?>
                            </p>
                        </div>

                        <div class="row">
                            <div class="text-end ">
                                <a href="forgot-password.php" class="text-decoration-none">Forgot Password?</a>
                            </div>
                        </div>

                        <button type="submit" class="btn btn-primary w-100">Login</button>
                </form>
            </div>
        </div>
    </div>

    <?php
require_once Config::getViewPath("home", "footer-noscroll.view.php");    ?>

<script src="<?php echo Config::getJsPath("login.js"); ?>"></script>
</body>

</html>