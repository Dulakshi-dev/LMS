<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Staff Registration</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            background-image: url('<?php echo Config::getImagePath("staff-reg back.jpg"); ?>');
            background-repeat: no-repeat;
            background-size: cover;
            background-position: center;
        }

        span {
            color: red;
            font-size: 0.9em;
        }

        .v-b {
            background: rgb(37, 87, 162);
            color: white;
        }

        .box-2 {
            display: none;
        }
    </style>
</head>

<body>
    <div class="container-fluid d-flex justify-content-left align-items-center my-5">
        <div class="box-1 p-5 text-white m-3 rounded-5 shadow-lg overflow-hidden" style="width: 100%; max-width: 800px; background-color: rgba(0, 0, 0, 0.58);">
            <h3 class="text-center mb-4">Staff Registration</h3>
            <form id="registrationForm" action="<?php echo Config::indexPath() ?>?action=register" method="POST" novalidate>
                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label for="firstName" class="form-label">First Name</label>
                        <input type="text" class="form-control rounded-pill" id="firstName" name="firstName" required>
                        <span id="firstNameError"></span>
                    </div>
                    <div class="col-md-6 mb-3">
                        <label for="lastName" class="form-label">Last Name</label>
                        <input type="text" class="form-control rounded-pill" id="lastName" name="lastName" required>
                        <span id="lastNameError"></span>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label for="address" class="form-label">Address</label>
                        <input type="text" class="form-control rounded-pill" id="address" name="address" required>
                        <span id="addressError"></span>
                    </div>
                    <div class="col-md-6 mb-3">
                        <label for="phone" class="form-label">Phone No</label>
                        <input type="tel" class="form-control rounded-pill" id="phone" name="phone" required>
                        <span id="phoneError"></span>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label for="email" class="form-label">Email</label>
                        <input type="email" class="form-control rounded-pill" id="email" name="email" required>
                        <span id="emailError"></span>
                    </div>
                    <div class="col-md-6 mb-3">
                        <label for="nic" class="form-label">NIC</label>
                        <input type="text" class="form-control rounded-pill" id="nic" name="nic" required>
                        <span id="nicError"></span>
                    </div>
                </div>



                <div class="mb-3">
                    <label class="form-label">Select Role</label>
                    <span id="roleError"></span>
                    <div class="form-check">
                        <input class="form-check-input" type="radio" id="roleLibraryStaff" name="role" value="Library Staff" required>
                        <label class="form-check-label" for="roleLibraryStaff">Library Staff</label>
                    </div>
                    <div class="form-check">
                        <input class="form-check-input" type="radio" id="roleLibrarian" name="role" value="Librarian" required>
                        <label class="form-check-label" for="roleLibrarian">Librarian</label>
                    </div>
                </div>
                <div class="d-flex justify-content-center">
                    <button type="submit" class="btn v-b w-50 rounded-pill w-25">Submit</button>
                </div>
            </form>



        </div>

        <div class="box-2 rounded-5 px-3 m-5 shadow-lg overflow-hidden">
            <h2 class="text-center text-white m-4">Librarian Registration</h2>
            <p class="p-3" style="color: rgb(37, 87, 162);">An enrollment key has been sent to the email address Please check your inbox for the key. </p>
            <form class="p-3" id="enrollmentForm">
                <input type="text" class="form-control rounded-pill" name="enrollmentKey" id="enrollmentKey" placeholder="Enter Enrollment Key">
                <span id="enrollmentKeyError" class="text-danger"></span>
                <div class="text-center m-4">
                    <button type="submit" class="btn w-50 mt-3 rounded-pill v-b">Verify Key</button>
                </div>
            </form>
        </div>
    </div>

    <script>
        document.getElementById("registrationForm").addEventListener("submit", function(e) {
            e.preventDefault();

            document.querySelectorAll("span").forEach(span => span.textContent = "");

            let isValid = true;

            const firstName = document.getElementById("firstName").value.trim();
            if (firstName === "") {
                document.getElementById("firstNameError").textContent = "First Name is required.";
                isValid = false;
            }

            const lastName = document.getElementById("lastName").value.trim();
            if (lastName === "") {
                document.getElementById("lastNameError").textContent = "Last Name is required.";
                isValid = false;
            }

            const address = document.getElementById("address").value.trim();
            if (address === "") {
                document.getElementById("addressError").textContent = "Address is required.";
                isValid = false;
            }

            const phone = document.getElementById("phone").value.trim();
            if (!/^\d{10}$/.test(phone)) {
                document.getElementById("phoneError").textContent = "Phone number must be 10 digits.";
                isValid = false;
            }

            const email = document.getElementById("email").value.trim();
            if (!/^\S+@\S+\.\S+$/.test(email)) {
                document.getElementById("emailError").textContent = "Invalid email format.";
                isValid = false;
            }

            const nic = document.getElementById("nic").value.trim();
            if (!/^(?:\d{9}[VX]|\d{12})$/.test(nic)) {
                document.getElementById("nicError").textContent = "NIC must be in the correct format (9 digits + V/X or 12 digits).";
                isValid = false;
            }

            const roleSelected = document.querySelector('input[name="role"]:checked');
            if (!roleSelected) {
                document.getElementById("roleError").textContent = "Please select a role.";
                isValid = false;
            }

            if (isValid) {
                if (roleSelected.value === "Librarian") {
                    // Show the enrollment form for Librarian
                    document.querySelector(".box-1").style.display = "none";
                    document.querySelector(".box-2").style.display = "block";
                } else {
                    // If Library Staff is selected, submit the form
                    this.submit();
                }
            }
        });
    </script>

    <script src="<?php echo Config::getJsPath("staff-reg.js"); ?>"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>

</body>

</html>