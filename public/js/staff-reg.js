
function showAlert(title, message, type) {
    return Swal.fire({
        title: title,
        text: message,
        icon: type, // 'success', 'error', 'warning', 'info', 'question'
        confirmButtonText: 'OK'
    });
}

function submit(){
    document.querySelectorAll("span").forEach(span => span.textContent = "");

    let isValid = true;

    const firstName = document.getElementById("firstName").value.trim();
    if (firstName === "") {
        document.getElementById("firstNameError").textContent = "First Name is required.";
        isValid = false;
    }

    const lastName = document.getElementById("lastName").value.trim();
    if (lastName === "") {
        document.getElementById("lastNameError").textContent = "Last Name is required.";
        isValid = false;
    }

    const address = document.getElementById("address").value.trim();
    if (address === "") {
        document.getElementById("addressError").textContent = "Address is required.";
        isValid = false;
    }

    const phone = document.getElementById("phone").value.trim();
    if (!/^(?:\+94|0)([1-9][0-9])\d{7}$/.test(phone)) {
        document.getElementById("phoneError").textContent = "Invalid Phone Number.";
        isValid = false;
    }

    const email = document.getElementById("email").value.trim();
    if (!/^\S+@\S+\.\S+$/.test(email)) {
        document.getElementById("emailError").textContent = "Invalid email format.";
        isValid = false;
    }

    const nic = document.getElementById("nic").value.trim();
    if (!/^(?:\d{9}[VX]|\d{12})$/.test(nic)) {
        document.getElementById("nicError").textContent = "NIC must be in the correct format (9 digits + V/X or 12 digits).";
        isValid = false;
    }

    const roleSelected = document.querySelector('input[name="role"]:checked');
    if (!roleSelected) {
        document.getElementById("roleError").textContent = "Please select a role.";
        isValid = false;
    }

    if (isValid) {
        if (roleSelected) {
            document.querySelector(".box-2").style.display = "block";
        }
    }

}

function register(){
    
    var fname = document.getElementById("firstName").value.trim();
    var lname = document.getElementById("lastName").value.trim();
    var address = document.getElementById("address").value.trim();
    var phone = document.getElementById("phone").value.trim();
    var email = document.getElementById("email").value.trim();
    var nic = document.getElementById("nic").value.trim();
    var role = document.querySelector('input[name="role"]:checked').value.trim();
    var password = document.getElementById("password").value.trim();
    var key = document.getElementById("enrollmentKey").value.trim();


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
                    location.href="index.php";
                });
                
            } else {
                showAlert("Error", resp.message, "error");

            }
        })
        .catch(error => {
            console.error("Error:", error);
            showAlert("Error", "An error occurred. Please try again.", "error");

        });
}


