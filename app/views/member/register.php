<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register</title>
    <link rel="stylesheet" href="reg.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
    <Style>
.Important{
    background-color: rgb(88, 84, 76);
}

.background-container {
    position: relative;
    height: 400px;
    width: 100%;
    background-image: url('../../../public/images/contact.jpg');
    background-size: cover;
    background-position: center;

    
}

.container {
    background: rgba(0, 0, 0, 0.5);
    border-radius: 20px;
    
}

.txt{
    color: black;
}

.line {
    height: 2px;
    background-color: black;
    margin: 10px auto;
}

.long-line {
    width: 90%; /* Longer line */
    
}

.short-line {
    width: 90%; /* Shorter line */
    
}

.bt {
    background-color: #000000;
    color: white; /* White text */
    padding: 10px;
    border: none; /* No border */
    border-radius: 105px; /* Rounded corners */
    cursor: pointer; /* Pointer/hand icon on hover */
    font-size: 16px; /* Font size */
    width: 150px;
}

.bt:hover {
    background-color: #141414; /* Darker green on hover */
}



.otp-inputs {
    display: flex;
    justify-content: space-between;
}

.otp-box {
    width: 50px;
    height: 50px;
    font-size: 20px;
    text-align: center;
    background-color: #333;
    color: white;
    border: 1px solid #ccc;
    border-radius: 5px;
}

.otp-timer {
    color: #ffc107;
}

.resend-text {
    color: white;
    margin-bottom: 20px;
}

.resend-text a {
    color: #27ee55;
    text-decoration: none;
}

.phone-number {
    color: #27ee55;
    font-weight: bold;
}
    </Style>
</head>
<body>

