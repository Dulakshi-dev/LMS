
function showAlert(title, message, type) {
    return Swal.fire({
        title: title,
        text: message,
        icon: type, // 'success', 'error', 'warning', 'info', 'question'
        confirmButtonText: 'OK'
    });
}


function loadProfileData(id) {
    var formData = new FormData();
    formData.append("staff_id", id);

    fetch("index.php?action=loadUserData", {
        method: "POST",
        body: formData,
    })
        .then(response => response.json())
        .then(resp => {
            if (resp.success) {
                document.getElementById("staff_id").value = resp.user_id;
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


function updateProfileDetails(event) {
    event.preventDefault();  // Prevent form submission

    // Clear any previous error messages
    document.getElementById("fname_error").innerText = "";
    document.getElementById("lname_error").innerText = "";
    document.getElementById("phone_error").innerText = "";
    document.getElementById("address_error").innerText = "";

    // Get the values from the form fields
    var nic = document.getElementById("nic").value;
    var fname = document.getElementById("fname").value;
    var lname = document.getElementById("lname").value;
    var phone = document.getElementById("phone").value;
    var address = document.getElementById("address").value;
    var profimg = document.getElementById("uploadprofimg").files[0];

    var isValid = true;

    // Validate First Name
    if (fname.trim() === "") {
        document.getElementById("fname_error").innerText = "First Name is required.";
        isValid = false;
    }

    // Validate Last Name
    if (lname.trim() === "") {
        document.getElementById("lname_error").innerText = "Last Name is required.";
        isValid = false;
    }

    // Validate Mobile Number
    if (phone.trim() === "") {
        document.getElementById("phone_error").innerText = "Mobile number is required.";
        isValid = false;
    } else if (!/^(?:\+94|0)([1-9][0-9])\d{7}$/.test(phone)) {
        document.getElementById("phone_error").innerText = "Invalid Mobile Number.";
        isValid = false;
    }

    // Validate Address
    if (address.trim() === "") {
        document.getElementById("address_error").innerText = "Address is required.";
        isValid = false;
    }

    if (!isValid) {
        return;  // Stop form submission if validation fails
    }

    // Create FormData object
    var formData = new FormData();
    formData.append("nic", nic);
    formData.append("fname", fname);
    formData.append("lname", lname);
    formData.append("mobile", phone);
    formData.append("address", address);

    if (profimg) {
        formData.append("profimg", profimg);
    }

    // Send the form data to the server
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

function goToChangePassword(event) {
    event.preventDefault(); // Prevent any default behavior (if inside a form)

    // Ensure box1 is hidden and box2 is shown
    document.getElementById("box1").classList.add("d-none");
    document.getElementById("box2").style.display = "block";
    document.getElementById("box2").classList.remove("d-none");

}

function validateCurrentPassword(user_id) {
    const password = document.getElementById("currentpassword").value.trim();
    const errorMsg = document.getElementById("errormsgcurrent");

    var formData = new FormData();
    formData.append("user_id", user_id);
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


function saveNewPassword(user_id) {
    const newPassword = document.getElementById("new-password").value.trim();
    const confirmPassword = document.getElementById("confirm-password").value.trim();

    // Clear previous error messages
    document.getElementById("new-password-error").textContent = '';
    document.getElementById("confirm-password-error").textContent = '';

    let isValid = true;

    // Validate new password length
    if (newPassword.length < 8 || newPassword.length > 15) {
        document.getElementById("new-password-error").textContent = "Password must be 8-15 characters.";
        isValid = false;
    }

    // Check if passwords match
    if (newPassword !== confirmPassword) {
        document.getElementById("confirm-password-error").textContent = "Passwords do not match.";
        isValid = false;
    }

    // If the form is valid, proceed with saving the password
    if (isValid) {
        var formData = new FormData();
        formData.append("user_id", user_id);
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
                    showAlert("Error", resp.message, "error");
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
