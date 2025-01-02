

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
    var nic = document.getElementById("NIC").value;
    var username = document.getElementById("username").value;
    var email = document.getElementById("email").value;
    var phone = document.getElementById("phoneNumber").value;
    var address = document.getElementById("address").value;

    //validate details

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
            console.error("Error fetching user data:", error);
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
    
    var name = document.getElementById("name").value;
    var email = document.getElementById("emailadd").value;
    var subject = document.getElementById("subject").value;
    var message = document.getElementById("message").value;

    var emailPattern = /^[^\s@]+@[^\s@]+\.[^\s@]+$/; // Simple email format check

    if (name === "") {
        document.getElementById("error").innerText = "Please enter your name";
    } else
    if (!emailPattern.test(email)) {
        document.getElementById("error").innerText = "Please enter a valid email address";
    } else if (subject === "") {
        document.getElementById("error").innerText = "Please enter a subject";
    } else if (message === "") {
        document.getElementById("error").innerText = "Please enter your message";
    } else {
        document.getElementById("error").innerText = ""; // Clear error messages

        // Prepare form data for sending
        var formData = new FormData();
        formData.append("name", name);
        formData.append("email", email);
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
                    alert("Mail sent");
                   
                } else {
                    alert("Failed to load user data. Please try again.");
                }
            })
            .catch(error => {
                console.error("Error fetching user data:", error);
            });
    }
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


