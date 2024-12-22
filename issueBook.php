<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Profile Update</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.0/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
</head>
<body>
    <div class="form w-100 bg-light px-5">     
        <!-- Navbar -->
        <nav class="navbar py-4 navbar-light bg-light">
            <div class="container-fluid">
                <span class="navbar-brand mb-0 h1">
                    Dashboard <small class="text-muted">Control Panel</small>
                </span>
                <a href="#" class="text-decoration-none h5">
                    <i class="fa fa-home"></i> Home
                </a>
            </div>
        </nav>

        <!-- Update Details Section -->
        <div id="box1">
            <div class="container-fluid fw-bold bg-white d-flex justify-content-center">
                <div class="row m-4 w-100">
                    <!-- Title -->
                    <div class="text-center border-bottom border-danger border-4 mb-4">
                        <h2 class="p-3">Issue Book</h2> 
                    </div>  

                    <!-- Profile Image -->
                    <div class="col-md-4 text-center">
                        <div class="m-4">
                            <img class="rounded-circle" id="profileimg" style="height: 200px; width: 200px;" src="assets/profimg/user.jpg" alt="Profile Picture">
                        </div>
                    </div>

                    <!-- Update Form -->
                    <div class="col-md-8 p-4">
                        <form class="mx-4 px-4">
                            <!-- Name -->
                            <div class="row">
                                <div class="col-lg-6 form-group">
                                    <label for="first-name">Books ID</label>
                                    <input id="first-name" class="form-control" type="text">
                                </div>
                                <div class="col-lg-6 form-group">
                                    <label for="last-name">Membership ID</label>
                                    <input id="last-name" class="form-control" type="text">
                                </div>
                            </div>


                            <!-- Address -->
                            <div class="form-group my-3">
                                <label for="phone">Book ID</label>
                                <input id="phone" class="form-control" type="text">
                            </div>
                            <div class="form-group my-3">
                                <label for="phone">User Name</label>
                                <input id="phone" class="form-control" type="text">
                            </div>

                            <!-- NIC and DOB -->
                            <div class="row">
                                <div class="col-lg-6 my-3 form-group">
                                    <label for="nic">Date Issued</label>
                                    <input id="nic" class="form-control" type="text">
                                </div>
                                <div class="col-lg-6 my-3 form-group">
                                    <label for="dob">By Whom To Return</label>
                                    <input id="dob" class="form-control" type="text">
                                </div>
                            </div>

                            <div>
                                <img src="" alt="">
                            </div>

                            <!-- Buttons -->
                            <div class="d-flex justify-content-end">
                                <button class="btn btn-primary mt-4 px-4" type="button" onclick="updateProfile()">Issue Book</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</body>
</html>
