
function showAlert(title, message, type) {
    return Swal.fire({
        title: title,
        text: message,
        icon: type, // 'success', 'error', 'warning', 'info', 'question'
        confirmButtonText: 'OK'
    });
}

function staffLogin() {
    var staffid = document.getElementById('staffid').value.trim();
    var password = document.getElementById('password').value.trim();
    var staffidError = document.getElementById('staffidError');
    var passwordError = document.getElementById('passwordError');
    let rememberMe = document.getElementById("rememberme").checked ? "1" : "0";

    staffidError.innerText = '';
    passwordError.innerText = '';

    if (staffid === '') {
        staffidError.innerText = 'Staff ID is required.';
        return; // Prevent request
    } 
    if (password === '') {
        passwordError.innerText = 'Password is required.';
        return; // Prevent request
    }

    let formData = new FormData();
    formData.append("staffid", staffid);
    formData.append("password", password);
    formData.append("rememberme", rememberMe);

    fetch("index.php?action=loginProcess", {
        method: "POST",
        body: formData,
    })
    .then(response => response.json())
    .then(resp => {
        if (resp.success) {
            window.location.href = "index.php?action=dashboard";
        } else {
            showAlert("Error", resp.message, "error");
        }
    })
    .catch(error => {
        showAlert("Error", "Error fetching user data: " + error, "error");
    });
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

    //validate email

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
