
function showAlert(title, message, type) {
    return Swal.fire({
        title: title,
        text: message,
        icon: type, // 'success', 'error', 'warning', 'info', 'question'
        confirmButtonText: 'OK'
    });
}

function loadBorrowBooks(page = 1) {
    var bookid = document.getElementById("bookid").value.trim();
    var title = document.getElementById("title").value.trim();
    var category = document.getElementById("category").value.trim();

    let formData = new FormData();
    formData.append("page", page);
    formData.append("bookid", bookid);
    formData.append("title", title);
    formData.append("category", category);

    fetch("index.php?action=loadBorrowHistory", {
        method: "POST",
        body: formData,
    })
        .then(response => response.json())
        .then(resp => {
            let tableBody = document.getElementById("bookTableBody");
            tableBody.innerHTML = "";

            if (resp.success && resp.books.length > 0) {
                resp.books.forEach(book => {
                    let coverImageUrl = `index.php?action=serveimage&image=${encodeURIComponent(book.cover_page)}`;

                    let row = `
                <tr>
                    <td>${book.borrow_id}</td>
                    <td>${book.book_id}</td>
                    <td><img src="${coverImageUrl}" alt="Book Cover" style="width: 50px; height: 75px; object-fit: cover;"></td>
                    <td>${book.title}</td>
                    <td>${book.borrow_date}</td>
                    <td>${book.due_date}</td>
                    <td>${getBorrowStatus(book.borrow_date, book.due_date, book.return_date)}</td>
                </tr>
                `;

                    tableBody.innerHTML += row;
                });
            } else {
                tableBody.innerHTML = "<tr><td colspan='7'>No books found</td></tr>";
            }
            createPagination("pagination", resp.totalPages, page, "loadBorrowBooks");
        })
        .catch(error => {
            console.error("Error fetching book data:", error);
        });
}

function getBorrowStatus(borrowDate, dueDate, returnDate) {
    if (returnDate && returnDate !== "null") { 
        return `<span style="color:green;">Returned on ${returnDate}</span>`;
    }

    let today = new Date();
    let due = new Date(dueDate);

    let diffTime = due - today;
    let diffDays = Math.ceil(diffTime / (1000 * 60 * 60 * 24));

    if (diffDays < 0) {
        return `<span style="color:red; font-weight:bold;">Overdue (${Math.abs(diffDays)} day${Math.abs(diffDays) > 1 ? "s" : ""} late)</span>`;
    } else if (diffDays === 0) {
        return `<span style="color:orange; font-weight:bold;">Due Today</span>`;
    } else if (diffDays <= 4) {
        return `<span style="color:darkorange;">Due Soon (${diffDays} day${diffDays > 1 ? "s" : ""} left)</span>`;
    } else {
        return `<span style="color:blue;">${diffDays} Day${diffDays > 1 ? "s" : ""} To Due</span>`;
    }
}

