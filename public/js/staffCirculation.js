
function showAlert(title, message, type) {
    return Swal.fire({
        title: title,
        text: message,
        icon: type, // 'success', 'error', 'warning', 'info', 'question'
        confirmButtonText: 'OK'
    });
}

function loadIssuedBooks(page = 1) {
    var memberid = document.getElementById("memberid").value.trim();
    var bookid = document.getElementById("bookid").value.trim();
    var status = document.getElementById("statusSelection").value; // Get dropdown value

    let formData = new FormData();
    formData.append("page", page);
    formData.append("memberid", memberid);
    formData.append("bookid", bookid);
    formData.append("status", status); // Append the selected status

    fetch("index.php?action=loadissuedbooks", {
        method: "POST",
        body: formData,
    })
        .then(response => response.json())
        .then(resp => {
            let tableBody = document.getElementById("issueBookTableBody");
            tableBody.innerHTML = "";

            if (resp.success && resp.issuebooks.length > 0) {
                resp.issuebooks.forEach(issuebook => {
                    let returnOrDueDate;
                    let fines;

                    if (issuebook.return_date) {
                        returnOrDueDate = `<span class="text-success">Return: ${issuebook.return_date}</span>`;
                        fines = issuebook.amount ? parseFloat(issuebook.amount).toFixed(2) : "0.00";
                    } else {
                        let dueDate = new Date(issuebook.due_date);
                        let today = new Date();
                        fines = `<p class="text-secondary">Not Returned</p>`;

                        if (dueDate < today) {
                            returnOrDueDate = `<span class="text-danger">Overdue: ${issuebook.due_date}</span>`;
                        } else {
                            returnOrDueDate = `<span class="text-warning">Due: ${issuebook.due_date}</span>`;
                        }
                    }

                    let row = `
                <tr>
                    <td>${issuebook.borrow_id}</td>
                    <td>${issuebook.borrow_book_id}</td>
                    <td>${issuebook.title}</td>
                    <td>${issuebook.member_id}</td>
                    <td>${issuebook.fname}</td>
                    <td>${issuebook.borrow_date}</td>
                    <td>${returnOrDueDate}</td>
                    <td>${fines}</td>
                    <td>`;

                    if (!issuebook.return_date) {
                        row += `<div class="m-1 action-buttons">
                        <button class="btn btn-success my-1 btn-sm return-book"
                            data-due-date="${issuebook.due_date}"
                            data-borrow-id="${issuebook.borrow_id}"
                            data-book-id="${issuebook.borrow_book_id}"
                            data-member-id="${issuebook.borrow_member_id}"
                            data-member-name="${issuebook.fname} ${issuebook.lname}"
                            data-book-title="${issuebook.title}"
                            data-book-email="${issuebook.email}">

                            <i class="fa fa-edit"></i>
                        </button>
                    </div>`;
                    } else {
                        row += `<p class="text-success">Returned</p>`;
                    }

                    row += `</td></tr>`;

                    tableBody.innerHTML += row;
                });
            } else {
                tableBody.innerHTML = "<tr><td colspan='10'>No issued books found</td></tr>";
            }

            createPagination("pagination", resp.totalPages, page, "loadIssuedBooks");
        })
        .catch(error => {
            console.error("Error fetching borrow data:", error);
        });
}


document.addEventListener("DOMContentLoaded", function () {
    // Check if the "statusSelection" element exists before adding the event listener
    const statusSelection = document.getElementById("statusSelection");
    if (statusSelection) {
        statusSelection.addEventListener("change", function () {
            loadIssuedBooks();
        });
    }

    // Handle "return-book" button click across the document
    document.body.addEventListener("click", function (event) {
        if (event.target.closest(".return-book")) {
            let button = event.target.closest(".return-book");
            returnButtonClick(button);
        }
    });


});


