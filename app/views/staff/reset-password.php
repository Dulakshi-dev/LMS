<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">

    <title>Create Password</title>

    <style>
        body {
            background-image: url('<?php echo Config::getImagePath("login_background.png"); ?>');
            background-size: cover;
            background-position: center;
            background-repeat: no-repeat;
            background-attachment: fixed;
        }

        .login-form {
            background: rgba(0, 0, 0, 0.8);
            padding: 30px;
            border-radius: 20px;
            height: 400px;

        }
    </style>
</head>

<body>
    <div class="container">
        <div class="row  p-5  mt-5 justify-content-center">
            <div class="col-md-6 text-white login-form ">
                <h1 class="text-center pb-3">Reset Pasword</h1>
                <span class="bg-danger text-dark text-center" id="doesnt"></span>

                    <div class="form-group mt-1">
                        <div class="bg-success-subtle p-1 rounded-2 text-center mb-1">
                            <p class="text-success">Please create a new password you don't use on any other site</p>
                        </div>

                        <!-- New Password Field -->
                        <input class="form-control" type="password" placeholder="Create new password" id="pw" name="password">
                        <span class="error text-danger" id="pwError"></span>

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
    <script>

</script>
    <script src="<?php echo Config::getJsPath("login.js"); ?>"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

</body>

</html>