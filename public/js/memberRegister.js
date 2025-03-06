function registerBox1() {
    var nic = document.getElementById("NICNumber").value;

    if (nic === "" || !/^(?:\d{10}|\d{12})$/.test(nic)) {
        document.getElementById("nicnumerror").innerText = "Invalid NIC. Must be 10 or 12 digits.";
        return false;
    }
    else {
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
    } else if (PhoneNumber === "" || !/^(0[1-9][0-9]{8})$/.test(PhoneNumber)) {
        document.getElementById("Pnumerror").innerText = "Please enter a valid mobile number";
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

    if (email === "" || !/^[a-zA-Z0-9._%+-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,}$/i.test(email)) {
        document.getElementById("Emailerror").innerText = "Please enter a valid email address";
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
                    alert("Check your email");

                    document.getElementById("Box3").classList.add("d-none");
                    document.getElementById("Box4").classList.remove("d-none");
                } else {
                    alert("Failed to send email. Please try again.");
                }
            })
            .catch(error => {
                console.error("Error fetching user data:", error);
            });

        return false;
    }
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
                alert("Failed to varify OTP. Please try again.");
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
                    alert("Payment processed successfully.");
                } else {
                    alert("Payment failed or was incomplete.");
                }
            })
            .catch(error => {
                console.error("Error fetching user data:", error);
            });
        register(transactionId);
        return false;
    };

    payhere.onDismissed = function () {
        alert("Payment dismissed!");
    };

    payhere.onError = function (error) {
        alert("Payment failed: " + error);
    };
};

function registerBox5() {

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
        fetch("index.php?action=showPayment", {
            method: "POST",
        })
            .then(response => response.json())
            .then(resp => {
                if (resp.status === "success") {
                    alert("Redirecting to PayHere...");

                    // Start PayHere Payment directly here
                    payhere.startPayment(resp.payment);
                } else {
                    alert("Payment Error: " + resp.error);
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
    var password = document.getElementById("Pword").value;

    var formData = new FormData();
    formData.append("nic", nic);
    formData.append("address", address);
    formData.append("mobile", mobile);
    formData.append("email", email);
    formData.append("fname", fname);
    formData.append("lname", lname);
    formData.append("password", password);
    formData.append("transactionId", transactionId);


    fetch("index.php?action=registerMember", {
        method: "POST",
        body: formData,

    })
        .then(response => response.json())
        .then(resp => {
            if (resp.success) {
                location.reload();
                window.location.href = "index.php?action=login";
            } else {
                alert("Failed to register member");
            }
        })
        .catch(error => {
            console.error("Error fetching user data:", error);
        });

    return false;
}



