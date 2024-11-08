<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Shelf Loom || Staff Login</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
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
<body class="x">
    <?php
        include "header.php";
    ?>

    <div class="container-fluid login-container">
        <h1 class="text-dark text-center m-4">Hi! Welcome Back</h1>
        <div class="row p-3 justify-content-center align-items-center">
            <div class="col-lg-4 col-md-6 text-white login-form">
                <h1 class="text-center"> Staff Login</h1>

                <div class="form-group">
                    <label for="username">User Name</label>
                    <input class="form-control mt-2" placeholder="Enter Staff ID" type="text" name="" id="username">
                    <span class="error text-danger" id="usernameError"></span>
                    
                </div>
                
                <div class="form-group mt-3">
                    <label for="password">Password</label>
                    <input class="form-control mt-2" placeholder="Enter Password" type="password" name="" id="password">
                    <span class="error text-danger" id="passwordError"></span>
                    
                </div>

                <div class="mt-1 bg-danger-subtle p-1 rounded-3 d-none" id="errormsgdiv">
                    <p id="errormsg" class="text-danger text-center mt-1"></p>
                </div>

                <div class="row">
                    <div class="text-end mt-2">
                        <a href="forgot-password.php" class="text-decoration-none">Forgot Password?</a>
                    </div>
                </div>
             
                <button class="btn btn-primary w-100 my-4" onclick="staffLogin();">Login</button>
   
            </div>            
        </div>
    </div>

    <?php
         include "footer-noscroll.php";
    ?>

    <script src="script.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
</body>
</html>