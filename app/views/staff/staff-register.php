<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Staff Registration</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            background-image: url('<?php echo Config::getImagePath("stafflog.jpg"); ?>');

            background-repeat: no-repeat;
            background-size: cover;
            background-position: center;
        }
    </style>
</head>
<body>
    <div class="container d-flex justify-content-center align-items-center my-5">
        <div class="p-5 text-white rounded-5" style="width: 100%; max-width: 800px; background-color: rgba(0, 0, 0, 0.5);">
            <h3 class="text-center mb-4">Staff Registration</h3>
            <form action="<?php echo Config::indexPath()?>?action=register" method="POST">
                <div class="mb-3">
                    <label for="firstName" class="form-label">First Name</label>
                    <input type="text" class="form-control rounded-pill" id="firstName" name="firstName" required>
                </div>
                <div class="mb-3">
                    <label for="lastName" class="form-label">Last Name</label>
                    <input type="text" class="form-control rounded-pill" id="lastName" name="lastName" required>
                </div>
                <div class="mb-3">
                    <label for="address" class="form-label">Address</label>
                    <input type="text" class="form-control rounded-pill" id="address" name="address" required>
                </div>
                <div class="mb-3">
                    <label for="phone" class="form-label">Phone No</label>
                    <input type="tel" class="form-control rounded-pill" id="phone" name="phone" required>
                </div>
                <div class="mb-3">
                    <label for="email" class="form-label">Email</label>
                    <input type="email" class="form-control rounded-pill" id="email" name="email" required>
                </div>
                <div class="mb-3">
                    <label for="nic" class="form-label">NIC</label>
                    <input type="text" class="form-control rounded-pill" id="nic" name="nic" required>
                </div>
                <div class="mb-3">
                    <label class="form-label">Select Role</label>
                    <div class="form-check">
                        <input class="form-check-input" type="radio" id="roleLibraryStaff" name="role" value="Library Staff" required>
                        <label class="form-check-label" for="roleLibraryStaff">Library Staff</label>
                    </div>
                    <div class="form-check">
                        <input class="form-check-input" type="radio" id="roleLibrarian" name="role" value="Librarian" required>
                        <label class="form-check-label" for="roleLibrarian">Librarian</label>
                    </div>
                    

                </div>
                <div class="mb-3 row">
                    <div class="col-6">
                    <label for="password" class="form-label">Password</label>
                    <input type="text" class="form-control rounded-pill" id="password" name="password" required>
                    </div>

                    <div class="col-6">
                    <label for="cpassword" class="form-label">Confirm Password</label>
                    <input type="text" class="form-control rounded-pill" id="cpassword" name="cpassword" required>
                    </div>
          
                </div>
                <div class="d-flex justify-content-center">
                    <button type="submit" class="btn btn-primary rounded-pill w-25">Submit</button>
                </div>
            </form>
        </div>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>

</body>
</html>
