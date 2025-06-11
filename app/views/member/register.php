<?php require_once __DIR__ . '/../../../main.php'; ?>

<!DOCTYPE html>
<html lang="en">

<?php
$pageTitle = "Register";
$pageCss = "member-register.css";
require_once Config::getViewPath("common", "head.php");
?>

<body>

    <?php
    require_once Config::getViewPath("guest", "header.view.php");
    ?>
    <!-- Important Notes Section -->
    <div class="text-white p-2 p-md-5 mt-3 Important">
        <h1 class="text-danger fs-1 font-weight-bold p-4">Important Notes for <span class="text-dark">Membership Registration:</span></h1>
        <ol class="py-4 fs-5 d-none d-md-block">
            Membership Fee:
            <ul class="m-3">
                <li>The annual membership fee is <span class="phone-number font-weight-bold">Rs <?= $fee ?></span></li>
            </ul>
            <ul class="m-3">
                <li>Make the payment online through the designated payment portal at the end of the registration process.</li>
            </ul>
        </ol>
        <ol class="py-1 d-block d-md-none">
            Membership Fee:
            <ul class="m-3">
                <li>The annual membership fee is <span class="phone-number font-weight-bold">Rs <?= $fee ?></span></li>
            </ul>
            <ul class="m-3">
                <li>Make the payment online through the designated payment portal at the end of the registration process.</li>
            </ul>
        </ol>
    </div>

    <!-- Registration Info Section -->
    <div class="text-center p-5 d-none d-md-block bg-white text-dark">
        <div class="line long-line m-2"></div>
        <div class="line short-line"></div>
        <h5 class="">Register today to access our extensive collection of books and other resources</h5>
    </div>
    <div class="text-center p-2 bg-white d-block d-md-none text-dark">
        <div class="line long-line m-2"></div>
        <div class="line short-line"></div>
        <p class="">Register today to access our extensive collection of books and other resources</p>
    </div>

    <!-- Registration Form Section -->
    <div class="bg-white background-container p-5">
        <div class="container p-4 my-2 col-md-12 col-lg-6">
            <div class="row">
                <div class="text-white login-form">
                    <!-- Box 1: Membership ID and NIC Number -->
                    <div id="Box1">
                        <h5 class="mb-1">Enter <b>NIC Number</b> :</h5>
                        <form id="loginForm1">
                            <div class="row mb-3">
                                <div class="col-md-12">
                                    <label for="NICNumber" class="my-2">NIC Number :</label>
                                    <input type="text" class="form-control" id="NICNumber" name="NICNumber" placeholder="Enter NIC Number">
                                    <div id="nicnumerror" class="text-danger"></div>
                                </div>
                            </div>
                            <div class="text-end mt-4">
                                <button id="btn1" type="button" class="bt text-white" onclick="registerBox1()">NEXT</button>
                            </div>
                        </form>
                    </div>

                    <!-- Box 2: Address and Phone Number -->
                    <div id="Box2" class="d-none">
                        <h5 class="mb-3">Enter <b>Address</b> and <b>Phone Number</b></h5>

                        <form id="loginForm2">
                            <div class="row mb-4">
                                <div class="col-md-6 mb-3 mb-md-0">
                                    <label for="Address" class="form-label">Address</label>
                                    <input type="text" class="form-control" id="Address" placeholder="Enter Address">
                                    <div id="Addresserror" class="text-danger small mt-1"></div>
                                </div>
                                <div class="col-md-6">
                                    <label for="PhoneNumber" class="form-label">Phone Number</label>
                                    <input type="text" class="form-control" id="PhoneNumber" placeholder="Enter Phone Number">
                                    <div id="Pnumerror" class="text-danger small mt-1"></div>
                                </div>
                            </div>

                            <div class="d-flex justify-content-between">
                                <button type="button" class="bt " id="backButton1" onclick="backToBox1()">Back</button>
                                <button type="button" class="bt" id="btn2" onclick="registerBox2()">Next</button>
                            </div>
                        </form>
                    </div>

                    <!-- Box 3: Email and Receipt Upload -->
                    <div id="Box3" class="d-none">
                        <h5 class="mb-1">Enter your <b>Email</b></h5>
                        <form id="loginForm4">
                            <div class="row mb-3">
                                <div class="col-md-12">
                                    <input type="text" class="form-control my-2" id="email" name="email" placeholder="Enter Email">
                                    <div id="Emailerror" class="text-danger"></div>
                                </div>

                            </div>
                            <div class="d-flex justify-content-between">

                                <button type="button" class="bt" id="backButton3" onclick="backToBox2()">BACK</button>

                                <div class="d-flex justify-content-end">
                                    <button id="btn4" type="button" class="bt" onclick="registerBox3()">
                                        <span id="btnText">Next</span>
                                        <span class="spinner-border spinner-border-sm d-none" id="spinner" role="status"></span>
                                    </button>
                                </div>
                            </div>
                        </form>
                    </div>

                    <!-- Box 4: OTP Verification -->
                    <div id="Box4" class="d-none">
                        <h5 class="mb-2">Enter OTP</h5>
                        <p>We have sent an OTP to your email address</p>
                        <p><span class="otp-timer">OTP expires in <span id="timer" class="text-warning"></span></span></p>
                        <form id="loginForm3">
                            <div class="otp-inputs d-flex justify-content-between mb-4">

                                <input type="text" maxlength="1" class="form-control text-center otp-box" id="otp1">
                                <input type="text" maxlength="1" class="form-control text-center otp-box" id="otp2">
                                <input type="text" maxlength="1" class="form-control text-center otp-box" id="otp3">
                                <input type="text" maxlength="1" class="form-control text-center otp-box" id="otp4">
                                <input type="text" maxlength="1" class="form-control text-center otp-box" id="otp5">
                                <input type="text" maxlength="1" class="form-control text-center otp-box" id="otp6">
                            </div>
                            <p class="resend-text">Don't receive? <a href="#" class="" id="resend-link" onclick="resendOTP()">Resend OTP</a></p>
                            <div class="d-flex justify-content-between">
                                <button type="button" class="bt" id="backButton2" onclick="backToBox3()">BACK</button>
                                <button id="btn3" type="button" class="bt" onclick="registerBox4()">NEXT</button>

                            </div>
                        </form>
                    </div>

                    <!-- Box 5: Registration Details -->
                    <div id="Box5" class="d-none">
                        <form id="loginForm5">
                            <div class="row mb-3">
                                <div class="col-md-6">
                                    <label for="Address" class="my-1">First Name:</label>
                                    <input type="text" class="form-control" id="Fname" placeholder="Enter First Name">
                                    <div id="Ferror" class="text-danger"></div>
                                </div>
                                <div class="col-md-6">
                                    <label for="PhoneNumber" class="my-1">Last Name:</label>
                                    <input type="text" class="form-control" id="Lname" placeholder="Enter Last Name">
                                    <div id="Lerror" class="text-danger"></div>
                                </div>

                            </div>
                            <div>
                                <input type="checkbox" id="agreeCheckbox">
                                <label>I agree the terms and conditions</label>
                                <div id="checkboxerror" class="text-danger"></div>

                            </div>
                            <div class="row text-center">
                                <div class="col text-white">
                                    <button id="btn5" type="button" class="bt mt-2" onclick="registerBox5()"> payment</button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <?php
    require_once Config::getViewPath("common", "footer.php");
    ?>


    <script src="<?php echo Config::getJsPath("memberRegister.js"); ?>"></script>
    <script src="https://www.payhere.lk/lib/payhere.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>

</body>

</html>