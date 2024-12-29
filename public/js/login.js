function staffLogin() {
    var username = document.getElementById('username').value.trim();
    var password = document.getElementById('password').value.trim();
    var errorMsgDiv = document.getElementById('errormsgdiv');
    var errorMsg = document.getElementById('errormsg');

    errorMsg.innerText = '';
    errorMsgDiv.style.display = 'none';

    if (username === '') {
        errorMsg.innerText = 'Username is required.';
        errorMsgDiv.style.display = 'block';
        return false;
    } else if (password === '') {
        errorMsg.innerText = 'Password is required.';
        errorMsgDiv.style.display = 'block';
        return false;
    }

    return true;
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

    return true;
}


function ff() {

    var email = document.getElementById("email");
    var response = document.getElementById("responseMessage");

    // Prepare form data
    var formData = new FormData();
    formData.append("email", email.value);

    // Send AJAX request
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

