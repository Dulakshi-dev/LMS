
function showAlert(title, message, type) {
    return Swal.fire({
        title: title,
        text: message,
        icon: type, // 'success', 'error', 'warning', 'info', 'question'
        confirmButtonText: 'OK'
    });
}

function registerBox1() {
    var nic = document.getElementById("NICNumber").value;

    if (nic === "") {
        document.getElementById("nicnumerror").innerText = "NIC Required.";
        return false;
    }else if(!/^(?:\d{9}[VX]|\d{12})$/.test(nic)){
        document.getElementById("nicnumerror").innerText = "Invalid NIC.";
        return false;
    } else {
        document.getElementById("nicnumerror").innerText = "";

        document.getElementById("Box1").classList.add("d-none");
        document.getElementById("Box2").classList.remove("d-none");
        return false;
    }
}

function registerBox2() {
    var Address = document.getElementById("Address").value;
    var PhoneNumber = document.getElementById("PhoneNumber").value;

    if (Address === "") {
        document.getElementById("Addresserror").innerText = "Please enter Address";
        return false;
    } else if (PhoneNumber === "") {
        document.getElementById("Pnumerror").innerText = "Please enter mobile number";
        return false;
    }else if (!/^(?:\+94|0)([1-9][0-9])\d{7}$/.test(PhoneNumber)) {
        document.getElementById("Pnumerror").innerText = "Invalid mobile number";
        return false;
    } else {
        document.getElementById("Addresserror").innerText = "";
        document.getElementById("Pnumerror").innerText = "";

        document.getElementById("Box2").classList.add("d-none");
        document.getElementById("Box3").classList.remove("d-none");
        return false;

    }
}

function registerBox3() {
    var email = document.getElementById("email").value;

    if (email === "") {
        document.getElementById("Emailerror").innerText = "Please enter the email address";
        return false;
    }else if (!/^\S+@\S+\.\S+$/.test(email)) {
        document.getElementById("Emailerror").innerText = "Invalid email address";
        return false;
    } else {
        document.getElementById("Emailerror").innerText = "";

        var formData = new FormData();
        formData.append("email", email);

        fetch("index.php?action=sendotp", {
            method: "POST",
            body: formData,
        })
            .then(response => response.json())
            .then(resp => {
                if (resp.success) {
                    showAlert("Info", resp.message, "info");

                    document.getElementById("Box3").classList.add("d-none");
                    document.getElementById("Box4").classList.remove("d-none");

                    // Start OTP Timer
                    startOTPTimer(120); // 120 seconds (2 minutes)
                } else {
                    showAlert("Error", resp.message, "error");
                }
            })
            .catch(error => {
                console.error("Error fetching user data:", error);
            });

        return false;
    }
}

function startOTPTimer(duration) {
    let timerDisplay = document.getElementById("timer");
    let resendLink = document.getElementById("resend-link");
    let otpInputs = document.querySelectorAll(".otp-box");

    let timeLeft = duration; 

    // Disable OTP input initially
    otpInputs.forEach(input => input.disabled = false);
    resendLink.style.pointerEvents = "none"; // Disable resend link

    let countdown = setInterval(() => {
        let minutes = Math.floor(timeLeft / 60);
        let seconds = timeLeft % 60;

        // Format seconds to always show two digits
        timerDisplay.innerText = `${minutes}m : ${seconds < 10 ? "0" : ""}${seconds}s`;

        if (timeLeft <= 0) {
            clearInterval(countdown);
            timerDisplay.innerText = "OTP expired";
            
            // Disable OTP input fields
            otpInputs.forEach(input => input.disabled = true);

            // Enable resend OTP link
            resendLink.style.pointerEvents = "auto";
            resendLink.style.color = " #27ee55";
        }
        timeLeft--;
    }, 1000);
}

function resendOTP(){
    registerBox3();
}


function registerBox4() {
    var otp1 = document.getElementById("otp1").value;
    var otp2 = document.getElementById("otp2").value;
    var otp3 = document.getElementById("otp3").value;
    var otp4 = document.getElementById("otp4").value;
    var otp5 = document.getElementById("otp5").value;
    var otp6 = document.getElementById("otp6").value;

    var userotp = otp1 + otp2 + otp3 + otp4 + otp5 + otp6;

    var formData = new FormData();
    formData.append("userotp", userotp);

    fetch("index.php?action=verifyotp", {
        method: "POST",
        body: formData,
    })
        .then(response => response.json())
        .then(resp => {
            if (resp.success) {

                document.getElementById("Box4").classList.add("d-none");
                document.getElementById("Box5").classList.remove("d-none");
            } else {
                showAlert("Error", resp.message, "error");

            }
        })
        .catch(error => {
            console.error("Error fetching user data:", error);
        });

    return false;
}

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

window.onload = function () {
    payhere.onCompleted = function (transactionId) {

        let formData = new FormData();
        formData.append("transactionId", transactionId);

        fetch("index.php?action=payment_notify", {
            method: "POST",
            body: formData,
        })
            .then(response => response.json())
            .then(resp => {
                if (resp.success) {
                    register(transactionId);
                } else {
                    showAlert("Error", resp.message, "error");

                    // alert("Payment failed or was incomplete.");
                }
            })
            .catch(error => {
                console.error("Error fetching user data:", error);
            });
        return false;
    };

    payhere.onDismissed = function () {
        showAlert("Success", "Payment dismissed!", "success");

    };

    payhere.onError = function (error) {
        showAlert("Error", "Payment failed: " + error, "error");

    };
};

function registerBox5() {

    var Fname = document.getElementById("Fname").value;
    var Lname = document.getElementById("Lname").value;
    if (Fname === "" || Lname === "") {
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

        return false;
    } else {
        fetch("index.php?action=showPayment", {
            method: "POST",
        })
            .then(response => response.json())
            .then(resp => {
                if (resp.status === "success") {
                    showAlert("Success", "Redirecting to PayHere...", "success");

                    // Start PayHere Payment directly here
                    payhere.startPayment(resp.payment);
                } else {
                    showAlert("Error", "Payment Error: " + resp.error, "error");

                }
            })
            .catch(error => {
                console.error("Error fetching user data:", error);
            });

        return false;
    }
}

function register(transactionId) {
    var nic = document.getElementById("NICNumber").value;
    var address = document.getElementById("Address").value;
    var mobile = document.getElementById("PhoneNumber").value;
    var email = document.getElementById("email").value;
    var fname = document.getElementById("Fname").value;
    var lname = document.getElementById("Lname").value;

    var formData = new FormData();
    formData.append("nic", nic);
    formData.append("address", address);
    formData.append("mobile", mobile);
    formData.append("email", email);
    formData.append("fname", fname);
    formData.append("lname", lname);
    formData.append("transactionId", transactionId);


    fetch("index.php?action=registerMember", {
        method: "POST",
        body: formData,

    })
        .then(response => response.json())
        .then(resp => {
            if (resp.success) {
                
                showAlert("Success", resp.message, "success").then(() => {
                    window.location.href = "index.php?action=login";
                });
            } else {
                showAlert("Error", resp.message, "error");
            }
        })
        .catch(error => {
            console.error("Error fetching user data:", error);
        });

    return false;
}



