<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
    <title>Create Password</title>

    <style>
        body {
            background-image: url('images/login_background.png');
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
                    
                    <form class="form-group mt-1" method="POST" action="index.php?route=reset_password">
                        <div class="bg-success-subtle p-1 rounded-2 text-center mb-1">
                            <p class="text-success">Please create a new password you don't use on any other site</p>
                        </div>
                        <input type="hidden" name="vcode" value="<?php echo htmlspecialchars($_GET['code']); ?>">
                        <input class="form-control" type="password" placeholder="Create new password" id="pw">
                        <input class="form-control mt-4" type="password" placeholder="Confirm your password" id="cpw">
                        <button class="btn btn-primary mt-4 w-100" type="submit">Change</button>
                    </form>

                </div>
                
            </div>
        </div>
    </div>

    <?php
    include "footer.php";
    ?>

</body>

</html>