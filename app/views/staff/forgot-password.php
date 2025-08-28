<!-- forgot-password.php -->
<!DOCTYPE html>
<html lang="en">

<?php
$pageTitle = "Forgot Password";
$pageCss = "forgot-password.css";
require_once Config::getViewPath("common", "head.php");
?>

<body class="d-flex flex-column min-vh-100">

    <?php require_once Config::getViewPath("guest", "header.view.php"); ?>

    <main class="flex-fill container-fluid d-flex justify-content-center align-items-center">
        <div class="col-12 col-md-6 col-lg-4 text-white login-form p-4 rounded shadow">
            <h1 class="text-center mb-4">Forgot Password</h1>

            <label for="email" class="form-label my-3">Enter your Email Address</label>
            <input class="form-control mb-3" placeholder="Enter your Email" type="email" name="email" id="email">
            <div class="text-danger mb-3" id="responseMessage"></div>

            <button class="btn btn-primary w-100 my-4" id="btn" onclick="forgotpw();">
                <span id="btnText">Continue</span>
                <span class="spinner-border spinner-border-sm d-none" id="spinner" role="status"></span>
            </button>
        </div>
    </main>

    <?php require_once Config::getViewPath("common", "footer.php"); ?>

    <script src="<?php echo Config::getJsPath("staffLogin.js"); ?>"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

</body>

</html>
