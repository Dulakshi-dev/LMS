function loadMembers(page = 1) {

    var memberid = document.getElementById("memberId").value.trim();
    var nic = document.getElementById("nic").value.trim();
    var name = document.getElementById("userName").value.trim();

    let formData = new FormData();
    formData.append("page", page);
    formData.append("memberid", memberid);
    formData.append("nic", nic);
    formData.append("username", name);

    fetch("index.php?action=loadmembers", {
        method: "POST",
        body: formData,
    })
        .then(response => response.json())
        .then(resp => {
            let tableBody = document.getElementById("memberTableBody");
            tableBody.innerHTML = "";

            if (resp.success && resp.members.length > 0) {
                resp.members.forEach(member => {
                    let row = `
                    <tr>
                        <td>${member.member_id}</td>
                        <td>${member.nic}</td>
                        <td>${member.fname} ${member.lname}</td>
                        <td>${member.address}</td>
                        <td>${member.mobile}</td>
                        <td>${member.email}</td>
                        <td>
                        <div class="m-1">

                            <button class="btn btn-success my-1 btn-sm edit-member " 
                                data-memberid="${member.member_id}">
                                <i class="fa fa-edit"></i>
                            </button>

                            <button class="btn btn-warning my-1 btn-sm send-mail" 
                                data-memberid="${member.member_id}">
                                <i class="fa fa-envelope"></i>
                            </button>

                            <button class="btn btn-danger my-1 btn-sm deactivate" 
                                data-memberid="${member.id}">
                                <i class="fa fa-trash"></i>
                            </button>
                            
                            </div>
                        </td>
                    </tr>
                `;
                    tableBody.innerHTML += row;

                });
            } else {
                tableBody.innerHTML = "<tr><td colspan='7'>No members found</td></tr>";
            }
            createPagination("pagination", resp.totalPages, page, "loadMembers");
        })
        .catch(error => {
            console.error("Error fetching member data:", error);
        });
}

document.addEventListener("DOMContentLoaded", function () {
    document.body.addEventListener("click", function (event) {
        if (event.target.closest(".edit-member")) {
            let button = event.target.closest(".edit-member");

            loadMemberDataUpdate(button.dataset.memberid);
        }
    });

    // Handle Send Email Modal
    document.body.addEventListener("click", function (event) {
        if (event.target.closest(".send-mail")) {
            let button = event.target.closest(".send-mail");
            loadMailData(button.dataset.memberid);
        }
    });

    document.body.addEventListener("click", function (event) {
        if (event.target.closest(".deactivate")) {
            let button = event.target.closest(".deactivate");
            deactivateMember(button.dataset.memberid);
        }
    });
});

function loadMemberRequests(page = 1) {

    var nic = document.getElementById("nic").value.trim();
    var name = document.getElementById("userName").value.trim();

    let formData = new FormData();
    formData.append("page", page);
    formData.append("nic", nic);
    formData.append("username", name);

    fetch("index.php?action=loadmemberrequests", {
        method: "POST",
        body: formData,
    })
        .then(response => response.json())
        .then(resp => {
            let tableBody = document.getElementById("requestTableBody");
            tableBody.innerHTML = "";

            if (resp.success && resp.requests.length > 0) {
                resp.requests.forEach(request => {
                    let row = `
                    <tr>
                        <td>${request.nic}</td>
                        <td>${request.fname} ${request.lname}</td>
                        <td>${request.address}</td>
                        <td>${request.mobile}</td>
                        <td>${request.email}</td>
                        <td>
                        <div class="m-1">

                            <button class="btn btn-info my-1 btn-sm approve-request " 
                                data-id="${request.id}">
                                <i class="fa fa-check"></i>
                            </button>

                            <button class="btn btn-danger my-1 btn-sm reject" 
                                data-id="${request.id}">
                                <i class="fa fa-trash"></i>
                            </button>
                            
                            </div>
                        </td>
                    </tr>
                `;
                    tableBody.innerHTML += row;

                });
            } else {
                tableBody.innerHTML = "<tr><td colspan='7'>No members found</td></tr>";
            }
            createPagination("pagination", resp.totalPages, page, "loadMemberRequests");
        })
        .catch(error => {
            console.error("Error fetching request data:", error);
        });
}

document.addEventListener("DOMContentLoaded", function () {
    document.body.addEventListener("click", function (event) {
        if (event.target.closest(".approve-request")) {
            let button = event.target.closest(".approve-request");
            approveMembership(button.dataset.id);
            alert("Membership Request Approved")

            location.reload();
        }
    });

    document.body.addEventListener("click", function (event) {
        if (event.target.closest(".reject")) {
            let button = event.target.closest(".reject");
            rejectMemberRequest(button.dataset.id);
            alert("Membership Request Rejected")

            location.reload();

        }
    });
});


