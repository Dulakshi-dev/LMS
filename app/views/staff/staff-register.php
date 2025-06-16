<!DOCTYPE html>
<html lang="en">

<?php
$pageTitle = "Register";
$pageCss = "staff-register.css";
require_once Config::getViewPath("common", "head.php");
?>

<body>
    <div class="container-fluid vh-100 d-md-flex justify-content-left align-items-center my-5">

        <div class="box-1 p-3 text-dark mx-md-5 rounded-5 " style="width: 100%; max-width: 800px; ">

            <h2 class="text-center mb-4" style="color: rgb(37, 87, 162);">Staff Registration</h2>
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
            <div class="row">
                <div class="col-md-6 mb-3">
                    <label for="password" class="form-label">Password</label>
                    <input type="password" class="form-control rounded-pill" id="password" name="password" required>
                    <span id="passwordError"></span>

                    <!-- Live Password Rules -->
                    <div id="passwordRulesContainer" style="display: none;">
                        <ul id="passwordRules">
                            <li id="rule-length">Minimum 8 characters</li>
                            <li id="rule-uppercase">At least one uppercase letter</li>
                            <li id="rule-lowercase">At least one lowercase letter</li>
                            <li id="rule-digit">At least one digit</li>
                            <li id="rule-special">At least one special character</li>
                        </ul>
                    </div>
                </div>

                <div class="col-md-6 mb-3">
                    <label for="cpassword" class="form-label">Confirm Password</label>
                    <input type="password" class="form-control rounded-pill" id="cpassword" name="cpassword" required>
                    <span id="cpError"></span>
                </div>
            </div>

            <div class="d-flex justify-content-center">
                <button type="button" class="btn v-b w-50 rounded-pill w-25" onclick="submit();">Submit</button>
            </div>
            </form>
        </div>

        <div class="box-2 d-none rounded-5 px-3 my-5 shadow-lg overflow-hidden" style="backdrop-filter: blur(3px);">
            <h2 class="text-center text-white m-4">Librarian Registration</h2>
            <p class="p-3" style="color: rgb(37, 87, 162);">An enrollment key has been sent to the email address. Please check. </p>
            <input type="password" class="form-control rounded-pill" name="enrollmentKey" id="enrollmentKey" placeholder="Enter Enrollment Key">
            <span id="enrollmentKeyError" class="text-danger"></span>

            <div class="text-center m-4">
                <button id="btn1" type="button" class="btn w-50 mt-3 rounded-pill v-b" onclick="register()">Verify</button>
                <div class="spinner-border d-none" role="status" id="spinner">
                    <span class="visually-hidden">Loading...</span>
                </div>
            </div>
        </div>


    </div>

    <?php require_once Config::getViewPath("staff", "footer.php"); ?>


    <script src="<?php echo Config::getJsPath("staff-reg.js"); ?>"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>

</body>

</html>