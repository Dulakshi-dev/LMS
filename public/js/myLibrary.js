
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
                    <div class="row m-4 border rounded shadow-sm bg-white d-flex align-items-center">
  <!-- Book Image and Info -->
  <div class="col-12 col-md-4 p-4 d-flex justify-content-center align-items-center border-end">
    <div class="book-card text-center position-relative w-100">
      <!-- Bookmark icon -->
      <a href="javascript:void(0)" onclick="unsaveBook('${book.book_id}')" class="position-absolute top-0 end-0 m-2">
        <i class="fa fa-bookmark text-warning fs-4"></i>
      </a>

      <!-- Book Cover -->
      <img class="book-cover img-fluid rounded shadow-sm" src="${coverImageUrl}" alt="Book Cover" style="max-height: 220px; object-fit: contain;">

      <!-- Title & Author -->
      <h5 class="mt-3 text-primary fw-bold">${book.title}</h5>
      <p class="text-muted small">${book.author}</p>
    </div>
  </div>

  <!-- Book Details -->
  <div class="col-12 col-md-8 p-4">
    <h6 class="text-danger fw-semibold mb-2">Book ID: ${book.book_id}</h6>
    <p class="text-justify small text-dark mb-3">${book.description}</p>
    <p class="${book.available_qty > 0 ? 'text-success' : 'text-danger'} fw-bold">${availabilityTextValue}</p>

    <!-- Reserve Button -->
    <button class="btn btn-outline-primary mt-2" onclick="reserveBook('${book.book_id}', '${availabilityTextValue}')">
      
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

