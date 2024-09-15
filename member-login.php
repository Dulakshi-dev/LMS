<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Shelf Loom || Member Login</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <style>
        body{
            background-image: url('img/signup.jpg'); 
            background-size: cover;
            background-position: center;
            background-repeat: no-repeat;
            background-attachment: fixed;
            overflow-x: hidden;
        }


        .login-form {
            background: rgba(0, 0, 0, 0.8);
            padding: 30px;
            border-radius: 10px;
            
        }

        .x{
            background-image: url('img/staff\ log.jpg'); 
            background-size: cover;
            background-position: center;
            background-repeat: no-repeat;
            background-attachment: fixed;
        }
    </style>
</head>
<body>

    <?php
        include "header.php";
    ?>

    <div class="container-fluid login-container">
        <div class="row mt-5 mb-5">
            <div class="col-12 col-lg-3 offset-3 d-lg-none">
                <h2 class="text-white">Hi! Welcome Back</h2>
            </div>
            <div class="col-12 col-lg-6">
               
                <div class="text-white login-form col-12 col-lg-8 offset-lg-4">
                    <h1 class="text-center">Log In</h1>

                    <div class="form-group">
                        <label for="username">User Name</label>
                        <input class="form-control mt-2" placeholder="eg:STU2220009" type="text" name="" id="username">
                        <span class="error text-danger" id="usernameError"></span>
                        
                    </div>
                    
                    <div class="form-group mt-3">
                        <label for="password">Password</label>
                        <input class="form-control mt-2" placeholder="Enter your password" type="text" name="" id="password">
                        <span class="error text-danger" id="passwordError"></span>
                        
                    </div>

                    <div class="row mt-2">
                        <div class="col">
                            <input type="checkbox" name="Remember me" id=""rememberme>
                            <label for="rememberme">Remember me</label>
                        </div>
                        <div class="col d-flex justify-content-end">
                            <a href="forgot-password.php" class="text-decoration-none forgot-password">Forgot Password?</a>
                        </div>
                    </div>
                
                    
                    <button type="submit" class="btn btn-primary w-100 my-4" onclick="memberLogin();">Login</button>

                    <div class="row">
                        <div class="col">
                            <p>Not registered yet?</p>
                        </div>
                        <div class="col d-flex justify-content-end">
                            <a href="" class="text-decoration-none create-account">Create an account</a>
                        </div>
                    </div>
                
                </div>
            </div>
            <div class="d-lg-block col-12 col-lg-3 offset-3">
                <h2 class="text-white">Hi! Welcome Back</h2>
            </div>
            
        </div>

    </div>

    <?php
        include "footer.php";
    ?>

    <script src="script.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
   
</body>
</html>