function returnButtonClick(button) {
    const dueDate = button.getAttribute("data-due-date");
    const borrow_id = button.getAttribute("data-borrow-id");
    const book_id = button.getAttribute("data-book-id");
    const memberId = button.getAttribute("data-member-id");
    const memberName = button.getAttribute("data-member-name");
    const bookTitle = button.getAttribute("data-book-title");
    const email = button.getAttribute("data-book-email");

    let updateModal = new bootstrap.Modal(document.getElementById("borrowBookAction"));
    updateModal.show();

    // fill form values
    document.getElementById("dueDate").value = dueDate;
    document.getElementById("borrowId").value = borrow_id;
    document.getElementById("bookId").value = book_id;
    document.getElementById("memberId").value = memberId;
    document.getElementById("name").value = memberName;
    document.getElementById("title").value = bookTitle;
    document.getElementById("email").value = email;

    let today = new Date().toISOString().split("T")[0]; // yyyy-mm-dd
    document.getElementById("returnDate").value = today;

    generateFineFromDOM();
}


function validateReturnForm() {
    let isValid = true;
    let returnDate = document.getElementById("returnDate").value.trim();
    if (returnDate === "") {
        document.getElementById("returndateerror").innerText = "Please select the return date.";
        isValid = false;
    }
    return isValid;
}

function loadBookData() {
    var book_id = document.getElementById("book_id").value;

    //validate book id - empty and format

    var formData = new FormData();
    formData.append("book_id", book_id);
    fetch("index.php?action=loadborrowbookdata", {
        method: "POST",
        body: formData,
    })
        .then(response => response.json())
        .then(resp => {
            if (resp.success) {
                document.getElementById("isbn").value = resp.isbn;
                document.getElementById("title").value = resp.title;
                document.getElementById("author").value = resp.author;

            } else {
                showAlert("Error", resp.message, "error");
            }
        })
        .catch(error => {
            console.error("Error fetching book data:", error);
        });
}

function loadMemberData() {
    var member_id = document.getElementById("member_id").value;

    //validate member id - empty and format

    var formData = new FormData();
    formData.append("member_id", member_id);
    fetch("index.php?action=loadborrowmemberdata", {
        method: "POST",
        body: formData,
    })
        .then(response => response.json())
        .then(resp => {
            if (resp.success) {
                document.getElementById("nic").value = resp.nic;
                document.getElementById("memName").value = resp.name;
                document.getElementById("email").value = resp.email;

            } else {
                showAlert("Success", resp.message, "success");
            }
        })
        .catch(error => {
            console.error("Error fetching book data:", error);
            showAlert("Error", resp.message, "error");
        });
}

