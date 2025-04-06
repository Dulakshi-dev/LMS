<?php
require_once "../../main.php";

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
            background: rgba(0, 0, 0, 0.7);
            padding: 30px;
            border-radius: 10px;

        }

        body {
            background-image: url('<?php echo Config::getImagePath("stafflog.jpg"); ?>');
            background-size: cover;
            background-position: center;
            background-repeat: no-repeat;
            background-attachment: fixed;
        }
    </style>

    </style>
</head>

<body class="x">
    <div class="login-container my-5">
        <h1 class="text-dark text-center">Hi! Welcome Back</h1>
        <div class="row p-4 justify-content-center align-items-center">
            <div class="col-lg-5 p-4 col-md-6 text-white login-form">
                <h1 class="text-center"> Staff Login</h1>

                <?php

                $staffid = "";
                $staffpw = "";

                if (isset($_COOKIE["staffid"])) {
                    $staffid = $_COOKIE["staffid"];
                }

                if (isset($_COOKIE["staffpw"])) {
                    $staffpw = $_COOKIE["staffpw"];
                }
                ?>

                    <div>
                        <label for="staffid">Staff ID:</label>
                        <input class="form-control mt-2" type="text" name="staffid" id="staffid" 
                        placeholder="Enter Staff ID" value="<?php echo $staffid; ?>" autocomplete="off">
                        <span class="error text-danger" id="staffidError"></span>
                    </div>

                    <div class="mt-3">
                        <label for="password" class="mt-3">Password:</label>
                        <input class="form-control mt-2" type="password" name="password" id="password" 
                        placeholder="Enter Password" value="<?php echo $staffpw; ?>" autocomplete="new-password">
                        <span class="error text-danger" id="passwordError"></span>
                    </div>


                    <div class="col mt-3">
                    <input type="checkbox" name="rememberme" id="rememberme" <?php echo isset($_COOKIE["staffid"]) ? "checked" : ""; ?>>
                    <label for="rememberme">Remember me</label>
                    </div>

                    <div class="mt-1 bg-danger-subtle p-1 rounded-3 d-none" id="errormsgdiv">
                        <p id="errormsg" class="text-danger text-center mt-1">
                            <?php if (isset($error)) echo $error; ?>
                        </p>
                    </div>

                    <div class="row mt-3">
                        <div class="text-start col-6">
                            <a href="<?php echo Config::indexPath() ?>?action=showregister" class="text-decoration-none">Create Account?</a>
                        </div>
                        <div class="text-end col-6">
                            <a href="<?php echo Config::indexPath() ?>?action=showforgotpw" class="text-decoration-none">Forgot Password?</a>
                        </div>
                    </div>

                    <div class="d-flex justify-content-center">
                        <button type="button" class="btn btn-primary w-75 rounded-pill mt-3" onclick="staffLogin();">Login</button>
                    </div>

                    
            </div>
        </div>
    </div>

    <script src="<?php echo Config::getJsPath("login.js"); ?>"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

</body>

</html>