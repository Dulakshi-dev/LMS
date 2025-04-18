<!-- forgot-password.php -->
<!DOCTYPE html>
<html lang="en">

<?php
$pageTitle = "Forgot Password";
$pageCss = "member-forgot-password.css";
require_once Config::getViewPath("home","head.php");
?>

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
                    <div id="responseMessage"></div>

                    <button class="btn btn-primary mt-4 w-100" onclick="forgotpw();">Continue</button>
            </div>
        </div>
    </div>
    <?php
    require_once Config::getViewPath("home", "footer-noscroll.view.php");
    ?>

<script src="<?php echo Config::getJsPath("memberLogin.js"); ?>"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

</body>

</html>