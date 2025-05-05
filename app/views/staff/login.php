<?php
require_once "../../main.php";

require_once Config::getControllerPath("staff", "authController.php");
$auth = new AuthController();

if (isset($_SESSION['staff'])) {
    // If session exists, redirect to the dashboard
    header("Location: index.php?action=dashboard");
    exit;
}
?>

<!DOCTYPE html>
<html lang="en">

<?php
$pageTitle = "Login";
$pageCss = "staff-login.css";
require_once Config::getViewPath("common", "head.php");
?>

<body class="x d-flex flex-column min-vh-100">
    <div class="container my-5 flex-grow-1">
        <h1 class="text-dark text-center">Hi! Welcome Back</h1>
        <div class="row p-4 justify-content-center align-items-center">
            <div class="col-lg-5 p-4 col-md-6 text-white login-form col-md-10 col-lg-6">
                <h1 class="text-center"> Staff Login</h1>

                <?php
                $staffid = "";

                if (isset($_COOKIE["staffid"])) {
                    $staffid = $_COOKIE["staffid"];
                }
                ?>
                <div>
                    <label for="staffid">Staff ID:</label>
                    <input class="form-control mt-2" type="text" name="staffid" id="staffid"
                        placeholder="Enter Staff ID" value="<?php echo htmlspecialchars($staffid); ?>">
                    <span class="error text-danger" id="staffidError"></span>
                </div>

                <div class="form-group mt-3">
                    <label for="password">Password</label>
                    <div class="input-group">
                        <input class="form-control " placeholder="Enter your password" type="password" name="password" id="password">
                        <span class="input-group-text" id="passwordToggle" style="cursor: pointer;">
                            <i class="fas fa-eye" id="passwordIcon"></i>
                        </span>
                    </div>
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
    <?php require_once Config::getViewPath("staff", "footer.php"); ?>

    <script>
        document.getElementById('passwordToggle').addEventListener('click', function() {
            var passwordField = document.getElementById('password');
            var passwordIcon = document.getElementById('passwordIcon');

            if (passwordField.type === "password") {
                passwordField.type = "text";
                passwordIcon.classList.remove("fa-eye");
                passwordIcon.classList.add("fa-eye-slash");
            } else {
                passwordField.type = "password";
                passwordIcon.classList.remove("fa-eye-slash");
                passwordIcon.classList.add("fa-eye");
            }
        });
    </script>

    <script src="<?php echo Config::getJsPath("staffLogin.js"); ?>"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

</body>

</html>