

function loadUserDataUpdate(user_id) {
    
    // Create a FormData object and append the user ID
    var formData = new FormData();
    formData.append("user_id", user_id);

    // Fetch user data from the server
    fetch("index.php?action=loadUserData", {
        method: "POST",
        body: formData,
    })
        .then(response => response.json()) 
        .then(resp => {
            if (resp.success) {
                document.getElementById("userID").value = resp.user_id;
                document.getElementById("NIC").value = resp.nic;
                document.getElementById("username").value = resp.fname + " " + resp.lname;
                document.getElementById("email").value = resp.email;
                document.getElementById("phoneNumber").value = resp.mobile;
                document.getElementById("address").value = resp.address;
            } else {
                alert("Failed to load user data. Please try again.");
            }
        })
        .catch(error => {
            console.error("Error fetching user data:", error);
        });
}

function updateUserDetails() {
    var userId = document.getElementById("userID").value;
    var nic = document.getElementById("NIC").value.trim();
    var username = document.getElementById("username").value.trim();
    var email = document.getElementById("email").value.trim();
    var phone = document.getElementById("phoneNumber").value.trim();
    var address = document.getElementById("address").value.trim();

    // Error elements
    var nicError = document.getElementById("nicError");
    var usernameError = document.getElementById("usernameError");
    var emailError = document.getElementById("emailError");
    var phoneError = document.getElementById("phoneError");
    var addressError = document.getElementById("addressError");

    // Clear previous error messages
    nicError.innerText = "";
    usernameError.innerText = "";
    emailError.innerText = "";
    phoneError.innerText = "";
    addressError.innerText = "";

    var isValid = true;

    // NIC Validation (Assuming it should be 10 or 12 characters)
    if (nic === "" || !(nic.length === 10 || nic.length === 12)) {
        nicError.innerText = "NIC must be 10 or 12 characters long";
        isValid = false;
    }

    // Username validation
    if (username === "") {
        usernameError.innerText = "Please enter a username";
        isValid = false;
    }

    // Email validation
    var emailPattern = /^[^\s@]+@[^\s@]+\.[^\s@]+$/; // Basic email format check
    if (!emailPattern.test(email)) {
        emailError.innerText = "Please enter a valid email address";
        isValid = false;
    }

    // Phone number validation (Assuming it should be 10 digits)
    var phonePattern = /^\d{10}$/;
    if (!phonePattern.test(phone)) {
        phoneError.innerText = "Phone number must be 10 digits";
        isValid = false;
    }

    // Address validation
    if (address === "") {
        addressError.innerText = "Please enter an address";
        isValid = false;
    }

    if (!isValid) {
        return; // Stop execution if validation fails
    }

    // Prepare form data for sending
    var formData = new FormData();
    formData.append("userId", userId);
    formData.append("username", username);
    formData.append("email", email);
    formData.append("phone", phone);
    formData.append("address", address);
    formData.append("nic", nic);

    fetch("index.php?action=updateUser", {
        method: "POST",
        body: formData,
    })
    .then(response => response.json()) 
    .then(resp => {
        if (resp.success) {
            location.reload();
        } else {
            alert("Failed to update user data. Please try again.");
        }
    })
    .catch(error => {
        console.error("Error updating user data:", error);
    });
}


function loadMailData(user_id) {

    var formData = new FormData();
    formData.append("user_id", user_id);

    fetch("index.php?action=loadMailData", {
        method: "POST",
        body: formData,
    })
        .then(response => response.json()) 
        .then(resp => {
            if (resp.success) {
                document.getElementById("name").value = resp.name;
                document.getElementById("emailadd").value = resp.email;
               
            } else {
                alert("Failed to load user data. Please try again.");
            }
        })
        .catch(error => {
            console.error("Error fetching user data:", error);
        });
}

function sendEmail() {
    var subject = document.getElementById("subject").value.trim();
    var message = document.getElementById("message").value.trim();
    
    var subjectError = document.getElementById("subjectError");
    var messageError = document.getElementById("messageError");

    // Clear previous error messages
    subjectError.innerText = "";
    messageError.innerText = "";

    var isValid = true;

    if (subject === "") {
        subjectError.innerText = "Please enter a subject";
        isValid = false;
    }

    if (message === "") {
        messageError.innerText = "Please enter your message";
        isValid = false;
    }

    if (!isValid) {
        return; // Stop execution if validation fails
    }

    // Prepare form data for sending
    var formData = new FormData();
    formData.append("subject", subject);
    formData.append("message", message);

    // Create XMLHttpRequest to send data to server
    fetch("index.php?action=sendMail", {
        method: "POST",
        body: formData,
    })
    .then(response => response.json()) 
    .then(resp => {
        if (resp.success) {
            alert("Mail sent successfully!");
        } else {
            alert("Failed to send mail. Please try again.");
        }
    })
    .catch(error => {
        console.error("Error sending mail:", error);
    });
}


function changeUserStatus(id) { 
    var formData = new FormData();
    formData.append("id", id);

    fetch("index.php?action=changeStatus", {
        method: "POST",
        body: formData,
    })
    .then(response => response.json())
    .then(resp => {
        if (resp.success) {
            location.reload();
        } else {
            alert("Failed to load user data. Please try again.");
        }
    })
    .catch(error => {
        console.error("Error fetching user data:", error);
    });
}


