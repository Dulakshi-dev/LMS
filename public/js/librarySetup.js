function saveOpeningHours(){
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

function saveNewsUpdate() {
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


function saveUpdatedLibInfo(){
    var name = document.getElementById("name").value.trim();
    var address = document.getElementById("address").value.trim();
    var email = document.getElementById("email").value.trim();
    var phone = document.getElementById("phone").value.trim();
    var fee = document.getElementById("fee").value.trim();
    var fine = document.getElementById('fine').value.trim();
    var fileInput = document.getElementById('logo');
    var logo = fileInput.files.length > 0 ? fileInput.files[0] : null; // Ensure file exists


    var formData = new FormData();
    formData.append("name", name);
    formData.append("address", address);
    formData.append("email", email);
    formData.append("phone", phone);
    formData.append("fee", fee);
    formData.append('logo', logo);
    formData.append('fine', fine);

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

function sendEmailToAllStaff(){
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
                showAlert("Error", resp.message, "error");

            }
        })
        .catch(error => {
            console.error("Error:", error);
            showAlert("Error", "An error occurred. Please try again.", "error");

        });
}

function sendEmailToAllMembers(){
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
                showAlert("Error", resp.message, "error");

            }
        })
        .catch(error => {
            console.error("Error:", error);
            showAlert("Error", "An error occurred. Please try again.", "error");

        });
}

function loadDetails(){
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
