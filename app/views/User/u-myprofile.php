<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Update Details</title>
  <link
    href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.0/css/bootstrap.min.css"
    rel="stylesheet"
  />
  <link
    href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css"
    rel="stylesheet"
  />
</head>
<body class="bg-light">
  <div class="d-flex flex-column bg-white m-5">

    <div class="container my-4">
      <div class="row">
        <div class="col-12 text-center border-bottom border-danger border-4 mb-4">
          <h2 class="p-2">Update Details</h2>
        </div>
      </div>

      <div class="row">
        <div class="col-md-3 text-center p-4">
          <div class="mb-4">
            <img id="profileimg" style="height: 280px; width: 280px;" src="../../../public/images/contact.jpg" alt="Profile Picture">
          </div>
          <div class="mb-4">
            <input type="file" id="uploadprofimg" name="uploadprofimg" class="form-control" onchange="showProfilePreview()">
          </div>
          <div class="mb-4">
            <label for="staff_id">User ID</label>
            <input id="staff_id" name="staff_id" type="text" class="form-control" disabled>
          </div>
        </div>

        <div class="col-md-9 p-4">
          <form>
            <div class="row">
              <div class="col-lg-6 col-sm-6 mb-3">
                <label for="fname">First Name</label>
                <input id="fname" name="fname" class="form-control" type="text">
              </div>
              <div class="col-lg-6 col-sm-6 mb-3">
                <label for="lname">Last Name</label>
                <input id="lname" name="lname" class="form-control" type="text">
              </div>
            </div>

            <div class="row">
              <div class="col-lg-6 col-sm-6 mb-3">
                <label for="email">Email</label>
                <input id="email" name="email" class="form-control" type="email" disabled>
              </div>
              <div class="col-lg-6 col-sm-6 mb-3">
                <label for="phone">Phone</label>
                <input id="phone" name="phone" class="form-control" type="text">
              </div>
            </div>

            <div class="mb-3">
              <label for="address">Address</label>
              <textarea id="address" name="address" class="form-control" rows="2"></textarea>
            </div>

            <div class="row">
              <div class="col-lg-6 col-sm-6 mb-3">
                <label for="nic">NIC Number</label>
                <input id="nic" name="nic" class="form-control" type="text">
              </div>
              <div class="col-lg-6 col-sm-6 mb-3">
                <label for="dob">Date of Birth</label>
                <input id="dob" name="dob" class="form-control" type="date">
              </div>
            </div>

            <div class="row">
              <div class="col-lg-6 col-sm-6 mb-3">
                <label for="district">District</label>
                <input id="district" name="district" class="form-control" type="text">
              </div>
              <div class="col-lg-6 col-sm-6 mb-3">
                <label for="city">City</label>
                <input id="city" name="city" class="form-control" type="text">
              </div>
            </div>

            <div class="d-flex justify-content-end">
              <button type="button" class="btn btn-danger mx-2" onclick="dashboard_change_password(event)">Reset Password</button>
              <button type="button" class="btn btn-primary" onclick="updateProfileDetails()">Save</button>
            </div>
          </form>
        </div>
      </div>
    </div>
  </div>

  <div id="box2">
        <div class="container mt-5 p-4 bg-white rounded" style="width: 800px;">
            <h3 class="pb-3">Change Password</h3>
            <form  method="POST">
                <div class="row d-flex mt-5">
                    <div class="col-12 col-md-4 mb-2"><label for="new-password">Current Password</label></div>
                    <div class="col-12 col-md-8"><input id="new-password" class="form-control" type="password"></div>
                </div>

                <div id="errormsg" class="text-danger"></div>

                <div class="d-flex justify-content-end py-3 my-4">
                    <button class="btn btn-primary px-5 mx-4">Back</button>
                    <button class="btn btn-danger px-5">Next</button>
                </div>
            </form>
        </div>
    </div>
  <div id="box3">
        <div class="container mt-5 p-4 bg-white rounded" style="width: 800px;">
            <h3 class="pb-3">Change Password</h3>
            <p class="text-muted">Your new password must be between 8 and 15 characters in length</p>
            <form  method="POST">
                <div class="row d-flex mt-5">
                    <div class="col-12 col-md-4 mb-2"><label for="new-password">New Password</label></div>
                    <div class="col-12 col-md-8"><input id="new-password" class="form-control" type="password"></div>
                </div>

                <div class="row d-flex my-4">
                    <div class="col-12 col-md-4 mb-2"><label for="confirm-password">Confirm Password</label></div>
                    <div class="col-12 col-md-8"><input id="confirm-password" class="form-control" type="password"></div>
                </div>

                <div id="errormsg" class="text-danger"></div>

                <div class="d-flex justify-content-end py-3 my-4">
                    <button class="btn btn-primary px-5 mx-4">Back</button>
                    <button class="btn btn-danger px-5">Save</button>
                </div>
            </form>
        </div>
    </div>

  <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.0/js/bootstrap.bundle.min.js"></script>
</body>
</html>
