<!-- forgot-password.php -->
<!DOCTYPE html>
<html lang="en">

<?php
$pageTitle = "Forgot Password";
$pageCss = "member-forgot-password.css";
require_once Config::getViewPath("common", "head.php");
?>

<body class="d-flex flex-column min-vh-100">

    <?php require_once Config::getViewPath("guest", "header.view.php"); ?>

    <main class="flex-fill">
        <div class="containe py-5">
            <div class="row p-5 mt-4 justify-content-center">
                <div class="col-12 col-md-6 col-lg-4 text-white login-form m-5">
                    <h1 class="text-center pb-3">Forgot Password</h1>
                    <span class="bg-danger text-dark text-center" id="doesnt"></span>

                    <label for="email">Enter your Email Address</label>
                    <input class="form-control mt-3" placeholder="Enter your Email" type="email" name="email" id="email">
                    <div class="text-danger" id="responseMessage"></div>

                    <button class="btn btn-primary mt-4 w-100" id="btn" onclick="forgotpw();">
                        <span id="btnText">Continue</span>
                        <span class="spinner-border spinner-border-sm d-none" id="spinner" role="status"></span>
                    </button>
                </div>
            </div>
        </div>
    </main>

    <?php require_once Config::getViewPath("common", "footer.php"); ?>

    <script src="<?php echo Config::getJsPath("memberLogin.js"); ?>"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

</body>

</html>
