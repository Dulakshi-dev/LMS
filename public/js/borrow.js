
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

    let formData = new FormData();
    formData.append("page", page);
    formData.append("memberid", memberid);
    formData.append("bookid", bookid);

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
                    // Determine the value for the return/due date column
                    let returnOrDueDate;
                    let fines;
                    if (issuebook.return_date) {
                        returnOrDueDate = `<span class="text-success">Return: ${issuebook.return_date}</span>`;
                        fines = issuebook.amount;
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
                        row += `<div class="m-1">
                            <button class="btn btn-success my-1 btn-sm return-book"
                                data-due-date="${issuebook.due_date}"
                                data-borrow-id="${issuebook.borrow_id}"
                                data-book-id="${issuebook.borrow_book_id}"
                                data-member-id="${issuebook.borrow_member_id}">
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
    const memberId = button.getAttribute("data-member-id"); // Corrected case

    let updateModal = new bootstrap.Modal(document.getElementById("borrowBookAction"));
    updateModal.show();

    document.getElementById("dueDate").value = dueDate;
    document.getElementById("borrowId").value = borrow_id;
    document.getElementById("bookId").value = book_id;
    document.getElementById("memberId").value = memberId;
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
    var book_id = document.getElementById("book_id").value;
    var member_id = document.getElementById("member_id").value;
    var issueDate = document.getElementById("issueDate").value;
    var returnDate = document.getElementById("returnDate").value;

    // Detail validation
    if (book_id.trim() === "") {
        document.getElementById("book_id_error").innerText = "Book ID is required.";
    } else if (member_id.trim() === "") {
        document.getElementById("member_id_error").innerText = "Membership ID is required.";
        isValid = false;
    } if (issueDate.trim() === "") {
        document.getElementById("issueDate_error").innerText = "Issue date is required.";

    } if (returnDate.trim() === "") {
        document.getElementById("returnDate_error").innerText = "Due date is required.";
    } else {
        let formData = new FormData();

        // Collect input values
        formData.append("book_id", document.getElementById("book_id").value);
        formData.append("member_id", document.getElementById("member_id").value);
        formData.append("isbn", document.getElementById("isbn").value);
        formData.append("nic", document.getElementById("nic").value);
        formData.append("title", document.getElementById("title").value);
        formData.append("memName", document.getElementById("memName").value);
        formData.append("author", document.getElementById("author").value);
        formData.append("borrow_date", document.getElementById("issueDate").value);
        formData.append("due_date", document.getElementById("returnDate").value);

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
                    Swal.fire("Error", resp.message, "error"); // Show error message
                }
            })
            .catch(error => {
                console.error("Error issuing book:", error);
                Swal.fire("Error", "Something went wrong!", "error");
            });
    }
}

function generateFine(finePerDay) {

    var dueDate = document.getElementById("dueDate").value;
    var returnDate = document.getElementById("returnDate").value;

    let due = new Date(dueDate);
    let returned = new Date(returnDate);

    // Calculate difference in days
    let timeDiff = returned - due;
    let daysLate = Math.ceil(timeDiff / (1000 * 60 * 60 * 24)); // Convert milliseconds to days

    let fine = Math.max(daysLate * finePerDay, 0);

    // Show alert based on fine amount
    if (fine > 0) {
        document.getElementById("fines").value = fine;
    } else {
        document.getElementById("fines").value = 0;
    }

    return fine;

}

function returnBook(){
    let formData = new FormData();

    // Collect input values
    formData.append("borrowId", document.getElementById("borrowId").value);
    formData.append("bookId", document.getElementById("bookId").value);
    formData.append("memberId", document.getElementById("memberId").value);
    formData.append("dueDate", document.getElementById("dueDate").value);
    formData.append("returnDate", document.getElementById("returnDate").value);
    formData.append("fines", document.getElementById("fines").value);

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
            showAlert("Error", resp.message, "error"); 
        }
    })
    .catch(error => {
        console.error("Error returning book:", error);
        Swal.fire("Error", "Something went wrong!", "error");
    });
}