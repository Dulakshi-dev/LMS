
function loadProfileData(id) {

    var formData = new FormData();
    formData.append("member_id", id);

    fetch("index.php?action=loadMemberData", {
        method: "POST",
        body: formData,
    })
        .then(response => response.json())
        .then(resp => {
            if (resp.success) {
                document.getElementById("member_id").value = resp.member_id;
                document.getElementById("fname").value = resp.fname;
                document.getElementById("lname").value = resp.lname;
                document.getElementById("email").value = resp.email;
                document.getElementById("phone").value = resp.mobile;
                document.getElementById("address").value = resp.address;
                document.getElementById("nic").value = resp.nic;

                var profimg = resp.profile_img;
                var profileImgElement = document.getElementById("profileimg");
                
                if (profimg == null) {
                    profileImgElement.src = "index.php?action=serveprofimage&image=user.jpg";

                } else {
                    profileImgElement.src = "index.php?action=serveprofimage&image=" + profimg;
                }
            } else {
                showAlert("Error", resp.message, "error");
            }
        })
        .catch(error => {
            console.error("Error fetching user data:", error);
        });
}


function showProfilePreview() {
    var file = document.getElementById('uploadprofimg').files[0];
    if (file) {
        const reader = new FileReader();
        reader.onload = function (e) {
            document.getElementById('profileimg').src = e.target.result;
        };
        reader.readAsDataURL(file);
    }
}

function updateProfileDetails() {
    // Get form values
    var fname = document.getElementById("fname").value;
    var lname = document.getElementById("lname").value;
    var nic = document.getElementById("nic").value;
    var phone = document.getElementById("phone").value;
    var address = document.getElementById("address").value;
    var email = document.getElementById("email").value;
    var profimg = document.getElementById("uploadprofimg").files[0];


    // Initialize error flag
    var valid = true;

    // Reset previous errors
    document.getElementById("fnameerror").innerText = "";
    document.getElementById("lnameerror").innerText = "";
    document.getElementById("nicerror").innerText = "";
    document.getElementById("phoneerror").innerText = "";
    document.getElementById("addresserror").innerText = "";
    document.getElementById("emailerror").innerText = "";

    // Validate First Name
    if (fname.trim() === "") {
        document.getElementById("fnameerror").innerText = "First name is required.";
        valid = false;
    }

    // Validate Last Name
    if (lname.trim() === "") {
        document.getElementById("lnameerror").innerText = "Last name is required.";
        valid = false;
    }

    // Validate Mobile
    if (phone.trim() === "" || !/^(?:\+94|0)([1-9][0-9])\d{7}$/.test(phone)) {
        document.getElementById("phoneerror").innerText = "Valid phone number is required (10 digits).";
        valid = false;
    }

    // Validate Address
    if (address.trim() === "") {
        document.getElementById("addresserror").innerText = "Address is required.";
        valid = false;
    }

    // If all validations pass, submit the form
    if (valid) {
        var formData = new FormData();
        formData.append("fname", fname);
        formData.append("lname", lname);
        formData.append("nic", nic);
        formData.append("phone", phone);
        formData.append("address", address);
        formData.append("email", email);

        if (profimg) {
            formData.append("profimg", profimg);
        }

        fetch("index.php?action=updateprofile", {
            method: "POST",
            body: formData,
        })
            .then(response => response.json())
            .then(resp => {
                if (resp.success) {
                    showAlert("Success", resp.message, "success").then(() => {
                        location.reload();
                    });
                } else {
                    alert("Failed to update user data. Please try again.");
                }
            })
            .catch(error => {
                console.error("Error fetching user data:", error);
            });
    }
}


function goToChangePassword() {
    document.getElementById("box1").classList.add("d-none");
    document.getElementById("box2").classList.remove("d-none");
}

function validateCurrentPassword(member_id) {
    const password = document.getElementById("currentpassword").value.trim();
    const errorMsg = document.getElementById("errormsgcurrent");

    var formData = new FormData();
    formData.append("member_id", member_id);
    formData.append("currentpassword", password);


    if (!password) {
        errorMsg.textContent = "Current password is required.";
        return;
    }

    fetch("index.php?action=validatecurrentpw", {
        method: "POST",
        body: formData,
    })
        .then(response => response.json())
        .then(resp => {
            if (resp.success) {
                document.getElementById("box2").classList.add("d-none");
                document.getElementById("box3").classList.remove("d-none");
            } else {
                errorMsg.textContent = "Incorrect password.";
            }
        })
        .catch(error => {
            console.error("Error fetching user data:", error);
        });


}


function saveNewPassword(member_id) {
    const newPassword = document.getElementById("new-password").value.trim();
    const confirmPassword = document.getElementById("confirm-password").value.trim();


    // Clear previous error messages
    document.getElementById("new-password-error").textContent = '';
    document.getElementById("confirm-password-error").textContent = '';

    let isValid = true;

    // Validate new password length
    if (newPassword === "") {
        document.getElementById("new-password-error").textContent = "Enter the new password.";
        isValid = false;
    }

    const rules = {
        length: newPassword.length >= 8,
        uppercase: /[A-Z]/.test(newPassword),
        lowercase: /[a-z]/.test(newPassword),
        digit: /[0-9]/.test(newPassword),
        special: /[\W_]/.test(newPassword)
    };
    const rulesContainer = document.getElementById("passwordRulesContainer");

    // Validate password

    if (!rules.length || !rules.uppercase || !rules.lowercase || !rules.digit || !rules.special) {
        rulesContainer.style.display = "block";
        isValid = false;

    }

    if (confirmPassword === "") {
        rulesContainer.style.display = "none";
        document.getElementById("confirm-password-error").textContent = "Confirm the new password.";
        isValid = false;
    } else if (newPassword !== confirmPassword) {
        document.getElementById("confirm-password-error").textContent = "Passwords do not match.";
        isValid = false;
    }
    

    if (isValid) {

        var formData = new FormData();
        formData.append("member_id", member_id);
        formData.append("newpassword", newPassword);

        fetch("index.php?action=savenewpw", {
            method: "POST",
            body: formData,
        })
            .then(response => response.json())
            .then(resp => {
                if (resp.success) {
                    showAlert("Success", resp.message, "success").then(() => {
                        location.reload();
                    });

                } else {
                    errorMsg.textContent = "error";
                }
            })
            .catch(error => {
                console.error("Error fetching user data:", error);
            });
    }
}

function goBack() {
    document.getElementById("box2").classList.add("d-none");
    document.getElementById("box1").classList.remove("d-none");
}

function goBackToCurrent() {
    document.getElementById("box3").classList.add("d-none");
    document.getElementById("box2").classList.remove("d-none");
}

function showAlert(title, message, type) {
    return Swal.fire({
        title: title,
        text: message,
        icon: type, // 'success', 'error', 'warning', 'info', 'question'
        confirmButtonText: 'OK'
    });
}
