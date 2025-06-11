<?php
require_once "../../main.php";

require_once Config::getControllerPath("member", "authController.php");
$auth = new AuthController();

if (isset($_SESSION['member'])) {
    // If session exists, redirect to the dashboard
    header("Location: index.php?action=dashboard");
    exit;
}

?>
<!DOCTYPE html>
<html lang="en">

<?php
$pageTitle = "Login";
$pageCss = "member-login.css";
require_once Config::getViewPath("common", "head.php");
?>

<body>
    <div class="container my-5 flex-grow-1">
        <div class="mt-5 mb-5">
        
            <div class="col-12 col-lg-3 offset-lg-10 d-flex align-items-center justify-content-center">
                <h2 class="text-white fs-1">Hi!<br> Welcome Back</h2>
            </div>
            <div class="col-12 col-lg-6 pt-5">

                <div class="text-white login-form">
                    <h1 class="text-center">Log In</h1>

                    <?php
                    $memberid = "";

                    if (isset($_COOKIE["memberid"])) {
                        $memberid = $_COOKIE["memberid"];
                    }
                    ?>
                    <form id="loginForm">
                        <div class="form-group">
                            <label for="memberid">Member ID</label>
                            <input class="form-control mt-2" placeholder="M-XXXXXX" type="text" name="memid" id="memberid" value="<?php echo htmlspecialchars($memberid); ?>">
                            <span class="error text-danger" id="memberidError"></span>
                        </div>

                        <div class="form-group mt-3">
                            <label for="memberpw">Password</label>
                            <div class="input-group">
                                <input class="form-control " placeholder="Enter your password" type="password" name="memberpw" id="memberpw">
                                <span class="input-group-text" id="passwordToggle" style="cursor: pointer;">
                                    <i class="fas fa-eye" id="passwordIcon"></i>
                                </span>
                            </div>
                            <span class="error text-danger" id="passwordError"></span>
                        </div>

                        <div class="row mt-2">
                            <div class="col">
                                <input type="checkbox" name="rememberme" id="rememberme">
                                <label for="rememberme">Remember me</label>
                            </div>
                            <div class="col d-flex justify-content-end">
                                <a href="<?php echo Config::indexPathMember() ?>?action=showforgotpw" class="text-decoration-none forgot-password">Forgot Password?</a>
                            </div>
                        </div>

                        <button type="submit" class="btn btn-primary w-100 my-4">Login</button>

                        <div class="row">
                            <div class=" col-md-6">
                                <p>Not registered yet?</p>
                            </div>
                            <div class="col-md-6 d-flex justify-content-end">
                                <a href="<?php echo Config::indexPathMember() ?>?action=register" class="text-decoration-none create-account">Create an account</a>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
    <?php require_once Config::getViewPath("common", "footer.php"); ?>


    <script>
        // Handle form submit with Enter or button
        document.getElementById('loginForm').addEventListener('submit', function(event) {
            event.preventDefault();
            login();
        });

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
    <script src="<?php echo Config::getJsPath("memberLogin.js"); ?>"></script>

    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>

</body>

</html>