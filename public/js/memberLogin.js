function showAlert(title, message, type) {
    return Swal.fire({
        title: title,
        text: message,
        icon: type, // 'success', 'error', 'warning', 'info', 'question'
        confirmButtonText: 'OK'
    });
}

function login() {

    // Get the values entered by the user
    let memberid = document.getElementById("memberid").value.trim();
    let memberpw = document.getElementById("memberpw").value.trim();
    let rememberMe = document.getElementById("rememberme").checked ? "1" : "0"; 

    // Regular expression pattern for validating Member ID format (e.g., M-123456)
    const memberidPattern = /^M-\d{6}$/;

    // Validate fields
    if (memberid === "") {
        document.getElementById("memberidError").textContent = "Member ID Required";
        return false;
    } else if (!memberid.match(memberidPattern)) {
        document.getElementById("memberidError").textContent = "Please enter a Valid Member ID (e.g., M-123456)";
        return false;
    } else if (memberpw === "") {
        document.getElementById("memberidError").textContent = "";
        document.getElementById("passwordError").textContent = "Password is Required.";
        return false;
    } else {
        // Clear error messages if inputs are valid
        document.getElementById("passwordError").textContent = "";
        document.getElementById("memberidError").textContent = "";

        let formData = new FormData();
        formData.append("memberid", memberid);
        formData.append("memberpw", memberpw);
        formData.append("rememberme", rememberMe);

        // Send the login request to the server using fetch API
        fetch("index.php?action=memberlogin", {
            method: "POST",
            body: formData,
        })
            .then(response => response.json())
            .then(resp => {

                if (resp.success) {
                    window.location.href = "index.php?action=dashboard";

                }else if(resp.message == "deactivated"){
                    window.location.href = "index.php?action=deactivated";

                }else if(resp.message == "expired"){
                    window.location.href = "index.php?action=expired";

                }else{
                    showAlert("Error", resp.message, "error");
                }
            })
            .catch(error => {
                // Handle any errors that occur during the fetch request
                showAlert("Error", "Error fetching user data: " + error, "error");
            });
    }
}

function forgotpw() {

    var email = document.getElementById("email").value;
    var response = document.getElementById("responseMessage");

    if (email === "") {
        response.innerHTML = "Please enter the email";
    }else if(!/^\S+@\S+\.\S+$/.test(email)){
        response.innerHTML = "Invalid email address";
    } else {

        const button = document.getElementById("btn");
        const btnText = document.getElementById("btnText");
        const spinner = document.getElementById("spinner");
    
        // Show spinner, hide icon
        if (btnText) btnText.classList.add("d-none");
        if (spinner) spinner.classList.remove("d-none");
    
        // Prevent multiple clicks
        button.disabled = true;
        
        var formData = new FormData();
        formData.append("email", email);


        fetch("index.php?action=forgotpassword", {
            method: "POST",
            body: formData,
        })
            .then(response => response.json())
            .then(resp => {
                if (resp.success) {
                    showAlert("Info", resp.message, "info").then(() => {
                        location.reload();
                    });
                } else {
                    response.innerHTML = resp.message;
                    resetButtonUI(button, btnText, spinner);
                }
            })
            .catch(error => {
                showAlert("Error", "Error fetching user data: " + error, "error");
            });

    }

};

function resetpassword() {
    var password = document.getElementById("pw").value.trim();
    var confirmPassword = document.getElementById("cpw").value.trim();
    var pwError = document.getElementById('pwError');
    var cpwError = document.getElementById('cpwError');

    pwError.innerText = '';
    cpwError.innerText = '';

    const rules = {
        length: password.length >= 8,
        uppercase: /[A-Z]/.test(password),
        lowercase: /[a-z]/.test(password),
        digit: /[0-9]/.test(password),
        special: /[\W_]/.test(password)
    };
    const rulesContainer = document.getElementById("passwordRulesContainer");

    // Validate password presence
    if (password === "") {
        pwError.textContent = "Password is required.";
        return;
    } 
    else if (!rules.length || !rules.uppercase || !rules.lowercase || !rules.digit || !rules.special) {
        rulesContainer.style.display = "block";
        return;
    }else if (confirmPassword === "") {
        rulesContainer.style.display = "none";
        cpwError.textContent = "Confirm password is required.";
        return;
    } else if (confirmPassword !== password) {
        cpwError.textContent = "Passwords do not match.";
        return;
    }

    // Proceed to send request if all validations pass
    var vcode = document.getElementById("vcode").value.trim();
    var id = document.getElementById("id").value.trim();

    var formData = new FormData();
    formData.append("password", password);
    formData.append("vcode", vcode);
    formData.append("id", id);

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

document.addEventListener("DOMContentLoaded", () => {
    const passwordInput = document.getElementById("pw");
    const cpasswordInput = document.getElementById("cpw");
    const rulesContainer = document.getElementById("passwordRulesContainer");

    // Only continue if both elements exist
    if (!passwordInput || !rulesContainer) return;

    passwordInput.addEventListener("focus", () => {
        rulesContainer.style.display = "block";
    });

    cpasswordInput.addEventListener("focus", () => {
        rulesContainer.style.display = "none";
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

function resetButtonUI(button, icon, spinner) {
    if (icon) icon.classList.remove("d-none");
    if (spinner) spinner.classList.add("d-none");
    if (button) button.disabled = false;
}