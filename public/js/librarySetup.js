function showAlert(title, message, type) {
    return Swal.fire({
        title: title,
        text: message,
        icon: type, // 'success', 'error', 'warning', 'info', 'question'
        confirmButtonText: 'OK'
    });
}

function saveOpeningHours() {
    if (validateTimeSetup()) {
        var weekdayfrom = document.getElementById("weekdayfrom").value.trim();
        var weekdayto = document.getElementById("weekdayto").value.trim();
        var weekendfrom = document.getElementById("weekendfrom").value.trim();
        var weekendto = document.getElementById("weekendto").value.trim();
        var holidayfrom = document.getElementById("holidayfrom").value.trim();
        var holidayto = document.getElementById("holidayto").value.trim();

        var formData = new FormData();
        formData.append("weekdayfrom", weekdayfrom);
        formData.append("weekdayto", weekdayto);
        formData.append("weekendfrom", weekendfrom);
        formData.append("weekendto", weekendto);
        formData.append("holidayfrom", holidayfrom);
        formData.append("holidayto", holidayto);


        fetch("index.php?action=changeopeninghours", {
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
                console.error("Error:", error);
                showAlert("Error", "An error occurred. Please try again.", "error");

            });
    }

}

function saveNewsUpdate() {
    if (validateNewsUpdate()) {
        // Retrieve values of form fields
        var boxSelection = document.getElementById('boxSelection').value;
        var title = document.getElementById('title').value;
        var date = document.getElementById('date').value;
        var description = document.getElementById('textareaDescription').value;
        var fileInput = document.getElementById('fileInput');
        var image = fileInput.files.length > 0 ? fileInput.files[0] : null; // Ensure file exists

        // Clear previous errors
        document.getElementById('boxSelectionError').textContent = "";
        document.getElementById('titleError').textContent = "";
        document.getElementById('textareaDescriptionError').textContent = "";
        document.getElementById('fileInputError').textContent = "";

        // Perform validation before sending data
        if (!boxSelection || !title || !date || !description || !image) {
            if (!boxSelection) document.getElementById('boxSelectionError').textContent = 'Please select a box.';
            if (!title) document.getElementById('titleError').textContent = 'Please enter a title.';
            if (!date) document.getElementById('dateError').textContent = 'Please enter a date.';
            if (!description) document.getElementById('textareaDescriptionError').textContent = 'Please enter a description.';
            if (!image) document.getElementById('fileInputError').textContent = 'Please select an image.';
            return; // Stop execution if any field is empty
        }

        var formData = new FormData();
        formData.append('boxSelection', boxSelection);
        formData.append('title', title);
        formData.append('date', date);
        formData.append('description', description);
        formData.append('image', image); // Append the selected file

        fetch('index.php?action=changenewsupdates', {
            method: 'POST',
            body: formData
        })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    console.log('Data saved successfully');
                    showAlert("Success", data.message, "success").then(() => {
                        location.reload();
                    });
                } else {
                    console.log('Error:', data.message);
                    showAlert("Error", data.message, "error");
                }
            })
            .catch(error => {
                console.error('Error:', error);
                showAlert("Error", "An error occurred. Please try again.", "error");
            });
    }

}


function saveUpdatedLibInfo() {
    if (validateLibraryInfo()) {
        var name = document.getElementById("name").value.trim();
        var address = document.getElementById("address").value.trim();
        var email = document.getElementById("email").value.trim();
        var phone = document.getElementById("phone").value.trim();
        var fee = document.getElementById("fee").value.trim();
        var fine = document.getElementById('fine').value.trim();
        var fileInput = document.getElementById('logo');
        var logo = fileInput.files.length > 0 ? fileInput.files[0] : null;
        var currentLogo =  document.getElementById("currentLogo").value.trim();


        var formData = new FormData();
        formData.append("name", name);
        formData.append("address", address);
        formData.append("email", email);
        formData.append("phone", phone);
        formData.append("fee", fee);
        formData.append('logo', logo);
        formData.append('fine', fine);
        formData.append("currentLogo", currentLogo); 


        fetch("index.php?action=changelibraryinfo", {
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
                console.error("Error:", error);
                showAlert("Error", "An error occurred. Please try again.", "error");

            });
    }

}

