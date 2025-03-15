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
                    <td>${book.return_date}</td>
                 
                  
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

