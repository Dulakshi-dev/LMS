
function showAlert(title, message, type) {
    return Swal.fire({
        title: title,
        text: message,
        icon: type, // 'success', 'error', 'warning', 'info', 'question'
        confirmButtonText: 'OK'
    });
}

function loadUsers(page = 1, status = "Active") {
    var userid = document.getElementById("memberId").value.trim();
    var nic = document.getElementById("nic").value.trim();
    var name = document.getElementById("userName").value.trim();

    let formData = new FormData();
    formData.append("page", page);
    formData.append("memberid", userid);
    formData.append("nic", nic);
    formData.append("username", name);
    formData.append("status", status);

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
                    let actionButtons = "";

                    if (status === "Active") {
                        // Show Edit, Email, and Deactivate buttons for active users
                        actionButtons = `
                            <button class="btn btn-success my-1 btn-sm edit-user" data-userid="${user.staff_id}">
                                <i class="fa fa-edit"></i>
                            </button>
                            <button class="btn btn-warning my-1 btn-sm send-mail" data-userid="${user.staff_id}">
                                <i class="fa fa-envelope"></i>
                            </button>
                           
                            <button class="btn btn-danger my-1 btn-sm deactivate" 
                                data-userid="${user.id}"
                                data-name="${user.fname} ${user.lname}"
                                data-email="${user.email}">
                                <i class="fa fa-trash icon"></i>
                                <span class="spinner-border spinner-border-sm d-none spinner" role="status"></span>
                            </button>
                        `;
                    } else {
                        // Show Activate button for deactivated users
                        actionButtons = `
                            
                            <button class="btn btn-success my-1 btn-sm activate" 
                                data-userid="${user.id}"
                                data-name="${user.fname} ${user.lname}"
                                data-email="${user.email}">
                                <i class="fa fa-plus icon"></i>
                                <span class="spinner-border spinner-border-sm d-none spinner" role="status"></span>
                            </button>
                        `;
                    }

                    let row = `
                        <tr>
                            <td>${user.staff_id}</td>
                            <td>${user.nic}</td>
                            <td>${user.fname} ${user.lname}</td>
                            <td>${user.address}</td>
                            <td>${user.mobile}</td>
                            <td>${user.email}</td>
                            <td>
                                <div class="action-buttons m-1">
                                    ${actionButtons}
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
            if (!button) return;

            // Extract icon and spinner elements inside the clicked button
            var icon = button.querySelector(".icon");
            var spinner = button.querySelector(".spinner");

            // Show spinner, hide icon
            if (icon) icon.classList.add("d-none");
            if (spinner) spinner.classList.remove("d-none");

            // Prevent multiple clicks
            button.disabled = true;

            deactivateUser(button.dataset.userid, button.dataset.name, button.dataset.email,button, icon, spinner);
        }
    });

    document.body.addEventListener("click", function (event) {
        if (event.target.closest(".activate")) {
            let button = event.target.closest(".activate");
            if (!button) return;

            // Extract icon and spinner elements inside the clicked button
            var icon = button.querySelector(".icon");
            var spinner = button.querySelector(".spinner");

            // Show spinner, hide icon
            if (icon) icon.classList.add("d-none");
            if (spinner) spinner.classList.remove("d-none");

            // Prevent multiple clicks
            button.disabled = true;
            activateUser(button.dataset.userid, button.dataset.name, button.dataset.email, button, icon, spinner);


        }
    });
});

function generateActiveStaffReport() {
    const table = document.getElementById("staffTable");
    const clonedTable = table.cloneNode(true);

    // Remove all action buttons
    clonedTable.querySelectorAll(".action-buttons").forEach(el => el.remove());

    // Remove the last column (assuming Actions is the last column)
    const headerRow = clonedTable.querySelector("thead tr");
    const totalColumns = headerRow.children.length;

    // Remove last <th>
    headerRow.deleteCell(totalColumns - 1);

    // Remove last <td> from each row
    clonedTable.querySelectorAll("tbody tr").forEach(row => {
        if (row.children.length === totalColumns) {
            row.deleteCell(totalColumns - 1);
        }
    });

    // Continue with report generation
    const tableHTML = clonedTable.outerHTML;
    let formData = new FormData();
    formData.append("table_html", tableHTML);
    formData.append("title", "Active Staff Report");
    formData.append("filename", "active_staff_report.pdf");

    fetch("index.php?action=generatereport", {
        method: "POST",
        body: formData
    })
        .then(response => response.blob())
        .then(blob => {
            const url = window.URL.createObjectURL(blob);
            window.open(url);
        });
}

function generateDeactiveStaffReport() {
    const table = document.getElementById("staffTable");
    const clonedTable = table.cloneNode(true);

    // Remove all action buttons
    clonedTable.querySelectorAll(".action-buttons").forEach(el => el.remove());

    // Remove the last column (assuming Actions is the last column)
    const headerRow = clonedTable.querySelector("thead tr");
    const totalColumns = headerRow.children.length;

    // Remove last <th>
    headerRow.deleteCell(totalColumns - 1);

    // Remove last <td> from each row
    clonedTable.querySelectorAll("tbody tr").forEach(row => {
        if (row.children.length === totalColumns) {
            row.deleteCell(totalColumns - 1);
        }
    });

    // Continue with report generation
    const tableHTML = clonedTable.outerHTML;
    let formData = new FormData();
    formData.append("table_html", tableHTML);
    formData.append("title", "Deactive Staff Report");
    formData.append("filename", "deactive_staff_report.pdf");

    fetch("index.php?action=generatereport", {
        method: "POST",
        body: formData
    })
        .then(response => response.blob())
        .then(blob => {
            const url = window.URL.createObjectURL(blob);
            window.open(url);
        });
}

function resetButtonUI(button, icon, spinner) {
    if (icon) icon.classList.remove("d-none");
    if (spinner) spinner.classList.add("d-none");
    if (button) button.disabled = false;
}

function deactivateUser(user_id,name, email , button, icon, spinner) {
    var formData = new FormData();
    formData.append("staff_id", user_id);
    formData.append("name", name);
    formData.append("email", email);

    fetch("index.php?action=deactivateuser", {
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
                resetButtonUI(button, icon, spinner);

            }
        })
        .catch(error => {
            console.error("Error fetching user data:", error);
        });

}

function loadUserDataUpdate(user_id) {

    var formData = new FormData();
    formData.append("staff_id", user_id);

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
                showAlert("Error", resp.message, "error");
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
    if (nic === "") {
        nicError.innerText = "Please enter the NIC number";
        isValid = false;
    } else if (!/^(?:\d{9}[VXvx]|\d{12})$/.test(nic)) {
        nicError.innerText = "Invalid NIC";
        isValid = false;
    }

    // Username validation
    if (username === "") {
        usernameError.innerText = "Please enter a username";
        isValid = false;
    }



    var emailPattern = /^\S+@\S+\.\S+$/;
    if (!emailPattern.test(email)) {
        emailError.innerText = "Invalid email address";
        isValid = false;
    }

    // Phone number validation (Assuming it should be 10 digits)
    var phonePattern = /^(?:\+94|0)([1-9][0-9])\d{7}$/;
    if (phone === "") {
        phoneError.innerText = "Enter a mobile number";
        isValid = false;
    } else if (!phonePattern.test(phone)) {
        phoneError.innerText = "Invalid Mobile Number";
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
    formData.append("staffId", userId);
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
                showAlert("Success", resp.message, "success").then(() => {
                    location.reload();
                });
            } else {
                showAlert("Error", resp.message, "error");
            }
        })
        .catch(error => {
            console.error("Error updating user data:", error);
        });
}


function loadMailData(user_id) {
    var formData = new FormData();
    formData.append("staff_id", user_id);

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
                showAlert("Error", resp.message, "error");
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

    const button = document.getElementById("btn");
    const btnText = document.getElementById("btnText");
    const spinner = document.getElementById("spinner");

    // Show spinner, hide icon
    if (btnText) btnText.classList.add("d-none");
    if (spinner) spinner.classList.remove("d-none");

    // Prevent multiple clicks
    button.disabled = true;

    // Prepare form data for sending
    var formData = new FormData();
    formData.append("subject", subject);
    formData.append("message", message);
    formData.append("name", name);
    formData.append("email", email);


    // Create XMLHttpRequest to send data to server
    fetch("index.php?action=sendMailStaff", {
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
                showAlert("Error", resp.message, "error").then(() => {
                    resetButtonUI(button, btnText, spinner);
                });
            }
        })
        .catch(error => {
            console.error("Error sending mail:", error);
        });
}

function activateUser(user_id, name, email, button, icon, spinner) {
    var formData = new FormData();
    formData.append("staff_id", user_id);
    formData.append("name", name);
    formData.append("email", email);

    fetch("index.php?action=activatestaff", {
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
                showAlert("Error", "Failed to activate Staff Member", "error");
                resetButtonUI(button, icon, spinner);

            }
        })
        .catch(error => {
            console.error("Error fetching user data:", error);
        });
}

function sendKey() {
    const email = document.getElementById("email").value.trim();
    const roleInput = document.querySelector('input[name="role"]:checked');

    const emailError = document.getElementById("emailError");
    const roleError = document.getElementById("roleError");

    // Clear previous errors
    emailError.textContent = "";
    roleError.textContent = "";

    let isValid = true;

    // Validate email
    if (email === "") {
        emailError.textContent = "Email is required.";
        isValid = false;
    } else if (!/^\S+@\S+\.\S+$/.test(email)) {
        emailError.textContent = "Enter a valid email address.";
        isValid = false;
    }

    // Validate role
    if (!roleInput) {
        roleError.textContent = "Select the role.";
        isValid = false;
    }

    // If not valid, stop the function
    if (!isValid) return;

   
    const button = document.getElementById("btn");
    const btnText = document.getElementById("btnText");
    const spinner = document.getElementById("spinner");

    // Show spinner, hide icon
    if (btnText) btnText.classList.add("d-none");
    if (spinner) spinner.classList.remove("d-none");

    // Prevent multiple clicks
    button.disabled = true;

    const formData = new FormData();
    formData.append("email", email);
    formData.append("role", roleInput.value); // Use .value here

    fetch("index.php?action=sendkey", {
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
                showAlert("Error", resp.message, "error").then(() => {
                    resetButtonUI(button, btnText, spinner);

                });
            }
        })
        .catch(error => {
            console.error("Error:", error);
            showAlert("Error", "An error occurred. Please try again.", "error");
        });
}
function resetButtonUI(button, btnText, spinner) {
    if (btnText) btnText.classList.remove("d-none");
    if (spinner) spinner.classList.add("d-none");
    if (button) button.disabled = false;
}



