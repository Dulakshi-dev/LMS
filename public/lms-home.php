<!DOCTYPE html>

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale = 1.0">   
    <link rel="stylesheet" href="bootstrap.css" />

    <title>LMS | Home</title>
</head>
<style>
    body{
        overflow-x:hidden;
    }
    .div1 {
    background-image: url("images/test.jpg");
    height: 90vh;
    width: 100%; 
    background-repeat: no-repeat;
    background-size: cover; 
    background-position: center; 
}

    .div2{
        background-color: black;
    }

    .span1{
        color: red;
    }

    .span2{
        color: aliceblue;
    }

    .div3{
    
        border-bottom: solid 4px;
        border-right: solid 4px;
        border-color: black;
        padding-bottom: 5px;
        padding: 2px;
        border-bottom-right-radius: 10px; 
        margin-bottom: 20px;
    }
</style>

<body>
    <?php
        include "header.php";
    ?>

    <div class="container-fluid">
        <div class="row">
            <div class="col-12 div1">
                <div class="row vh-100 d-flex justify-content-center align-items-center">
                    <div class="col-12 col-lg-6 offset-lg-6">
                        <div class="row ">
                            <div class="text-center">
                                <div class="col-12">
                                    <h1 class="mb-5 fw-bold">Library LMS - Institute Name</h1>
                                    <p class="fs-5 mt-5">Register now to access our full range of features and <br> start exploring our vast collection of resources</p>

                                </div>
                                <div class=" mt-5">
                                    <button class="btn btn-dark rounded-5 w-25" onclick="window.location.href='register.php'">Register</button>
                                    <button class="btn btn-secondary ms-5 rounded-5 w-25" onclick="window.location.href='login.php'">Login</button>
                                </div>

                            </div>
                        </div>

                    </div>
                </div>
            </div>
        </div>

        <div class= "row mt-2 div2">
            <h1 class="mt-3 ms-4 fw-bold"><span class="span1">Top</span> <span class="span2">Books</span></h1>
            <div class="d-flex row mt-5 mb-5 ms-5 gap-5">

                <div class="card" style="width: 18rem;">
                    <img src="..." class="card-img-top" alt="...">
                    <div class="card-body">
                        <h5 class="card-title">Card title</h5>
                        <p class="card-text">Some quick example text to build on the card title and make up the bulk of the card's content.</p>
                    </div>
                </div>

                <div class="card" style="width: 18rem;">
                    <img src="..." class="card-img-top" alt="...">
                    <div class="card-body">
                        <h5 class="card-title">Card title</h5>
                        <p class="card-text">Some quick example text to build on the card title and make up the bulk of the card's content.</p> 
                    </div>
                </div>

                <div class="card" style="width: 18rem;">
                    <img src="..." class="card-img-top" alt="...">
                    <div class="card-body">
                        <h5 class="card-title">Card title</h5>
                        <p class="card-text">Some quick example text to build on the card title and make up the bulk of the card's content.</p>
                    </div>
                </div>

            </div>
        </div>

        <div class="row d-none d-md-block">
            
            <div class="d-flex justify-content-end col-10 div3">
                <img src="images/quotes.jpg" height="600px" class="rounded-4 me-5 mb-5 mt-3">
                
            </div>
        </div>    
    </div>

    <?php
        include "footer.php";
    ?>

    <script src="bootstrap.bundle.js"></script>
</body>

</html>