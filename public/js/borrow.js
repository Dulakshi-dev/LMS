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
                    let row = `
                    <tr>
                        <td>${issuebook.borrow_id}</td>
                        <td>${issuebook.borrow_book_id}</td>
                        <td>${issuebook.title} </td>
                        <td>${issuebook.member_id}</td>
                        <td>${issuebook.fname}</td>
                        <td>${issuebook.borrow_date}</td>
                        <td>${issuebook.due_date}</td>
                        <td>${issuebook.return_date}</td>
                        <td>${issuebook.amount}</td>
                        <td>`;

                    if (issuebook.return_date == null) {
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
                        row += `<p class="text-danger">Book Returned</p>`;
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
                alert("Failed to load book data. Please try again.");
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
                alert("Failed to load book data. Please try again.");
            }
        })
        .catch(error => {
            console.error("Error fetching book data:", error);
        });
}


function generateFine() {

    var dueDate = document.getElementById("dueDate").value;
    var returnDate = document.getElementById("returnDate").value;

    let due = new Date(dueDate);
    let returned = new Date(returnDate);

    // Calculate difference in days
    let timeDiff = returned - due;
    let daysLate = Math.ceil(timeDiff / (1000 * 60 * 60 * 24)); // Convert milliseconds to days

    let fine = Math.max(daysLate * 5, 0);

    // Show alert based on fine amount
    if (fine > 0) {
        document.getElementById("fines").value = fine;
    } else {
        document.getElementById("fines").value = 0;
    }

    return fine;

}
function validateForm() {
    // Clear any previous error messages
    document.getElementById("book_id_error").innerText = "";
    document.getElementById("member_id_error").innerText = "";
    document.getElementById("issueDate_error").innerText = "";
    document.getElementById("returnDate_error").innerText = "";

    // Get the values from the form fields
    var book_id = document.getElementById("book_id").value;
    var member_id = document.getElementById("member_id").value;
    var issueDate = document.getElementById("issueDate").value;
    var returnDate = document.getElementById("returnDate").value;

    var isValid = true;

    // Book ID validation
    if (book_id.trim() === "") {
        document.getElementById("book_id_error").innerText = "Book ID is required.";
        isValid = false;
    }

    // Member ID validation
    if (member_id.trim() === "") {
        document.getElementById("member_id_error").innerText = "Membership ID is required.";
        isValid = false;
    }

    // Issue Date validation
    if (issueDate.trim() === "") {
        document.getElementById("issueDate_error").innerText = "Issue date is required.";
        isValid = false;
    }

    // Return Date validation
    if (returnDate.trim() === "") {
        document.getElementById("returnDate_error").innerText = "Due date is required.";
        isValid = false;
    }

    // Return false if validation fails
    return isValid;
}
