function showAlert(title, message, type) {
    return Swal.fire({
        title: title,
        text: message,
        icon: type, // 'success', 'error', 'warning', 'info', 'question'
        confirmButtonText: 'OK'
    });
}

function loadMembers(page = 1, status = "Active") {

    var memberid = document.getElementById("memberId").value.trim();
    var nic = document.getElementById("nic").value.trim();
    var name = document.getElementById("userName").value.trim();

    let formData = new FormData();
    formData.append("page", page);
    formData.append("memberid", memberid);
    formData.append("nic", nic);
    formData.append("username", name);
    formData.append("status", status);

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
                    let actionButtons = "";

                    if (status === "Active") {
                        // Show Edit, Email, and Deactivate buttons for active users
                        actionButtons = `
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
                        `;
                    } else {
                        // Show Activate button for deactivated users
                        actionButtons = `
                            <button class="btn btn-success my-1 btn-sm activate" data-memberid="${member.id}">
                                <i class="fa fa-plus"></i>
                            </button>

                        `;
                    }

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
                        ${actionButtons}
                    
                            
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

function loadMemberRequests(page = 1, status = "Pending") {

    var nic = document.getElementById("nic").value.trim();
    var name = document.getElementById("userName").value.trim();

    let formData = new FormData();
    formData.append("page", page);
    formData.append("nic", nic);
    formData.append("username", name);
    formData.append("status", status);

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

                    let actionButtons = "";

                    if (status === "Pending") {
                        // Show Edit, Email, and Deactivate buttons for active users
                        actionButtons = `
                             <button class="btn btn-info my-1 btn-sm approve-request " 
                                data-id="${request.id}">
                                <i class="fa fa-check"></i>
                            </button>

                            <button class="btn btn-danger my-1 btn-sm reject" 
                                data-id="${request.id}">
                                <i class="fa fa-trash"></i>
                            </button>
                        `;
                    } else {
                        // Show Activate button for deactivated users
                        actionButtons = `
                            <button class="btn btn-success my-1 btn-sm activate-request" data-id="${request.id}">
                                <i class="fa fa-plus"></i>
                            </button>

                        `;
                    }
                    let row = `
                    <tr>
                        <td>${request.nic}</td>
                        <td>${request.fname} ${request.lname}</td>
                        <td>${request.address}</td>
                        <td>${request.mobile}</td>
                        <td>${request.email}</td>
                        <td>
                        <div class="m-1">
                        ${actionButtons}
                           
                    
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

    document.body.addEventListener("click", function (event) {
        if (event.target.closest(".activate")) {
            let button = event.target.closest(".activate");
            activateMember(button.dataset.memberid);


        }
    });

    document.body.addEventListener("click", function (event) {
        if (event.target.closest(".approve-request")) {
            let button = event.target.closest(".approve-request");
            approveMembership(button.dataset.id);
            
        }
    });

    document.body.addEventListener("click", function (event) {
        if (event.target.closest(".reject")) {
            let button = event.target.closest(".reject");
            rejectMemberRequest(button.dataset.id);
        
        }
    });

    document.body.addEventListener("click", function (event) {
        if (event.target.closest(".activate-request")) {
            let button = event.target.closest(".activate-request");
            activateRequest(button.dataset.id);

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
                showAlert("Success", resp.message, "success").then(() => {
                    location.reload();
                });
            } else {
                showAlert("Error", resp.message, "error");
            }
        })
        .catch(error => {
            showAlert("Error", "Something went wrong", "error");
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
                showAlert("Error", resp.message, "error");
            }
        })
        .catch(error => {
            showAlert("Error", "Something went wrong", "error");
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
    if (nic === "") {
        document.getElementById("nicError").innerText = "Enter the NIC number";
        isValid = false;
    }else  if (!/^(?:\d{9}[VXvx]|\d{12})$/.test(nic)) {
        document.getElementById("nicError").innerText = "Invalid NIC.";
        isValid = false;
    }

    // Username validation (Non-empty, alphabets only)
    if (username === "") {
        document.getElementById("usernameError").innerText = "Enter the user name.";
        isValid = false;
    }else if(!/^[a-zA-Z\s]+$/.test(username)) {
        document.getElementById("usernameError").innerText = "Invalid name.";
        isValid = false;
    }

    // Email validation
    if (email === "") {
        document.getElementById("emailError").innerText = "Enter the email.";
        isValid = false;
    }else if (!/^\S+@\S+\.\S+$/.test(email)) {
        document.getElementById("emailError").innerText = "Invalid email address.";
        isValid = false;
    }

    // Phone number validation (10-digit number)
    if (phone === "") {
        document.getElementById("phoneError").innerText = "Enter the mobile number.";
        isValid = false;
    }else if (!/^(?:\+94|0)([1-9][0-9])\d{7}$/.test(phone)) {
        document.getElementById("phoneError").innerText = "Invalid mibile number.";
        isValid = false;
    }

    // Address validation (Minimum 5 characters)
    if (address === "") {
        document.getElementById("addressError").innerText = "Enter the address.";
        isValid = false;
    }else if (address.length < 5) {
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
                showAlert("Success", resp.message, "success").then(() => {
                    location.reload();
                });
            } else {
                showAlert("Error", resp.message, "error");

            }
        })
        .catch(error => {
            showAlert("Error", "Something went wrong", "error");
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
                showAlert("Error", resp.message, "error");

            }
        })
        .catch(error => {
            showAlert("Error", "Something went wrong", "error");
            console.error("Error fetching user data:", error);
        });
}

function deactivateMember(member_id) {

    var formData = new FormData();
    formData.append("id", member_id);

    fetch("index.php?action=deactivatemember", {
        method: "POST",
        body: formData,
    })
        .then(response => response.json())
        .then(resp => {
            if (resp.success) {
                showAlert("Success", resp.message, "success").then(() => {
                    location.reload();
                });
            } else {
                showAlert("Error", resp.message, "error");
            }
        })
        .catch(error => {
            showAlert("Error", "Something went wrong", "error");
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
                showAlert("Success", resp.message, "success").then(() => {
                    location.reload();
                });
            } else {
                showAlert("Error", resp.message, "error");

            }
        })
        .catch(error => {
            showAlert("Error", "Something went wrong", "error");
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

    fetch("index.php?action=sendMailMember", {
        method: "POST",
        body: formData,
    })
        .then(response => response.json())
        .then(resp => {
            if (resp.success) {
                showAlert("Success", resp.message, "success").then(() => {
                    location.reload();
                });
            } else {
                showAlert("Error", resp.message, "error");

            }
        })
        .catch(error => {
            showAlert("Error", "Something went wrong", "error");
            console.error("Error sending mail:", error);
        });
}

function activateMember(memberid) {
    var formData = new FormData();
    formData.append("memberid", memberid);

    fetch("index.php?action=activatemember", {
        method: "POST",
        body: formData,
    })
        .then(response => response.json())
        .then(resp => {
            if (resp.success) {
                showAlert("Success", resp.message, "success").then(() => {
                    location.reload();
                });
            } else {
                showAlert("Error", "Failed to activate Member", "error");

            }
        })
        .catch(error => {
            console.error("Error fetching user data:", error);
        });
}

function activateRequest(id) {
    alert(id);
    var formData = new FormData();
    formData.append("id", id);

    fetch("index.php?action=activaterequest", {
        method: "POST",
        body: formData,
    })
        .then(response => response.json())
        .then(resp => {
            if (resp.success) {
                showAlert("Success", resp.message, "success").then(() => {
                    location.reload();
                });
            } else {
                showAlert("Error", "Failed to Accept Request", "Error");

            }
        })
        .catch(error => {
            console.error("Error fetching user data:", error);
        });
}