function approveMembership(id){
    var formData = new FormData();
    formData.append("id", id);

    fetch("index.php?action=approvemembership", {
        method: "POST",
        body: formData,
    })
        .then(response => response.json())
        .then(resp => {
            if (resp.success) {
                location.reload();
            } else {
                alert("Approval failed. Please try again.");
            }
        })
        .catch(error => {
            console.error("Error fetching user data:", error);
        });
}

function loadMemberDataUpdate(id) {
    // Create a FormData object and append the user ID
    var formData = new FormData();
    formData.append("member_id", id);

    // Fetch user data from the server
    fetch("index.php?action=loadMemberData", {
        method: "POST",
        body: formData,
    })
        .then(response => response.json())
        .then(resp => {
            if (resp.success) {
                let updateModal = new bootstrap.Modal(document.getElementById("updateDetailsModal"));
                updateModal.show();

                document.getElementById("userID").value = resp.member_id;
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

    // Clear previous error messages
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

    // NIC validation (Assuming NIC is 10 or 12 characters long)
    if (nic === "" || !/^\d{10}|\d{12}$/.test(nic)) {
        document.getElementById("nicError").innerText = "Invalid NIC. Must be 10 or 12 digits.";
        isValid = false;
    }

    // Username validation (Non-empty, alphabets only)
    if (username === "" || !/^[a-zA-Z\s]+$/.test(username)) {
        document.getElementById("usernameError").innerText = "Invalid name. Only letters are allowed.";
        isValid = false;
    }

    // Email validation
    if (email === "" || !/^\S+@\S+\.\S+$/.test(email)) {
        document.getElementById("emailError").innerText = "Invalid email format.";
        isValid = false;
    }

    // Phone number validation (10-digit number)
    if (phone === "" || !/^\d{10}$/.test(phone)) {
        document.getElementById("phoneError").innerText = "Invalid phone number. Must be 10 digits.";
        isValid = false;
    }

    // Address validation (Minimum 5 characters)
    if (address === "" || address.length < 5) {
        document.getElementById("addressError").innerText = "Address must be at least 5 characters.";
        isValid = false;
    }

    if (!isValid) {
        return; // Stop the function if validation fails
    }

    // If all inputs are valid, proceed with updating user details
    var formData = new FormData();
    formData.append("userId", userId);
    formData.append("name", username);
    formData.append("email", email);
    formData.append("phone", phone);
    formData.append("address", address);
    formData.append("nic", nic);

    fetch("index.php?action=updateMember", {
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


function loadMailData(member_id) {

    var formData = new FormData();
    formData.append("member_id", member_id);

    fetch("index.php?action=loadMemberMailData", {
        method: "POST",
        body: formData,
    })
        .then(response => response.json())
        .then(resp => {
            if (resp.success) {
                let updateModal = new bootstrap.Modal(document.getElementById("mailModal"));
                updateModal.show();

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

function deactivateUser(member_id) {

    var formData = new FormData();
    formData.append("id", member_id);

    fetch("index.php?action=deactivatemember", {
        method: "POST",
        body: formData,
    })
        .then(response => response.json())
        .then(resp => {
            if (resp.success) {
                alert("Member Deactivated");
            } else {
                alert("Failed to load user data. Please try again.");
            }
        })
        .catch(error => {
            console.error("Error fetching user data:", error);
        });
}

function rejectMemberRequest(id) {

    var formData = new FormData();
    formData.append("id", id);

    fetch("index.php?action=rejectmember", {
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

function sendEmail() {
    var name = document.getElementById("name").value.trim();
    var email = document.getElementById("emailadd").value.trim();
    var subject = document.getElementById("subject").value.trim();
    var message = document.getElementById("message").value.trim();

    var emailPattern = /^[^\s@]+@[^\s@]+\.[^\s@]+$/; // Simple email format check

    // Clear previous error messages
    document.getElementById("nameError").innerText = "";
    document.getElementById("emailError").innerText = "";
    document.getElementById("subjectError").innerText = "";
    document.getElementById("messageError").innerText = "";

    var isValid = true;

    // Validation checks
    if (name === "") {
        document.getElementById("nameError").innerText = "Please enter your name.";
        isValid = false;
    }

    if (email === "" || !emailPattern.test(email)) {
        document.getElementById("emailError").innerText = "Please enter a valid email address.";
        isValid = false;
    }

    if (subject === "") {
        document.getElementById("subjectError").innerText = "Please enter a subject.";
        isValid = false;
    }

    if (message === "") {
        document.getElementById("messageError").innerText = "Please enter your message.";
        isValid = false;
    }

    if (!isValid) {
        return; // Stop the function if validation fails
    }

    // If validation passes, proceed with sending data
    var formData = new FormData();
    formData.append("name", name);
    formData.append("email", email);
    formData.append("subject", subject);
    formData.append("message", message);

    fetch("index.php?action=sendMail", {
        method: "POST",
        body: formData,
    })
        .then(response => response.json())
        .then(resp => {
            if (resp.success) {
                alert("Mail sent successfully.");
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
    alert(id);

    fetch("index.php?action=changeMemberStatus", {
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


