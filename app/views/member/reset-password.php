<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
    <title>Create Password</title>

    <style>
        body {
            background-image: url('../../../public/images/login_background.png');
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

        .msg {
            background-color: rgb(60, 178, 33);
            font-size: 15px;
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

                    <form class="form-group mt-4" method="POST" action="index.php?route=reset_password" onsubmit="return validatePassword()">
                        <div class="bg-success-subtle p-1 rounded-2 text-center mb-4">
                            <p class="text-success">Please create a new password you don't use on any other site</p>
                        </div>
                        <!-- Hidden Verification Code -->
                        <input type="hidden" name="vcode" value="<?php echo isset($_GET['code']) ? htmlspecialchars($_GET['code']) : ''; ?>">

                        <input class="form-control" type="password" placeholder="Create new password" id="pw" name="password">
                        <span class="error text-danger" id="pwError"></span>

                        <input class="form-control mt-4" type="password" placeholder="Confirm your password" id="cpw" name="cpassword">
                        <span class="error text-danger" id="cpwError"></span>
                        <div class="text-center">
                            <button class="btn btn-primary mt-4 w-75" type="submit">Change</button>
                        </div>
                        
                    </form>

                </div>

            </div>
        </div>
    </div>


    <script>
        function validatePassword() {
            var pw = document.getElementById("pw").value.trim();
            var cpw = document.getElementById("cpw").value.trim();
            var pwError = document.getElementById("pwError");
            var cpwError = document.getElementById("cpwError");

            pwError.textContent = "";
            cpwError.textContent = "";

            var pwPattern = /^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[@$!%*?&])[A-Za-z\d@$!%*?&]{8,}$/;

            if (pw === "") {
                pwError.textContent = "Password is required.";
                return false;
            }

            if (!pw.match(pwPattern)) {
                pwError.textContent = "Password must be at least 8 characters, include uppercase, lowercase, number, and special character.";
                return false;
            }

            if (cpw === "") {
                cpwError.textContent = "Please confirm your password.";
                return false;
            }

            if (pw !== cpw) {
                cpwError.textContent = "Passwords do not match.";
                return false;
            }

            return true;
        }
    </script>


</body>

</html>