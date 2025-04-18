<!DOCTYPE html>
<html lang="en">

<?php
$pageTitle = "Reset Password";
$pageCss = "reset-password.css";
require_once Config::getViewPath("common","head.php");
?>

<body>
    <div class="container">
        <div class="row  p-5  mt-5 justify-content-center">
            <div class="col-md-6 text-white login-form ">
                <h1 class="text-center pb-3">Reset Pasword</h1>
                <span class="bg-danger text-dark text-center" id="doesnt"></span>

                <div class="form-group mt-1">
                    <div class="bg-success-subtle p-1 rounded-2 text-center mb-4">
                        <p class="text-success">Please create a strong password you don't use on any other site</p>
                    </div>

                    <!-- New Password Field -->
                    <input class="form-control" type="password" placeholder="Create new password" id="pw" name="password">
                    <span class="error text-danger" id="pwError"></span>


                    <div id="passwordRulesContainer" class="mt-2" style="display: none;">
                        <ul class="list-unstyled mb-0">
                            <li id="rule-length" class="text-danger">At least 8 characters</li>
                            <li id="rule-uppercase" class="text-danger">At least one uppercase letter</li>
                            <li id="rule-lowercase" class="text-danger">At least one lowercase letter</li>
                            <li id="rule-digit" class="text-danger">At least one number</li>
                            <li id="rule-special" class="text-danger">At least one special character</li>
                        </ul>
                    </div>

                    <!-- Confirm Password Field -->
                    <input class="form-control mt-4" type="password" placeholder="Confirm your password" id="cpw" name="cpassword">
                    <span class="error text-danger" id="cpwError"></span>

                    <!-- Hidden Verification Code -->
                    <input type="hidden" id="vcode" name="vcode" value="<?php echo isset($_GET['vcode']) ? htmlspecialchars($_GET['vcode']) : ''; ?>">
                </div>

                <div class="form-group">
                    <button type="button" class="btn btn-primary mt-4 w-100" onclick="resetpassword();">Change</button>
                </div>
                </form>
            </div>
        </div>
    </div>
    <?php require_once Config::getViewPath("common", "footer-noscroll.view.php"); ?>

    <script src="<?php echo Config::getJsPath("login.js"); ?>"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

</body>

</html>