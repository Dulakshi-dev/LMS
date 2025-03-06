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

<body>
    <div class=" bg-light">
        <div class="p-5">
            <section>
                <div class="container-fluid mt-4 p-4 border rounded book-car">
                    <h3 class="text-start"><i class="fa fa-clock-o"></i> Time Setup</h3>
                    <div class="row ">
                        <div class="col-md-2 mt-5 col-12">
                            <div><label class="fw-bold mb-5">Opening Hours</label></div>
                            <div><label class="fw-bold mb-5">Opening Hours</label></div>
                            <div><label class="fw-bold mb-5">Opening Hours</label></div>
                        </div>

                        <div class="col-md-5 col-12">
                            <h5>Week Days</h5>
                            <div class="d-flex gap-3">
                                <div>
                                    <label>From</label>
                                    <input type="text" class="form-control time-input">
                                    <span class="text-danger error-message"></span>
                                </div>
                                <div>
                                    <label>To</label>
                                    <input type="text" class="form-control time-input">
                                    <span class="text-danger error-message"></span>
                                </div>
                            </div>
                            <div class="d-flex gap-3 mt-2">
                                <div>
                                    <label>From</label>
                                    <input type="text" class="form-control time-input">
                                    <span class="text-danger error-message"></span>
                                </div>
                                <div>
                                    <label>To</label>
                                    <input type="text" class="form-control time-input">
                                    <span class="text-danger error-message"></span>
                                </div>
                            </div>
                            <div class="d-flex gap-3 mt-2">
                                <div>
                                    <label>From</label>
                                    <input type="text" class="form-control time-input">
                                    <span class="text-danger error-message"></span>
                                </div>
                                <div>
                                    <label>To</label>
                                    <input type="text" class="form-control time-input">
                                    <span class="text-danger error-message"></span>
                                </div>
                            </div>
                        </div>

                        <div class="col-md-5 col-12">
                            <h5 class="">WeekEnd Days</h5>
                            <div class="d-flex gap-3">
                                <div>
                                    <label>From</label>
                                    <input type="text" class="form-control time-input">
                                    <span class="text-danger error-message"></span>
                                </div>
                                <div>
                                    <label>To</label>
                                    <input type="text" class="form-control time-input">
                                    <span class="text-danger error-message"></span>
                                </div>
                            </div>
                            <div class="d-flex gap-3 mt-2">
                                <div>
                                    <label>From</label>
                                    <input type="text" class="form-control time-input">
                                    <span class="text-danger error-message"></span>
                                </div>
                                <div>
                                    <label>To</label>
                                    <input type="text" class="form-control time-input">
                                    <span class="text-danger error-message"></span>
                                </div>
                            </div>
                            <div class="d-flex gap-3 mt-2">
                                <div>
                                    <label>From</label>
                                    <input type="text" class="form-control time-input">
                                    <span class="text-danger error-message"></span>
                                </div>
                                <div>
                                    <label>To</label>
                                    <input type="text" class="form-control time-input">
                                    <span class="text-danger error-message"></span>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="text-end">
                        <button class="btn btn-primary px-5" onclick="validateTimeForm(event)">Save</button>
                    </div>
                </div>
            </section>


            <section>
                <div class="container-fluid mt-4 p-4 border rounded book-car">
                    <h3 class="text-start"><i class="fa fa-newspaper-o"></i> Latest News and Updates</h3>
                    <div class="row">
                        <div class="col-12 col-md-6">

                            <div class="d-flex align-items-center gap-3 mt-3">
                                <label for="boxSelection" class="col-form-label fw-bold">Select Box</label>
                                <select id="boxSelection" class="form-select">
                                    <option value="" selected disabled>Choose an option</option>
                                    <option value="box1">BOX-1</option>
                                    <option value="box2">BOX-2</option>
                                    <option value="box3">BOX-3</option>
                                </select>

                            </div>
                            <span class="text-danger" id="boxSelectionError"></span>
                            
                            <div class="d-flex align-items-center gap-3 mt-3">
                                <label for="title" class="col-form-label fw-bold">Title</label>
                                <input type="text" id="title" class="form-control">

                            </div>
                            <span class="text-danger" id="titleError"></span>

                            
                            <div class="d-flex align-items-center gap-3 mt-3">
                                <label for="description" class="col-form-label fw-bold">Description</label>
                                <input type="text" id="description" class="form-control">

                            </div>
                            <span class="text-danger" id="descriptionError"></span>

                            <div class="d-flex align-items-center gap-3 mt-3">
                                <label for="date" class="col-form-label fw-bold">Date</label>
                                <input type="date" id="date" class="form-control">

                            </div>
                            <span class="text-danger" id="dateError"></span>
                        </div>

                        <div class="col-12 col-md-6">
                            <div>
                                <label class="fw-bold my-4">Description</label>
                                <textarea class="form-control" id="textareaDescription"></textarea>
                                <span class="text-danger" id="textareaDescriptionError"></span>
                            </div>
                            <div class="my-3 d-flex justify-content-center">
                                <input type="file" class="form-control w-75" id="fileInput">

                            </div>
                            <span class="text-danger" id="fileInputError"></span>
                            <div class="text-end pt-4">
                                <button class="btn px-5 btn-primary" onclick="validateForm()">Save</button>
                            </div>
                        </div>
                    </div>
                </div>
            </section>




            <section>
                <div class="container-fluid mt-4 p-4 border rounded book-car">
                    <h3 class="text-start"><i class="fa fa-envelope-o"></i> Send Email to All Users</h3>
                    <div class="row">
                        <div class="col-12 col-md-6">
                            <div class="d-flex align-items-center py-3 gap-3 mt-3">
                                <label for="title1" class="col-form-label fw-bold">Subject</label>
                                <input type="text" id="title1" class="form-control">

                            </div>
                            <span class="text-danger" id="title1Error"></span>
                        </div>

                        <div class="col-12 col-md-6">
                            <div class="d-flex align-items-center gap-3 mt-3">
                                <label for="description1" class="col-form-label fw-bold">Message</label>
                                <textarea class="form-control" id="description1"></textarea>

                            </div>
                            <span class="text-danger" id="description1Error"></span>
                            <div class="text-end pt-4">
                                <button class="btn px-5 btn-primary" onclick="validateEmailForm()">Send</button>
                            </div>
                        </div>
                    </div>
                </div>
            </section>



            <section>
                <div class="container-fluid mt-4 p-4 border border rounded book-car">
                    <h3 class="text-start mb-3"><i class="fa fa-money"></i> Change Membership Fee</h3>
                    <div class="row align-items-center">
                        <div class="col-md-6">
                            <div class="d-flex align-items-center gap-3 mb-3">
                                <label for="fee" class="col-form-label fw-bold">Fee</label>
                                <input type="text" id="fee" class="form-control" placeholder="Enter membership fee">
                            </div>
                            <span class="text-danger" id="feeError"></span>
                        </div>
                        <div class="col-md-6 text-end">
                            <button class="btn btn-primary px-5" onclick="validateFee()">Save</button>
                        </div>
                    </div>
                </div>
            </section>


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

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

</body>

</html>