<?php include "../home/header.view.php"; ?>
    <!-- Important Notes Section -->
    <div class="text-white p-5 mt-3 Important">
        <h5 class="text-danger font-weight-bold p-4">Important Notes for <span class="text-dark">Membership Registration:</span></h5>
        <ol class="pl-4">
            <li>Visit the Library:</li>
            <li>Pay the Membership Fee:
                <ul>
                    <li>Pay the membership fee of <span class="text-success font-weight-bold">RS 1000</span> at the library.</li>
                </ul>
            </li>
            <li>Receive Your Receipt:
                <ul>
                    <li>The library office will provide you with a receipt that includes your unique Library Membership ID.</li>
                </ul>
            </li>
            <li>Complete the Online Registration:
                <ul>
                    <li>Upload a clear image of the receipt.</li>
                    <li>Enter your Library Membership ID in the registration form.</li>
                </ul>
            </li>
        </ol>
    </div>

    <!-- Registration Info Section -->
    <div class="text-center p-5 bg-white text-dark">
        <div class="line long-line m-2"></div>
        <div class="line short-line"></div>
        <h5>Register today to access our extensive collection of books and other resources</h5>
    </div>

    <!-- Registration Form Section -->
    <div class="bg-white background-container p-5">
        <div class="container p-4 my-2 col-md-6">
            <div class="row">
            <div class="text-white login-form">
    <!-- Box 1: Membership ID and NIC Number -->
    <div id="Box1">
        <h5 class="mb-1">Enter <b>membership ID</b> and <b>NIC Number</b> :</h5>
        <form id="loginForm1" onsubmit="return MemberId()">
            <div class="row mb-3">
                <div class="col-md-6">
                    <label for="membershipID" class="my-2">Membership ID :</label>
                    <input type="text" class="form-control" id="membershipID" placeholder="Enter Membership ID">
                    <div id="memerror" class="text-danger"></div>
                </div>
                <div class="col-md-6">
                    <label for="nicNumber" class="my-2">NIC Number :</label>
                    <input type="text" class="form-control" id="NICNumber" placeholder="Enter NIC Number">
                    <div id="nicnumerror" class="text-danger"></div>
                </div>
            </div>
            <div class="text-end mt-4">
                <button id="btn1" type="submit" class="bt text-white">NEXT</button>
            </div>
        </form>
    </div>

    <!-- Box 2: Address and Phone Number -->
    <div id="Box2" class="d-none">
        <h5 class="mb-1">Enter <b>Address</b> and <b>Phone Number</b> :</h5>
        <form id="loginForm2" onsubmit="return addressBox()">
            <div class="row mb-3">
                <div class="col-md-6">
                    <label for="Address" class="my-2">Address :</label>
                    <input type="text" class="form-control" id="Address" placeholder="Enter Address">
                    <div id="Addresserror" class="text-danger"></div>
                </div>
                <div class="col-md-6">
                    <label for="PhoneNumber" class="my-2">Phone Number :</label>
                    <input type="text" class="form-control" id="PhoneNumber" placeholder="Enter Phone Number">
                    <div id="Pnumerror" class="text-danger"></div>
                </div>
            </div>
            <div class="row">
                <div class="col">
                    <button type="button" class="bt" id="backButton1" onclick="backToBox1()">BACK</button>
                </div>
                <div class="col d-flex justify-content-end">
                    <button id="btn2" type="submit" class="bt">NEXT</button>
                </div>
            </div>
        </form>
    </div>

    <!-- Box 3: OTP Verification -->
    <div id="Box3" class="d-none">
        <h5 class="mb-2">Enter OTP</h5>
        <p>We have sent an OTP to your mobile number <span class="phone-number">071xxxxxxx</span></p>
        <p><span class="otp-timer text-warning">OTP expires in <span id="timer">1m : 52s</span></span></p>
        <form id="loginForm3" onsubmit="return validateOTP()">
            <div class="otp-inputs d-flex justify-content-between mb-4">
                <input type="text" maxlength="1" class="form-control text-center otp-box" id="otp1">
                <input type="text" maxlength="1" class="form-control text-center otp-box" id="otp2">
                <input type="text" maxlength="1" class="form-control text-center otp-box" id="otp3">
                <input type="text" maxlength="1" class="form-control text-center otp-box" id="otp4">
                <input type="text" maxlength="1" class="form-control text-center otp-box" id="otp5">
                <input type="text" maxlength="1" class="form-control text-center otp-box" id="otp6">
            </div>
            <p class="resend-text">Don't receive? <a href="#" class="" id="resend-link">Resend OTP</a></p>
            <div class="row">
                <div class="col">
                    <button type="button" class="bt" id="backButton2" onclick="backToBox2()">BACK</button>
                </div>
                <div class="col d-flex justify-content-end">
                    <button id="btn3" type="submit" class="bt">NEXT</button>
                </div>
            </div>
        </form>
    </div>

    <!-- Box 4: Email and Receipt Upload -->
    <div id="Box4" class="d-none">
        <h5 class="mb-1">Enter your <b>Email</b> and upload your <b>Receipt</b> :</h5>
        <form id="loginForm4" onsubmit="return EmailBox()">
            <div class="row mb-3">
                <div class="col-md-6">
                    <label for="membershipID" class="my-2">Email :</label>
                    <input type="text" class="form-control" id="Email" placeholder="Enter Email">
                    <div id="Emailerror" class="text-danger"></div>
                </div>
                <div class="col-md-6">
                    <label for="nicNumber" class="my-2">Upload Receipt :</label>
                    <input type="file" class="form-control" id="Rece" placeholder="">
                    <div id="Receerror" class="text-danger"></div>
                </div>
            </div>
            <div class="row">
                <div class="col">
                    <button type="button" class="bt" id="backButton3" onclick="backToBox3()">BACK</button>
                </div>
                <div class="col d-flex justify-content-end">
                    <button id="btn4" type="submit" class="bt">NEXT</button>
                </div>
            </div>
        </form>
    </div>

    <!-- Box 5: Registration Details -->
    <div id="Box5" class="d-none">
        <form id="loginForm5" onsubmit="return RegisterBox()">
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
                <div class="col-md-6">
                    <label for="Address" class="my-1">Password:</label>
                    <input type="password" class="form-control" id="Pword" placeholder="Enter Password">
                    <div id="Perror" class="text-danger"></div>
                </div>
                <div class="col-md-6">
                    <label for="PhoneNumber" class="my-1">Confirm Password:</label>
                    <input type="password" class="form-control" id="Cpword" placeholder="Confirm Password">
                    <div id="Cperror" class="text-danger"></div>
                </div>
            </div>
            <div>
                <input type="checkbox" id="agreeCheckbox">
                <label for="">I agree the terms and conditions</label>
            </div>
            <div class="row text-center">
                <div class="col text-white">
                    <button id="btn5" type="submit" class="bt mt-2">Register</button>
                    <div>Need help?<a class="txt text-decoration-none" href="">contact us</a></div>
                    <div>Already a member? <a class="txt text-decoration-none" href="">login here</a></div>
                </div>
            </div>
        </form>
    </div>
