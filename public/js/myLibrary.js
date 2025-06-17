
function showAlert(title, message, type) {
    return Swal.fire({
        title: title,
        text: message,
        icon: type, // 'success', 'error', 'warning', 'info', 'question'
        confirmButtonText: 'OK'
    });
}

function loadSavedBooks(page = 1) {
    var bookid = document.getElementById("bookid").value.trim();
    var title = document.getElementById("title").value.trim();
    var category = document.getElementById("category").value; // No .trim() needed for select

    let formData = new FormData();
    formData.append("page", page);
    formData.append("bookid", bookid);
    formData.append("title", title);
    formData.append("category", category);

    fetch("index.php?action=savedbooks", {
        method: "POST",
        body: formData,
    })
    .then(response => response.json())
    .then(resp => {
        let tableBody = document.getElementById("myLibraryBody");
        tableBody.innerHTML = ""; // Clear previous content

        if (resp.success && resp.books.length > 0) {
            resp.books.forEach(book => {
                let coverImageUrl = `index.php?action=serveimage&image=${encodeURIComponent(book.cover_page)}`;
                let availabilityTextValue = book.available_qty > 0 ? 'Available' : 'Not Available';

                let row = `
                    <div class="row m-4 border rounded d-flex align-items-center">
                        <div class="col-md-4 mb-4 mb-md-0 p-2 d-flex justify-content-center align-items-center">
                            <div class="book-card text-center position-relative d-flex flex-column align-items-center">
                                <a href="javascript:void(0)" onclick="unsaveBook('${book.book_id}')" class="position-absolute top-0 end-0 mx-1">
                                    <i class="fa fa-bookmark text-warning fs-5"></i>
                                </a>
                                <img class="book-cover img-fluid" src="${coverImageUrl}" alt="Book Cover">
                                <h2 class="book-title mt-3">${book.title}</h2>
                                <p class="book-author text-muted">${book.author}</p>
                            </div>
                        </div>
                        <div class="col-md-8 p-5">
                            <h2 class="text-danger">Book ID : ${book.book_id}</h2>
                            <p class="col-10 text-justify mt-3">${book.description}</p>
                            <p class="${book.available_qty > 0 ? 'text-success' : 'text-danger'} fw-bold">${availabilityTextValue}</p>
                            <button class="btn" id="success" onclick="reserveBook('${book.book_id}', '${availabilityTextValue}')">
                                Reserve
                            </button>
                        </div>
                    </div>
                `;

                tableBody.insertAdjacentHTML("beforeend", row); // Better performance
            });
        } else {
            tableBody.innerHTML = "<p class='text-center text-muted'>No books found</p>";
        }
        createPagination("pagination", resp.totalPages, page, "loadSavedBooks");
    })
    .catch(error => {
        console.error("Error fetching book data:", error);
    });
}


function reserveBook(book_id, availability){
    var formData = new FormData();
    formData.append("book_id", book_id);
    formData.append("availability", availability);


    fetch("index.php?action=reserve", {
        method: "POST",
        body: formData,
    })
        .then(response => response.json())
        .then(resp => {
            if (resp.success) {
                
                showAlert("Success", resp.message, "success");
                
            } else {
                showAlert("Error", resp.message, "error");
            }
        })
        .catch(error => {
            console.error("Error reserving book:", error);
           
                showAlert("Error", "Something went wrong. Please try again", "error");

           
        });
}

function unsaveBook(bookId) {
    fetch(`index.php?action=unsave&book_id=${bookId}`, {
        method: "GET"
    })
    .then(response => response.json())
    .then(resp => {
        if (resp.success) {
            showAlert("Success", resp.message, "success");

            loadSavedBooks(); 
        } else {
            showAlert("Error", resp.message, "error");

        }
    })
    .catch(error => console.error("Error unsaving book:", error));
}

