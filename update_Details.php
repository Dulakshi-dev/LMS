<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Update Details</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
    <style>
        #box1{
            display: block;
        }
        #box2{
            display: none;
        }
    </style>
</head>
<body>

    <?php include "dash_header.php"; ?>

    <div class="d-flex">
        <div class="nav-bar">
            <?php include "dash_site_panel.php"; ?>
        </div>

        <div class="form w-100">     
            <nav class="navbar py-4 navbar-light bg-light">
                <div class="container-fluid">
                    <span class="navbar-brand mb-0 h1">Dashboard <small class="text-muted">control panel</small></span>
                    <a href="#" class="text-decoration-none h5"><i class="fa fa-home"></i> Home</a>
                </div>
            </nav>
            <div id="box1">
                <div class="container-fluid fw-bold bg-white d-flex justify-content-center mt-5">
                    <div class="row m-4">
                        <div class="text-center border-bottom border-danger border-4">
                            <h2 class="p-3">Update Details</h2>
                        </div>

                        <div class="col-md-4 text-center">
                            <div class="m-4">
                                <img class="rounded-circle" style="height: 200px; width: 200px;" src="img/use.jpg" alt="Profile Picture">
                            </div>
                            <div class="m-4">
                                <input type="file" class="form-control" placeholder="Choose file">
                            </div>
                            <div class="m-4">
                                <label for="membership-id">Membership ID</label>
                                <input id="membership-id" type="text" class="form-control">
                            </div>
                        </div>

                        <div class="col-md-8 p-4">
                            <form action="" class="mx-4 px-4">
                                <div class="row">
                                    <div class="col-lg-6 col-sm-6 form-group">
                                        <label for="first-name">First Name</label>
                                        <input id="first-name" class="form-control" type="text" name="">
                                    </div>
                                    <div class="col form-group">
                                        <label for="last-name">Last Name</label>
                                        <input id="last-name" class="form-control" type="text" name="">
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-lg-6 col-sm-6 my-3 form-group">
                                        <label for="email">Email</label>
                                        <input id="email" class="form-control" type="email" name="">
                                    </div>
                                    <div class="col my-3 form-group">
                                        <label for="phone">Phone</label>
                                        <input id="phone" class="form-control" type="text" name="">
                                    </div>
                                </div>

                                <div class="form-group my-3">
                                    <label for="address">Address</label>
                                    <textarea id="address" class="form-control" name="" rows="3"></textarea>
                                </div>

                                <div class="row">
                                    <div class="col my-3 form-group">
                                        <label for="nic">NIC Number</label>
                                        <input id="nic" class="form-control" type="text" name="">
                                    </div>
                                </div>

                                <div class="d-flex justify-content-end">
                                    <button class="btn btn-danger mx-4 mt-4" onclick="dashboard_change_password(event)">Reset Password</button>    
                                    <button class="btn btn-primary mt-4 px-4">Save</button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
            <div id="box2">
                <div class="container mt-5 p-4 bg-white rounded">
                    <h3 class="pb-3">Change Password</h3>
                    <p class="text-muted">Your new password must be between 8 and 15 characters in length</p>
                    <form>
                        <div class="row d-flex mt-5">
                            <div class="col-12 col-md-4 mb-2"><label for="new-password">New Password</label></div>
                            <div class="col-12 col-md-8"><input id="new-password" class="form-control" type="password"></div>
                        </div>

                        <div class="row d-flex my-4">
                            <div class="col-12 col-md-4 mb-2"><label for="confirm-password">Confirm Password</label></div>
                            <div class="col-12 col-md-8"><input id="confirm-password" class="form-control" type="password"></div>
                        </div>
                        
                        <div class="d-flex justify-content-end py-3 my-4">
                            <button type="button" class="btn btn-primary px-5 mx-4">Back</button>
                            <button type="submit" class="btn btn-danger px-5">Save</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
