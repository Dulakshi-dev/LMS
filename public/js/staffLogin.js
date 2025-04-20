
function showAlert(title, message, type) {
    return Swal.fire({
        title: title,
        text: message,
        icon: type, // 'success', 'error', 'warning', 'info', 'question'
        confirmButtonText: 'OK'
    });
}

function staffLogin() {
    const staffidPattern = /^S-\d{6}$/;

    // Get input values and trim any whitespace
    var staffid = document.getElementById('staffid').value.trim();
    var password = document.getElementById('password').value.trim();

    // Get error message elements
    var staffidError = document.getElementById('staffidError');
    var passwordError = document.getElementById('passwordError');

    // Check if "Remember Me" is checked, store "1" for checked and "0" for unchecked
    let rememberMe = document.getElementById("rememberme").checked ? "1" : "0";

    // Clear previous error messages
    staffidError.innerText = '';
    passwordError.innerText = '';

    // Validate fields
    if (staffid === '') {
        staffidError.innerText = 'Staff ID is required.';
        return;
    } else if(!staffid.match(staffidPattern)){
        staffidError.innerText = 'Invalid Staff ID.';
        return;
    } else if (password === '') {
        passwordError.innerText = 'Password is required.';
        return; 
    } else {
        // Create a FormData object to send data via POST
        let formData = new FormData();
        formData.append("staffid", staffid);
        formData.append("password", password);
        formData.append("rememberme", rememberMe);

        // Send a POST request to process the login
        fetch("index.php?action=loginProcess", {
            method: "POST",
            body: formData,
        })
        .then(response => response.json()) // Parse response as JSON
        .then(resp => {
            if (resp.success) {
                // If login is successful, redirect to the dashboard
                window.location.href = "index.php?action=dashboard";
            } else {
                showAlert("Error", resp.message, "error");
                // Optional: Uncheck remember me on error
                document.getElementById("rememberme").checked = false;
            }
        })
        .catch(error => {
            showAlert("Error", "Error fetching user data: " + error, "error");
        });
    }
}


function staffRegistration() {
    //validate all the fiels and formats
    var fname = document.getElementById('firstName').value.trim();
    var lname = document.getElementById('lastName').value.trim();
    var address = document.getElementById('address').value.trim();
    var phone = document.getElementById('phone').value.trim();
    var email = document.getElementById('email').value.trim();
    var nic = document.getElementById('nic').value.trim();
    var role = document.querySelector('input[name="role"]:checked');


    if (username === '') {
        errorMsg.innerText = 'Username is required.';
        errorMsgDiv.style.display = 'block';
        return false;
    } else if (password === '') {
        errorMsg.innerText = 'Password is required.';
        errorMsgDiv.style.display = 'block';
        return false;
    }

    //validate details

    return true;
}


function forgotpw() {

    var email = document.getElementById("email").value;
    var response = document.getElementById("responseMessage");

    if (email === "") {
        response.innerHTML = "Please enter the email";
    }else if(!/^\S+@\S+\.\S+$/.test(email)){
        response.innerHTML = "Invalid email address";
    }else{
        var formData = new FormData();
        formData.append("email", email);
    
        fetch("index.php?action=forgotpassword", {
            method: "POST",
            body: formData,
        })
            .then(response => response.json())
            .then(resp => {
                if (resp.success) {
                    showAlert("Info", resp.message, "info");
                } else {
                    response.innerHTML = resp.message;
                }
            })
            .catch(error => {
                showAlert("Error", "Error fetching user data: " + error, "error");
            });
    }
    

};

function resetpassword() {

    // Retrieve form inputs and error spans
    var password = document.getElementById("pw").value.trim();
    var confirmPassword = document.getElementById("cpw").value.trim();
    var pwError = document.getElementById('pwError'); // Corrected to match the HTML ID
    var cpwError = document.getElementById('cpwError'); // Corrected to match the HTML ID

    // Clear previous error messages
    pwError.innerText = '';
    cpwError.innerText = '';

    // Validate password
    if (password === "") {
        pwError.textContent = "Password is required.";

    } else if (password.length < 8) {
        pwError.textContent = "Password must be at least 8 characters.";

    } else if (confirmPassword === "") {
        cpwError.textContent = "Confirm password is required.";

    } else if (confirmPassword !== password) {
        cpwError.textContent = "Passwords do not match.";

    } else {
        var vcode = document.getElementById("vcode").value.trim();

        var formData = new FormData();
        formData.append("password", password);
        formData.append("vcode", vcode);

        fetch("index.php?action=resetpassword", {
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
                showAlert("Error", "Error fetching user data: " + error, "error");
            });

    }
}

document.addEventListener("DOMContentLoaded", () => {
    const passwordInput = document.getElementById("pw");
    const rulesContainer = document.getElementById("passwordRulesContainer");

    // Only continue if both elements exist
    if (!passwordInput || !rulesContainer) return;

    passwordInput.addEventListener("focus", () => {
        rulesContainer.style.display = "block";
    });

    passwordInput.addEventListener("blur", () => {
        setTimeout(() => {
            rulesContainer.style.display = "none";
        }, 200);
    });

    passwordInput.addEventListener("input", () => {
        const password = passwordInput.value;

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
});

function updateRule(id, isValid) {
    const element = document.getElementById(id);
    if (!element) return;

    element.classList.toggle("text-danger", !isValid);
    element.classList.toggle("text-success", isValid);
}
