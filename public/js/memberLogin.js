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
    let memberpw = document.getElementById("password").value.trim();
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
    } else if (password === "") {
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
    } else {
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
        var id = document.getElementById("id").value.trim();

        var formData = new FormData();
        formData.append("password", password);
        formData.append("vcode", vcode);
        formData.append("id", id);

        fetch("index.php?action=changepassword", {
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