function sendEmailToAllStaff() {
    if (validateStaffEmail()) {
        const button = document.getElementById("btn1");
        const btnText = document.getElementById("btnText1");
        const spinner = document.getElementById("spinner1");
    
        // Show spinner, hide icon
        if (btnText) btnText.classList.add("d-none");
        if (spinner) spinner.classList.remove("d-none");
    
        // Prevent multiple clicks
        button.disabled = true;

        var subject = document.getElementById("staffsubject").value.trim();
        var message = document.getElementById("staffmsg").value.trim();

        var formData = new FormData();
        formData.append("subject", subject);
        formData.append("message", message);

        fetch("index.php?action=sendemailtoallstaff", {
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
                    showAlert("Error", resp.message, "error").then(()=>{
                        resetButtonUI(button, btnText, spinner);
                    });

                }
            })
            .catch(error => {
                console.error("Error:", error);
                showAlert("Error", "An error occurred. Please try again.", "error");

            });
    }

}

function resetButtonUI(button, btnText, spinner) {
    if (btnText) btnText.classList.remove("d-none");
    if (spinner) spinner.classList.add("d-none");
    if (button) button.disabled = false;
}


function sendEmailToAllMembers() {
    if (validateMemberEmail()) {
        const button = document.getElementById("btn2");
        const btnText = document.getElementById("btnText2");
        const spinner = document.getElementById("spinner2");
    
        // Show spinner, hide icon
        if (btnText) btnText.classList.add("d-none");
        if (spinner) spinner.classList.remove("d-none");
    
        // Prevent multiple clicks
        button.disabled = true;

        var subject = document.getElementById("membersubject").value.trim();
        var message = document.getElementById("membermsg").value.trim();

        var formData = new FormData();
        formData.append("subject", subject);
        formData.append("message", message);

        fetch("index.php?action=sendemailtoallstaff", {
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
                    showAlert("Error", resp.message, "error").then(()=>{
                        resetButtonUI(button, btnText, spinner);
                    });
                }
            })
            .catch(error => {
                console.error("Error:", error);
                showAlert("Error", "An error occurred. Please try again.", "error");

            });
    }

}

function loadDetails() {
    loadLibraryInfo();
    loadOpeningHours();
}

function loadOpeningHours() {
    fetch("index.php?action=getopeninghours", {
        method: "POST",
    })
        .then(response => response.json())
        .then(resp => {
            if (resp.success) {
                resp.data.forEach(item => {
                    // Check if open_time or close_time is 00:00:00 and set to "Closed"
                    if (item.day_label === "Weekday") {
                        document.getElementById("weekdayfrom").value = item.open_time === "00:00:00" ? "Closed" : item.open_time;
                        document.getElementById("weekdayto").value = item.close_time === "00:00:00" ? "Closed" : item.close_time;
                    } else if (item.day_label === "Weekend") {
                        document.getElementById("weekendfrom").value = item.open_time === "00:00:00" ? "Closed" : item.open_time;
                        document.getElementById("weekendto").value = item.close_time === "00:00:00" ? "Closed" : item.close_time;
                    } else if (item.day_label === "Holiday") {
                        document.getElementById("holidayfrom").value = item.open_time === "00:00:00" ? "Closed" : item.open_time;
                        document.getElementById("holidayto").value = item.close_time === "00:00:00" ? "Closed" : item.close_time;
                    }
                });
            } else {
                showAlert("Error", resp.message, "error");
            }
        })
        .catch(error => {
            showAlert("Error", "Something went wrong", "error");
            console.error("Error fetching opening hours:", error);
        });
}

function loadLibraryInfo() {
    fetch("index.php?action=getlibraryinfo", {
        method: "POST",
    })
        .then(response => response.json())
        .then(resp => {
            if (resp.success && resp.libraryData) {
                let data = resp.libraryData;

                document.getElementById("name").value = data.name;
                document.getElementById("address").value = data.address;
                document.getElementById("phone").value = data.mobile;
                document.getElementById("email").value = data.email;
                document.getElementById("fee").value = data.membership_fee;
                document.getElementById("fine").value = data.fine_amount;
                document.getElementById("currentLogo").value = data.logo || "";

                let logoPreview = document.getElementById("logoPreview");

                // Check if logo exists before setting src
                if (data.logo) {
                    let logo = `index.php?action=servelogo&image=${encodeURIComponent(data.logo)}`;
                    logoPreview.src = logo;
                    logoPreview.style.display = "block"; // Show preview
                } else {
                    logoPreview.style.display = "none"; // Hide if no logo
                }

            } else {
                showAlert("Error", resp.message || "No information found", "error");
            }
        })
        .catch(error => {
            showAlert("Error", "Something went wrong", "error");
            console.error("Error fetching library info:", error);
        });
}

