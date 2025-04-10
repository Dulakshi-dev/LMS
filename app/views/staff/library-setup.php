<?php

if (!isset($_SESSION['staff'])) {
    header("Location: index.php"); 
    exit;
}

// Session Timeout (30 minutes)
if (isset($_SESSION['staff']['last_activity']) && (time() - $_SESSION['staff']['last_activity'] > 1800)) {
    session_unset();  // Clear session data
    session_destroy(); 
    header("Location: index.php"); 
    exit;
}

// Reset last activity time (only if user is active)
$_SESSION['staff']['last_activity'] = time();
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
    <style>
        .book-car {
            background: white;
            border-radius: 8px;
            box-shadow: 0px 4px 6px 3px rgba(0, 0, 0, 0.1);
        }
    </style>
</head>

<body onload="loadDetails();">
    <?php require_once Config::getViewPath("staff", "dash_header.php"); ?>
    <div class="d-flex">


    <div>
            <div class="nav-bar d-none d-lg-block">
                <?php include "dash_sidepanel.php"; ?>
            </div>

            <div class="nav-bar d-block d-lg-none">
                <?php include "small_sidepanel.php"; ?>
            </div>

        </div>
        <div class="container-fluid ">
            <div class=" bg-light">
                <div class="m-2 pt-3">
                    <section>
                        <div class="container-fluid p-4 border rounded book-car">
                            <h4 class="text-start"><i class="fa fa-clock-o"></i> Time Setup</h4>
                            <div class="row ">
                                <div class="col-md-2 mt-3 col-12">
                                    <div><label class="fw-bold mb-5">Week Days</label></div>
                                    <div><label class="fw-bold mb-5">Week Ends</label></div>
                                    <div><label class="fw-bold mb-5">Holidays</label></div>
                                </div>

                                <div class="col-md-8 col-12">
                                    <div class="row">
                                        <!-- Week Days -->
                                        <div class="col-6 mb-3">
                                            <label for="weekdayfrom" class="fw-bold">From</label>
                                            <input type="text" class="form-control time-input" id="weekdayfrom" placeholder="00:00:00">
                                            <span class="text-danger error-message"></span>
                                        </div>
                                        <div class="col-6 mb-3">
                                            <label for="weekdayto" class="fw-bold">To</label>
                                            <input type="text" class="form-control time-input" id="weekdayto" placeholder="00:00:00">
                                            <span class="text-danger error-message"></span>
                                        </div>

                                        <!-- Week Ends -->
                                        <div class="col-6 mb-3">
                                            <label for="weekendfrom" class="fw-bold">From</label>
                                            <input type="text" class="form-control time-input" id="weekendfrom" placeholder="00:00:00">
                                            <span class="text-danger error-message"></span>
                                        </div>
                                        <div class="col-6 mb-3">
                                            <label for="weekendto" class="fw-bold">To</label>
                                            <input type="text" class="form-control time-input" id="weekendto" placeholder="00:00:00">
                                            <span class="text-danger error-message"></span>
                                        </div>

                                        <!-- Holidays -->
                                        <div class="col-6 mb-3">
                                            <label for="holidayfrom" class="fw-bold">From</label>
                                            <input type="text" class="form-control time-input" id="holidayfrom" placeholder="00:00:00">
                                            <span class="text-danger error-message"></span>
                                        </div>
                                        <div class="col-6 mb-3">
                                            <label for="holidayto" class="fw-bold">To</label>
                                            <input type="text" class="form-control time-input" id="holidayto" placeholder="00:00:00">
                                            <span class="text-danger error-message"></span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="text-end">
                                <button class="btn btn-primary px-5" onclick="saveOpeningHours();">Save</button>
                            </div>
                        </div>
                    </section>


                    <section>
                        <div class="container-fluid mt-4 p-4 border rounded book-car">
                            <h4 class="text-start"><i class="fa fa-newspaper-o"></i> Latest News and Updates</h4>
                            <div class="row">
                                <div class="col-12 col-md-6">

                                    <div class="d-flex align-items-center gap-3 mt-3">
                                        <label for="boxSelection" class="col-2 fw-bold">Select Box</label>
                                        <select id="boxSelection" class="form-select">
                                            <option value="" selected disabled>Choose an option</option>
                                            <option value="box1">BOX-1</option>
                                            <option value="box2">BOX-2</option>
                                            <option value="box3">BOX-3</option>
                                        </select>

                                    </div>
                                    <span class="text-danger" id="boxSelectionError"></span>

                                    <div class="d-flex align-items-center gap-3 mt-3">
                                        <label for="title" class="col-2 fw-bold">Title</label>
                                        <input type="text" id="title" class="form-control">

                                    </div>
                                    <span class="text-danger" id="titleError"></span>

                                    <div class="d-flex align-items-center gap-3 mt-3">
                                        <label for="date" class="col-2 fw-bold">Date</label>
                                        <input type="date" id="date" class="form-control">

                                    </div>
                                    <span class="text-danger" id="dateError"></span>
                                </div>

                                <div class="col-12 col-md-6">
                                    <div>
                                        <label class="fw-bold ">Description</label>
                                        <textarea class="form-control" id="textareaDescription"></textarea>
                                        <span class="text-danger" id="textareaDescriptionError"></span>
                                    </div>
                                    <div>
                                        <label class="fw-bold mt-3">Media</label>
                                        <input type="file" class="form-control w-75" id="fileInput" name="fileInput"> <span class="text-danger" id="textareaDescriptionError"></span>
                                    </div>

                                    <span class="text-danger" id="fileInputError"></span>
                                    <div class="text-end pt-4">
                                        <button class="btn px-5 btn-primary" onclick="saveNewsUpdate()">Save</button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </section>

                    <section>
                        <div class="container-fluid mt-4 p-4 border rounded book-car">
                            <h4 class="text-start"><i class="fa fa-edit"></i> Update Library Information</h4>
                            <div class="row">
                                <div class="col-12 col-md-6">


                                    <div class="d-flex align-items-center gap-3 mt-3">
                                        <label for="title" class="col-2 fw-bold">Name</label>
                                        <input type="text" id="name" class="form-control">

                                    </div>
                                    <span class="text-danger" id="nameError"></span>

                            

                                    <div class="d-flex align-items-center gap-3 mt-3">
                                        <label for="date" class="col-2 fw-bold">Logo</label>
                                        <img id="logoPreview" src="" alt="Library Logo" class="mt-3" style="width: 150px; height: auto; display: none;">

                                        <input type="file" id="logo" class="form-control" onchange="previewLogo(event)">
                                        <input type="hidden" id="currentLogo" name="currentLogo" value="">

                                    </div>
                                    <span class="text-danger" id="logoError"></span>

                                    <div class="d-flex align-items-center gap-3 mt-3">
                                        <label for="date" class="col-2 fw-bold">Email</label>
                                        <input type="text" id="email" class="form-control">

                                    </div>
                                    <span class="text-danger" id="emailError"></span>

                                    <div class="d-flex align-items-center gap-3 mt-3">
                                        <label for="date" class="col-2 fw-bold">Mobile</label>
                                        <input type="text" id="phone" class="form-control">

                                    </div>
                                    <span class="text-danger" id="phoneError"></span>

                                   
                                </div>

                                <div class="col-12 col-md-6">

                                
                                    <div class="d-flex align-items-center gap-3 mt-3">
                                        <label for="date" class="col-2 fw-bold">Address</label>
                                        <input type="text" id="address" class="form-control">

                                    </div>
                                    <span class="text-danger" id="addressError"></span>


                                    <div class="d-flex align-items-center gap-3 mt-3">
                                        <label for="fee" class="col-2 fw-bold">Fee</label>
                                        <input type="text" id="fee" class="form-control">

                                    </div>
                                    <span class="text-danger" id="feeError"></span>

                                    <div class="d-flex align-items-center gap-3 mt-3">
                                        <label for="fine" class="col-2 fw-bold">Overdue Fine</label>
                                        <input type="text" id="fine" class="form-control">

                                    </div>
                                    <span class="text-danger" id="fineError"></span>


                                    <span class="text-danger" id="fileInputError"></span>
                                    <div class="text-end pt-4">
                                        <button class="btn px-5 btn-primary" onclick="saveUpdatedLibInfo()">Save</button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </section>


                    <section>
                        <div class="container-fluid">
                            <div class="row">
                                <!-- First Email Section -->
                                <div class="col-lg-12 col-sm-12 mt-4 p-4 border rounded book-car">
                                    <h4 class="text-start"><i class="fa fa-envelope"></i> Send Email to All Staff Members</h4>
                                    <div class="row">
                                        <div class="col-5">
                                            <div class="d-flex align-items-center gap-3 mt-3">
                                                <label for="staffsubject" class="col-form-label fw-bold">Subject</label>
                                                <input type="text" id="staffsubject" class="form-control">
                                            </div>
                                            <span class="text-danger" id="staffsuberror"></span>
                                        </div>

                                        <div class="col-7">
                                            <div class="d-flex align-items-center gap-2">
                                                <label for="staffmsg" class="col-form-label fw-bold">Message</label>
                                                <textarea class="form-control" id="staffmsg"></textarea>
                                            </div>
                                            <span class="text-danger" id="description1Error"></span>
                                            <div class="text-end pt-4">
                                                <button class="btn px-5 btn-primary" onclick="sendEmailToAllStaff()">Send</button>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                                <!-- Second Email Section -->
                                 <div class="row">
                                <div class="col-lg-12 col-sm-12 mt-4 p-4 border rounded book-car"> <!-- Added ms-lg-3 for spacing on large screens -->
                                    <h4 class="text-start"><i class="fa fa-envelope"></i> Send Email to All Library Members</h4>
                                    <div class="row">
                                        <div class="col-5">
                                            <div class="d-flex align-items-center gap-3 mt-3">
                                                <label for="membersubject" class="col-form-label fw-bold">Subject</label>
                                                <input type="text" id="membersubject" class="form-control">
                                            </div>
                                            <span class="text-danger" id="title2Error"></span>
                                        </div>

                                        <div class="col-7">
                                            <div class="d-flex align-items-center gap-2">
                                                <label for="membermsg" class="col-form-label fw-bold">Message</label>
                                                <textarea class="form-control" id="membermsg"></textarea>
                                            </div>
                                            <span class="text-danger" id="description2Error"></span>
                                            <div class="text-end pt-4">
                                                <button class="btn px-5 btn-primary" onclick="sendEmailToAllMembers()">Send</button>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </section>
                </div>
            </div>
        </div>

    </div>

    <script>
        function validateTimeForm(event) {
            event.preventDefault();
            let isValid = true;
            const timeInputs = document.querySelectorAll('.time-input');
            const timePattern = /^([01]\d|2[0-3]):([0-5]\d)$/; // Matches HH:MM format (24-hour)

            timeInputs.forEach(input => {
                const errorMessage = input.nextElementSibling;
                if (!timePattern.test(input.value)) {
                    errorMessage.textContent = "Enter a valid time (HH:MM)";
                    isValid = false;
                } else {
                    errorMessage.textContent = "";
                }
            });

            if (isValid) {
                alert("Form submitted successfully!");
            }
        }

        function validateForm() {
            let isValid = true;

            // Select Box Validation
            let boxSelection = document.getElementById("boxSelection");
            let boxSelectionError = document.getElementById("boxSelectionError");
            if (boxSelection.value === "") {
                boxSelectionError.textContent = "Please select an option.";
                isValid = false;
            } else {
                boxSelectionError.textContent = "";
            }

            // Title Validation
            let title = document.getElementById("title");
            let titleError = document.getElementById("titleError");
            if (title.value.trim() === "") {
                titleError.textContent = "Title is required.";
                isValid = false;
            } else {
                titleError.textContent = "";
            }

            // Description Validation
            let description = document.getElementById("description");
            let descriptionError = document.getElementById("descriptionError");
            if (description.value.trim() === "") {
                descriptionError.textContent = "Description is required.";
                isValid = false;
            } else {
                descriptionError.textContent = "";
            }

            // Date Validation
            let date = document.getElementById("date");
            let dateError = document.getElementById("dateError");
            if (date.value === "") {
                dateError.textContent = "Date is required.";
                isValid = false;
            } else {
                dateError.textContent = "";
            }

            // Textarea Description Validation
            let textareaDescription = document.getElementById("textareaDescription");
            let textareaDescriptionError = document.getElementById("textareaDescriptionError");
            if (textareaDescription.value.trim() === "") {
                textareaDescriptionError.textContent = "Description is required.";
                isValid = false;
            } else {
                textareaDescriptionError.textContent = "";
            }

            // File Input Validation
            let fileInput = document.getElementById("fileInput");
            let fileInputError = document.getElementById("fileInputError");
            if (fileInput.files.length === 0) {
                fileInputError.textContent = "Please upload a file.";
                isValid = false;
            } else {
                fileInputError.textContent = "";
            }

            // Prevent form submission if any validation fails
            if (!isValid) {
                return false;
            }

            alert("Form submitted successfully!");
        }

        function validateEmailForm() {
            let isValid = true;

            // Subject Validation
            let subject = document.getElementById("title1");
            let subjectError = document.getElementById("title1Error");
            if (subject.value.trim() === "") {
                subjectError.textContent = "Subject is required.";
                isValid = false;
            } else {
                subjectError.textContent = "";
            }

            // Message Validation
            let message = document.getElementById("description1");
            let messageError = document.getElementById("description1Error");
            if (message.value.trim() === "") {
                messageError.textContent = "Message is required.";
                isValid = false;
            } else {
                messageError.textContent = "";
            }

            // Prevent form submission if validation fails
            if (!isValid) {
                return false;
            }

            alert("Email sent successfully!");
        }


        function validateFee() {
            let fee = document.getElementById("fee").value.trim();
            let feeError = document.getElementById("feeError");

            if (fee === "") {
                feeError.textContent = "Fee is required.";
                return false;
            } else if (isNaN(fee) || Number(fee) <= 0) {
                feeError.textContent = "Please enter a valid membership fee.";
                return false;
            } else {
                feeError.textContent = "";
                alert("Membership fee updated successfully!");
            }
        }
    </script>
    <script src="<?php echo Config::getJsPath("librarySetup.js"); ?>"></script>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

</body>

</html>