</div>
            </div>
        </div>
    </div>
    
    <?php include "../home/footer.view.php"; ?>
    

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
    <script>
    // Function to navigate back to Box 1
    function backToBox1() {
        document.getElementById("Box2").classList.add("d-none");
        document.getElementById("Box1").classList.remove("d-none");
    }

    // Function to navigate back to Box 2
    function backToBox2() {
        document.getElementById("Box3").classList.add("d-none");
        document.getElementById("Box2").classList.remove("d-none");
    }

    // Function to navigate back to Box 3
    function backToBox3() {
        document.getElementById("Box4").classList.add("d-none");
        document.getElementById("Box3").classList.remove("d-none");
    }

    // Function to navigate back to Box 4
    function backToBox4() {
        document.getElementById("Box5").classList.add("d-none");
        document.getElementById("Box4").classList.remove("d-none");
    }

    // Form validation for Membership ID and NIC Number
    function MemberId() {
        var membershipID = document.getElementById("membershipID").value;
        var nicNumber = document.getElementById("NICNumber").value;

        if (membershipID === "" || nicNumber === "") {
            if (membershipID === "") {
                document.getElementById("memerror").innerText = "Please enter Membership ID";
            } else {
                document.getElementById("memerror").innerText = "";
            }

            if (nicNumber === "") {
                document.getElementById("nicnumerror").innerText = "Please enter NIC Number";
            } else {
                document.getElementById("nicnumerror").innerText = "";
            }

            return false; // Prevents form submission
        } else {
            document.getElementById("Box1").classList.add("d-none");
            document.getElementById("Box2").classList.remove("d-none");
            return false;
        }
    }

    // Form validation for Address and Phone Number
    function addressBox() {
        var Address = document.getElementById("Address").value;
        var PhoneNumber = document.getElementById("PhoneNumber").value;

        if (Address === "" || PhoneNumber === "") {
            if (Address === "") {
                document.getElementById("Addresserror").innerText = "Please enter Address";
            } else {
                document.getElementById("Addresserror").innerText = "";
            }

            if (PhoneNumber === "") {
                document.getElementById("Pnumerror").innerText = "Please enter Phone Number";
            } else {
                document.getElementById("Pnumerror").innerText = "";
            }

            return false; // Prevents form submission
        } else {
            document.getElementById("Box2").classList.add("d-none");
            document.getElementById("Box3").classList.remove("d-none");
            return false;
        }
    }

    // OTP Validation
    function validateOTP() {
        let otp = '';
        for (let i = 1; i <= 6; i++) {
            const value = document.getElementById('otp' + i).value;
            if (value === '') {
                alert('Please enter the full OTP.');
                return false;
            }
            otp += value;
        }

        // If OTP is valid (6 digits), proceed to the next step
        document.getElementById("Box3").classList.add("d-none");
        document.getElementById("Box4").classList.remove("d-none");
        return false;
    }

    // Email and Receipt Upload Validation
    function EmailBox() {
        var Email = document.getElementById("Email").value;
        var Rece = document.getElementById("Rece").value;

        if (Email === "" || Rece === "") {
            if (Email === "") {
                document.getElementById("Emailerror").innerText = "Please enter a valid email address";
            } else {
                document.getElementById("Emailerror").innerText = "";
            }

            if (Rece === "") {
                document.getElementById("Receerror").innerText = "Please upload the file";
            } else {
                document.getElementById("Receerror").innerText = "";
            }

            return false; // Prevents form submission
        } else {
            document.getElementById("Box4").classList.add("d-none");
            document.getElementById("Box5").classList.remove("d-none");
            return false;
        }
    }

    // Registration Form Validation
    function RegisterBox() {
        var Fname = document.getElementById("Fname").value;
        var Lname = document.getElementById("Lname").value;
        var Pword = document.getElementById("Pword").value;
        var Cpword = document.getElementById("Cpword").value;

        if (Fname === "" || Lname === "" || Pword === "" || Cpword === "") {
            if (Fname === "") {
                document.getElementById("Ferror").innerText = "First name is required";
            } else {
                document.getElementById("Ferror").innerText = "";
            }

            if (Lname === "") {
                document.getElementById("Lerror").innerText = "Last name is required";
            } else {
                document.getElementById("Lerror").innerText = "";
            }

            if (Pword === "") {
                document.getElementById("Perror").innerText = "Password is required";
            } else if (Pword.length < 6) {
                document.getElementById("Perror").innerText = "Password must be at least 6 characters.";
            } else {
                document.getElementById("Perror").innerText = "";
            }

            if (Cpword === "") {
                document.getElementById("Cperror").innerText = "Password confirmation is required";
            } else if (Pword !== Cpword) {
                document.getElementById("Cperror").innerText = "Passwords do not match";
            } else {
                document.getElementById("Cperror").innerText = "";
            }

            return false;
        } else {
            alert("Registration successful!");
            return true;
        }
    }
</script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
</body>
</html>