function issueBook() {
    // Get the values from the form fields
    const memberidPattern = /^M-\d{6}$/;
    const bookidPattern = /^B-\d{6}$/;

    var book_id = document.getElementById("book_id").value.trim();
    var member_id = document.getElementById("member_id").value.trim();
    var issueDate = document.getElementById("issueDate").value.trim();
    var returnDate = document.getElementById("returnDate").value.trim();

    // Detail validation
    var isValid = true;

    // Reset all error messages
    document.getElementById("book_id_error").innerText = "";
    document.getElementById("member_id_error").innerText = "";
    document.getElementById("issueDate_error").innerText = "";
    document.getElementById("returnDate_error").innerText = "";

    // Validate form fields
    if (book_id === "") {
        document.getElementById("book_id_error").innerText = "Book ID is required.";
        isValid = false;
    } else if (!book_id.match(bookidPattern)) {
        document.getElementById("book_id_error").innerText = "Invalid Book ID.";
        isValid = false;

    }

    if (member_id === "") {
        document.getElementById("member_id_error").innerText = "Membership ID is required.";
        isValid = false;
    } else if (!member_id.match(memberidPattern)) {
        document.getElementById("member_id_error").innerText = "Invalid Membership ID.";
        isValid = false;

    }

    if (issueDate === "") {
        document.getElementById("issueDate_error").innerText = "Issue date is required.";
        isValid = false;
    }
    if (returnDate === "") {
        document.getElementById("returnDate_error").innerText = "Due date is required.";
        isValid = false;
    }

    if (!isValid) {
        return;  // Stop form submission if validation fails
    }

    const button = document.getElementById("btn");
    const btnText = document.getElementById("btnText");
    const spinner = document.getElementById("spinner");

    // Show spinner, hide icon
    if (btnText) btnText.classList.add("d-none");
    if (spinner) spinner.classList.remove("d-none");

    // Prevent multiple clicks
    button.disabled = true;
    // Create a new FormData object
    let formData = new FormData();

    // Collect input values
    formData.append("book_id", book_id);
    formData.append("member_id", member_id);
    formData.append("isbn", document.getElementById("isbn").value);
    formData.append("nic", document.getElementById("nic").value);
    formData.append("title", document.getElementById("title").value);
    formData.append("memName", document.getElementById("memName").value);
    formData.append("author", document.getElementById("author").value);
    formData.append("email", document.getElementById("email").value);
    formData.append("borrow_date", issueDate);
    formData.append("due_date", returnDate);

    // Send data to controller 
    fetch("index.php?action=issuebook", {
        method: "POST",
        body: formData
    })
        .then(response => response.json())
        .then(resp => {
            if (resp.success) {
                showAlert("Success", resp.message, "success").then(() => {
                    location.reload();
                });
            } else {
                Swal.fire("Error", resp.message, "error").then(() => {
                    resetButtonUI(button, btnText, spinner);
                })

            }
        })
        .catch(error => {
            console.error("Error issuing book:", error);
            Swal.fire("Error", "Something went wrong!", "error");
        });
}


function generateFineFromDOM() {
    var dueDate = document.getElementById("dueDate").value;
    var returnDate = document.getElementById("returnDate").value;

    let fineRate = parseFloat(document.getElementById("finerate").innerText.trim()) || 0;

    if (!dueDate || !returnDate) {
        document.getElementById("fines").value = 0;
        return 0;
    }

    let due = new Date(dueDate);
    let returned = new Date(returnDate);

    // difference in days
    let timeDiff = returned - due;
    let daysLate = Math.ceil(timeDiff / (1000 * 60 * 60 * 24));

    let fine = Math.max(daysLate * fineRate, 0);

    document.getElementById("fines").value = fine;

    return fine;
}


function returnBook() {
    const button = document.getElementById("btn");
    const btnText = document.getElementById("btnText");
    const spinner = document.getElementById("spinner");

    // Show spinner, hide icon
    if (btnText) btnText.classList.add("d-none");
    if (spinner) spinner.classList.remove("d-none");

    // Prevent multiple clicks
    button.disabled = true;

    let formData = new FormData();

    // Collect input values
    formData.append("borrowId", document.getElementById("borrowId").value);
    formData.append("bookId", document.getElementById("bookId").value);
    formData.append("memberId", document.getElementById("memberId").value);
    formData.append("dueDate", document.getElementById("dueDate").value);
    formData.append("returnDate", document.getElementById("returnDate").value);
    formData.append("fines", document.getElementById("fines").value);
    formData.append("name", document.getElementById("name").value);
    formData.append("title", document.getElementById("title").value);
    formData.append("email", document.getElementById("email").value);

    // Send data to the backend using Fetch API
    fetch("index.php?action=returnbook", {
        method: "POST",
        body: formData
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

                })
            }
        })
        .catch(error => {
            console.error("Error returning book:", error);
            Swal.fire("Error", "Something went wrong!", "error");
        });
}

function resetButtonUI(button, btnText, spinner) {
    if (btnText) btnText.classList.remove("d-none");
    if (spinner) spinner.classList.add("d-none");
    if (button) button.disabled = false;
}


function generateIssuedBookReport() {
    const table = document.getElementById("issueBookTable");
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
    formData.append("title", "Issued Books Report");
    formData.append("filename", "issued_books_report.pdf");

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