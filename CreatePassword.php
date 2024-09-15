<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    
    <title>Create Password</title>

    <style>
        body{
            background-image: url('img/login_background.png');  
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

.paw{
    background-color: rgb(60, 178, 33);
    font-size: 13px;
}

.msg{
    background-color: rgb(60, 178, 33);
    font-size: 15px;
}
    </style>
</head>
<body>
    <?php
        include "header.php";
    ?>

<div class="container">
        <div class="row  p-5  mt-5 justify-content-center">
            <div class="col-md-6 text-white login-form ">
                <h1 class="text-center pb-3">New Pasword</h1>
                <span class="bg-danger text-dark text-center" id="doesnt"></span>
                <form onsubmit="return NewPassword()">
                    <div class="form-group mt-3">
                        <div class="paw">
                            <p class="py-4 mx-3 text-dark">please crate a new password you don't use on any other site</p>
                        </div>
                        <input class="form-control" type="password" placeholder="Create new password" id="pass">
                        <span class="error text-danger" id="passError"></span>
                        <input class="form-control mt-4" type="password" placeholder="Confirm your password" id="pw">
                        <span class="error text-danger" id="pwError"></span>
                    </div>
                    <div class="form-group">
                        <button class="btn btn-primary mt-4 w-100">Change</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script src="Forgot.js"></script>

    <?php
        include "footer.php";
    ?>
</body>
</html>