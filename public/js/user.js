function loadUsers(page = 1) {

    var userid = document.getElementById("memberId").value.trim();
    var nic = document.getElementById("nic").value.trim();
    var name = document.getElementById("userName").value.trim();

    let formData = new FormData();
    formData.append("page", page);
    formData.append("memberid", userid);
    formData.append("nic", nic);
    formData.append("username", name);

    fetch("index.php?action=loadusers", {
        method: "POST",
        body: formData,
    })
        .then(response => response.json())
        .then(resp => {
            let tableBody = document.getElementById("userTableBody");
            tableBody.innerHTML = "";

            if (resp.success && resp.users.length > 0) {
                resp.users.forEach(user => {
                    let row = `
                    <tr>
                        <td>${user.user_id}</td>
                        <td>${user.nic}</td>
                        <td>${user.fname} ${user.lname}</td>
                        <td>${user.address}</td>
                        <td>${user.mobile}</td>
                        <td>${user.email}</td>
                        <td>
                        <div class="m-1">

                            <button class="btn btn-success my-1 btn-sm edit-user " 
                                data-userid="${user.user_id}">
                                <i class="fa fa-edit"></i>
                            </button>

                            <button class="btn btn-warning my-1 btn-sm send-mail" 
                                data-userid="${user.user_id}">
                                <i class="fa fa-envelope"></i>
                            </button>

                            <button class="btn btn-danger my-1 btn-sm deactivate" 
                                data-userid="${user.id}">
                                <i class="fa fa-trash"></i>
                            </button>
                            
                            </div>
                        </td>
                    </tr>
                `;
                    tableBody.innerHTML += row;

                });
            } else {
                tableBody.innerHTML = "<tr><td colspan='7'>No users found</td></tr>";
            }
            createPagination("pagination", resp.totalPages, page, "loadUsers");
        })
        .catch(error => {
            console.error("Error fetching user data:", error);
        });
}

document.addEventListener("DOMContentLoaded", function () {
    document.body.addEventListener("click", function (event) {
        if (event.target.closest(".edit-user")) {
            let button = event.target.closest(".edit-user");

            loadUserDataUpdate(button.dataset.userid);
        }
    });

    // Handle Send Email Modal
    document.body.addEventListener("click", function (event) {
        if (event.target.closest(".send-mail")) {
            let button = event.target.closest(".send-mail");
            loadMailData(button.dataset.userid);
        }
    });

    document.body.addEventListener("click", function (event) {
        if (event.target.closest(".deactivate")) {
            let button = event.target.closest(".deactivate");
            deactivateUser(button.dataset.userid);


        }
    });
});

function deactivateUser(user_id) {
    var formData = new FormData();
    formData.append("user_id", user_id);

    fetch("index.php?action=deactivateuser", {
        method: "POST",
        body: formData,
    })
        .then(response => response.json())
        .then(resp => {
            if (resp.success) {
                alert("Staff member Deactivated");
            } else {
                alert("Failed to load user data. Please try again.");
            }
        })
        .catch(error => {
            console.error("Error fetching user data:", error);
        });

}
function loadUserDataUpdate(user_id) {

    var formData = new FormData();
    formData.append("user_id", user_id);

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

                let updateModal = new bootstrap.Modal(document.getElementById("updateDetailsModal"));
                updateModal.show();
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
    var emailPattern = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
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
                let mailModal = new bootstrap.Modal(document.getElementById("mailModal"));
                mailModal.show();

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

