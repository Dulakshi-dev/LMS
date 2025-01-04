function staffLogin() {
    let isValid = true;
    var username = document.getElementById('username').value.trim();
    var password = document.getElementById('password').value.trim();
    var usernameError = document.getElementById('usernameError');
    var passwordError = document.getElementById('passwordError');

    usernameError.innerText = '';
    passwordError.innerText = '';

    // Validate username
    if (username === '') {
        usernameError.innerText = 'Username is required.';
        isValid = false;
    }

    // Validate password
    if (password === '') {
        passwordError.innerText = 'Password is required.';
        isValid = false;
    } else if (password.length < 6) {
        passwordError.innerText = 'Password must be at least 8 characters.';
        isValid = false;
    }
//validate login details
    return isValid;
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
        .then(response => response.text()) 
        .then(data => {
            response.textContent = data; 
            email.value = ""; 
        })
        .catch(error => {
            response.textContent = "An error occurred. Please try again.";
            console.error("Error:", error);
        });
};

function resetpw() {
    let isValid = true;

    // Retrieve form inputs and error spans
    var password = document.getElementById("pw").value.trim();
    var confirmPassword = document.getElementById("cpw").value.trim();
    var pwError = document.getElementById('PassError'); // Corrected to match the HTML ID
    var cpwError = document.getElementById('ConError'); // Corrected to match the HTML ID

    // Clear previous error messages
    pwError.innerText = '';
    cpwError.innerText = '';

    // Validate password
    if (password === "") {
        pwError.textContent = "Password is required.";
        isValid = false;
    } else if (password.length < 8) {
        pwError.textContent = "Password must be at least 8 characters.";
        isValid = false;
    }

    // Validate confirm password
    if (confirmPassword === "") {
        cpwError.textContent = "Confirm password is required.";
        isValid = false;
    } else if (confirmPassword !== password) {
        cpwError.textContent = "Passwords do not match.";
        isValid = false;
    }

    return isValid; // Prevent form submission if validation fails
}
