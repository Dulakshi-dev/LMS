<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Shelf Loom || Member Login</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <style>
        body {
            background-image: url('<?php echo Config::getImagePath("signup.jpg"); ?>');
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
</head>

<body>


    <div class="container-fluid login-container">
        <div class="row mt-5 mb-5">
            <div class="col-12 col-lg-3 offset-3 d-lg-none">
                <h2 class="text-white">Hi! Welcome Back</h2>
            </div>
            <div class="col-12 col-lg-6">

                <div class="text-white login-form col-12 col-lg-8 offset-lg-4">
                    <h1 class="text-center">Log In</h1>

                    <?php
                    $memberid = isset($_COOKIE["memberid"]) ? $_COOKIE["memberid"] : "";
                    $memberpw = isset($_COOKIE["memberpw"]) ? $_COOKIE["memberpw"] : "";
                    $rememberChecked = isset($_COOKIE["memberid"]) ? "checked" : "";
                    ?>
                    <div class="form-group">
                        <label for="memberid">Member ID</label>
                        <input class="form-control mt-2" placeholder="M-XXXXXX" type="text" name="memid" id="memberid" value="<?php echo $memberid; ?>">
                        <span class="error text-danger" id="memberidError"></span>
                    </div>

                    <div class="form-group mt-3">
                        <label for="memberpw">Password</label>
                        <input class="form-control mt-2" placeholder="Enter your password" type="password" name="password" id="password" value="<?php echo $memberpw; ?>">
                        <span class="error text-danger" id="passwordError"></span>
                    </div>

                    <div class="row mt-2">
                        <div class="col">
                            <input type="checkbox" name="rememberme" id="rememberme" <?php echo $rememberChecked; ?>>
                            <label for="rememberme">Remember me</label>

                        </div>
                        <div class="col d-flex justify-content-end">
                            <a href="<?php echo Config::indexPathMember() ?>?action=showforgotpw" class="text-decoration-none forgot-password">Forgot Password?</a>
                        </div>
                    </div>

                    <button type="button" class="btn btn-primary w-100 my-4" onclick="login();">Login</button>

                    <div class="row">
                        <div class="col">
                            <p>Not registered yet?</p>
                        </div>
                        <div class="col d-flex justify-content-end">
                            <a href="<?php echo Config::indexPathMember() ?>?action=register" class="text-decoration-none create-account">Create an account</a>
                        </div>
                    </div>
                </div>
            </div>
            <div class="d-lg-block col-12 col-lg-3 offset-3">
                <h2 class="text-white">Hi! Welcome Back</h2>
            </div>
        </div>
    </div>


    <script src="<?php echo Config::getJsPath("memberLogin.js"); ?>"></script>

    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>

</body>

</html>