function validateTimeSetup() {
    let isValid = true;
    const timeFields = ['weekdayfrom', 'weekdayto', 'weekendfrom', 'weekendto', 'holidayfrom', 'holidayto'];

    timeFields.forEach(id => {
        const input = document.getElementById(id);
        const errorSpan = input.nextElementSibling;
        const value = input.value.trim();

        if (value.toLowerCase() === "closed" || /^\d{2}:\d{2}:\d{2}$/.test(value)) {
            errorSpan.textContent = "";
        } else {
            errorSpan.textContent = "Please enter a valid time (HH:MM:SS) or 'Closed'";
            isValid = false;
        }
    });

    return isValid;
}


function validateNewsUpdate() {
    let isValid = true;

    const box = document.getElementById("boxSelection");
    const title = document.getElementById("title");
    const date = document.getElementById("date");
    const desc = document.getElementById("textareaDescription");
    const file = document.getElementById("fileInput");

    if (!box.value) {
        document.getElementById("boxSelectionError").textContent = "Please select a box.";
        isValid = false;
    } else {
        document.getElementById("boxSelectionError").textContent = "";
    }

    if (!title.value.trim()) {
        document.getElementById("titleError").textContent = "Title is required.";
        isValid = false;
    } else {
        document.getElementById("titleError").textContent = "";
    }

    if (!date.value) {
        document.getElementById("dateError").textContent = "Date is required.";
        isValid = false;
    } else {
        document.getElementById("dateError").textContent = "";
    }

    if (!desc.value.trim()) {
        document.getElementById("textareaDescriptionError").textContent = "Description is required.";
        isValid = false;
    } else {
        document.getElementById("textareaDescriptionError").textContent = "";
    }

    if (!file.files[0]) {
        document.getElementById("fileInputError").textContent = "Please upload a file.";
        isValid = false;
    } else {
        document.getElementById("fileInputError").textContent = "";
    }

    return isValid;
}

function validateLibraryInfo() {
    let isValid = true;

    const fields = [
        { id: 'name', error: 'nameError', msg: 'Library name is required.' },
        { id: 'email', error: 'emailError', msg: 'Email is required.' },
        { id: 'phone', error: 'phoneError', msg: 'Phone number is required.' },
        { id: 'address', error: 'addressError', msg: 'Address is required.' },
        { id: 'fee', error: 'feeError', msg: 'Membership fee is required.' },
        { id: 'fine', error: 'fineError', msg: 'Overdue fine is required.' }
    ];

    // Text fields validation
    fields.forEach(f => {
        const input = document.getElementById(f.id);
        if (!input.value.trim()) {
            document.getElementById(f.error).textContent = f.msg;
            isValid = false;
        } else {
            document.getElementById(f.error).textContent = "";
        }
    });

    // Logo validation
    const logo = document.getElementById("logo");
    const currentLogo = document.getElementById("currentLogo").value;

    if (!logo.files[0] && !currentLogo) {
        document.getElementById("logoError").textContent = "Please upload a logo.";
        isValid = false;
    } else {
        document.getElementById("logoError").textContent = "";
    }


    return isValid;
}

function validateStaffEmail() {
    let isValid = true;

    const subject = document.getElementById("staffsubject");
    const message = document.getElementById("staffmsg");

    if (!subject.value.trim()) {
        document.getElementById("staffsuberror").textContent = "Subject is required.";
        isValid = false;
    } else {
        document.getElementById("staffsuberror").textContent = "";
    }

    if (!message.value.trim()) {
        document.getElementById("description1Error").textContent = "Message is required.";
        isValid = false;
    } else {
        document.getElementById("description1Error").textContent = "";
    }

    return isValid;
}

function validateMemberEmail() {
    let isValid = true;

    const subject = document.getElementById("membersubject");
    const message = document.getElementById("membermsg");

    if (!subject.value.trim()) {
        document.getElementById("title2Error").textContent = "Subject is required.";
        isValid = false;
    } else {
        document.getElementById("title2Error").textContent = "";
    }

    if (!message.value.trim()) {
        document.getElementById("description2Error").textContent = "Message is required.";
        isValid = false;
    } else {
        document.getElementById("description2Error").textContent = "";
    }

    return isValid;
}
