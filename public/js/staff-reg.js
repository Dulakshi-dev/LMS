
function showAlert(title, message, type) {
    return Swal.fire({
        title: title,
        text: message,
        icon: type, // 'success', 'error', 'warning', 'info', 'question'
        confirmButtonText: 'OK'
    });
}

document.addEventListener("DOMContentLoaded", () => {
    const passwordInput = document.getElementById("password");
    const cpasswordInput = document.getElementById("cpassword");
    const rulesContainer = document.getElementById("passwordRulesContainer");

    // Show the password rules when the password field is focused
    if (passwordInput && rulesContainer) {
        passwordInput.addEventListener("focus", () => {
            rulesContainer.style.display = "block";
        });

        cpasswordInput.addEventListener("focus", () => {
            rulesContainer.style.display = "none";
        });

        // Update password rules on input
        passwordInput.addEventListener("input", function () {
            const password = this.value;

            const rules = {
                length: password.length >= 8,
                uppercase: /[A-Z]/.test(password),
                lowercase: /[a-z]/.test(password),
                digit: /[0-9]/.test(password),
                special: /[\W_]/.test(password)
            };

            updateRule("rule-length", rules.length);
            updateRule("rule-uppercase", rules.uppercase);
            updateRule("rule-lowercase", rules.lowercase);
            updateRule("rule-digit", rules.digit);
            updateRule("rule-special", rules.special);
        });
    }
});

function updateRule(id, condition) {
    const el = document.getElementById(id);
    if (el) {
        el.classList.toggle("valid", condition); // Apply valid class if the rule is satisfied
        el.classList.toggle("invalid", !condition); // Apply invalid class if the rule is not satisfied
    }
}

function submit() {
    // Clear all previous errors
    document.querySelectorAll("span").forEach(span => span.textContent = "");

    let isValid = true;

    // First name
    const firstName = document.getElementById("firstName").value.trim();
    if (firstName === "") {
        document.getElementById("firstNameError").textContent = "First name is required.";
        isValid = false;
    }

    // Last name
    const lastName = document.getElementById("lastName").value.trim();
    if (lastName === "") {
        document.getElementById("lastNameError").textContent = "Last name is required.";
        isValid = false;
    }

    // Address
    const address = document.getElementById("address").value.trim();
    if (address === "") {
        document.getElementById("addressError").textContent = "Address is required.";
        isValid = false;
    }

    // Phone number
    const phone = document.getElementById("phone").value.trim();
    if (phone === "") {
        document.getElementById("phoneError").textContent = "Mobile Number Required.";
        isValid = false;
    } else if (!/^(?:\+94|0)([1-9][0-9])\d{7}$/.test(phone)) {
        document.getElementById("phoneError").textContent = "Invalid Mobile Number.";
        isValid = false;
    }

    // Email
    const email = document.getElementById("email").value.trim();
    if (email === "") {
        document.getElementById("emailError").textContent = "Email required.";
        isValid = false;
    } else if (!/^\S+@\S+\.\S+$/.test(email)) {
        document.getElementById("emailError").textContent = "Invalid email.";
        isValid = false;
    }

    // NIC
    const nic = document.getElementById("nic").value.trim();
    if (nic === "") {
        document.getElementById("nicError").textContent = "NIC Required.";
        isValid = false;
    } else if (!/^(?:\d{9}[VXvx]|\d{12})$/.test(nic)) {
        document.getElementById("nicError").textContent = "Invalid NIC.";
        isValid = false;
    }

    // Role selection
    const roleSelected = document.querySelector('input[name="role"]:checked');
    if (!roleSelected) {
        document.getElementById("roleError").textContent = "Please select a role.";
        isValid = false;
    }

    // Password validation
    const password = document.getElementById("password").value;
    const confirmPassword = document.getElementById("cpassword").value;

    const passwordRules = {
        length: password.length >= 8,
        uppercase: /[A-Z]/.test(password),
        lowercase: /[a-z]/.test(password),
        digit: /[0-9]/.test(password),
        special: /[\W_]/.test(password)
    };

    const rulesContainer = document.getElementById("passwordRulesContainer");

    if(password ===""){
        document.getElementById("passwordError").textContent = "Password is required.";
        isValid = false;

    }else if(!passwordRules.length || !passwordRules.uppercase || !passwordRules.lowercase || !passwordRules.digit || !passwordRules.special){
        rulesContainer.style.display = "block";
        isValid = false;

    }

    // Confirm password check
    if (password !== confirmPassword) {
        document.getElementById("cpError").textContent = "Passwords do not match.";
        isValid = false;
    }

    // Final check
    if (isValid) {


        var formData = new FormData();
        formData.append("email", email);
        formData.append("nic", nic);

        fetch("index.php?action=validatedetails", {
            method: "POST",
            body: formData,
        })
            .then(response => response.json())
            .then(resp => {
                if (resp.success) {
                    document.querySelector(".box-2").classList.remove("d-none");
                } else {
                    showAlert("Error", resp.message, "error");

                }
            })
            .catch(error => {
                console.error("Error:", error);
                showAlert("Error", "An error occurred. Please try again.", "error");

            });
    }
}

function register() {

    var fname = document.getElementById("firstName").value.trim();
    var lname = document.getElementById("lastName").value.trim();
    var address = document.getElementById("address").value.trim();
    var phone = document.getElementById("phone").value.trim();
    var email = document.getElementById("email").value.trim();
    var nic = document.getElementById("nic").value.trim();
    var role = document.querySelector('input[name="role"]:checked').value.trim();
    var password = document.getElementById("password").value.trim();
    var key = document.getElementById("enrollmentKey").value.trim();

    let isValid = true;

    if (key === "") {
        document.getElementById("enrollmentKeyError").textContent = "Enrollment key required.";
        isValid = false;
    }

    if (isValid) {

    const button1 = document.getElementById("btn1");
    const spinner = document.getElementById("spinner");

    // Show spinner, hide icon
    if (button1) button1.classList.add("d-none");
    if (spinner) spinner.classList.remove("d-none");

    var formData = new FormData();
    formData.append("fname", fname);
    formData.append("lname", lname);
    formData.append("address", address);
    formData.append("phone", phone);
    formData.append("email", email);
    formData.append("nic", nic);
    formData.append("role", role);
    formData.append("password", password);
    formData.append("key", key);

    fetch("index.php?action=register", {
        method: "POST",
        body: formData,
    })
        .then(response => response.json())
        .then(resp => {
            if (resp.success) {
                showAlert("Success", resp.message, "success").then(() => {
                    location.href = "index.php";
                });

            } else {
                showAlert("Error", resp.message, "error").then(()=>{
                    if (button1) button1.classList.remove("d-none");
                    if (spinner) spinner.classList.add("d-none");
                });

            }
        })
        .catch(error => {
            console.error("Error:", error);
            showAlert("Error", "An error occurred. Please try again.", "error");

        });
    }
}


function resetButtonUI(button, btnText, spinner) {
    if (btnText) btnText.classList.remove("d-none");
    if (spinner) spinner.classList.add("d-none");
    if (button) button.disabled = false;
}
