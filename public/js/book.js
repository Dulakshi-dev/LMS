
function showAlert(title, message, type) {
    return Swal.fire({
        title: title,
        text: message,
        icon: type, // 'success', 'error', 'warning', 'info', 'question'
        confirmButtonText: 'OK'
    });
}

function loadBooks(page = 1) {
    var bookid = document.getElementById("bookid").value.trim();
    var title = document.getElementById("bname").value.trim();
    var isbn = document.getElementById("isbn").value.trim();

    let formData = new FormData();
    formData.append("page", page);
    formData.append("bookid", bookid);
    formData.append("title", title);
    formData.append("isbn", isbn);

    fetch("index.php?action=loadBooks", {
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
                    <td>${book.book_id}</td>
                    <td>${book.isbn}</td>
                    <td><img src="${coverImageUrl}" alt="Book Cover" style="width: 50px; height: 75px; object-fit: cover;"></td>
                    <td>${book.title}</td>
                    <td>${book.author}</td>
                    <td>${book.pub_year}</td>
                    <td>${book.category_name}</td>
                    <td>${book.language_name}</td>
                    <td>${book.qty}</td>
                    <td>${book.qty - book.available_qty}</td>
                    <td>
                    <div class="m-1">
                        <button class="btn btn-success my-1 btn-sm edit-book" data-book_id="${book.book_id}">
                            <i class="fa fa-edit"></i>
                        </button>
                        <button class="btn btn-danger my-1 btn-sm deactivate" data-book_id="${book.book_id}">
                            <i class="fa fa-trash"></i>
                        </button>
                        </div>
                        
                    </td>
                </tr>
                `;

                tableBody.innerHTML += row;
            });
        } else {
            tableBody.innerHTML = "<tr><td colspan='7'>No books found</td></tr>";
        }
        createPagination("pagination", resp.totalPages, page, "loadBooks");
    })
    .catch(error => {
        console.error("Error fetching book data:", error);
    });
}



document.addEventListener("DOMContentLoaded", function () {
    // Handle book Edit Modal
    document.body.addEventListener("click", function (event) {
        if (event.target.closest(".edit-book")) {
            let button = event.target.closest(".edit-book");
            loadBookDataUpdate(button.dataset.book_id);

            let updateModal = new bootstrap.Modal(document.getElementById("updateBookDetailsModal"));
            updateModal.show();
        }
    });

    document.body.addEventListener("click", function (event) {
        if (event.target.closest(".deactivate")) {
            let button = event.target.closest(".deactivate");
            deactivateBook(button.dataset.book_id);

           
        }
    });
})

function addBook() {
    let currentYear = new Date().getFullYear();

    document.querySelectorAll('.text-danger').forEach(el => el.innerText = '');

    let isbn = document.getElementById("isbn").value.trim();
    let title = document.getElementById("title").value.trim();
    let category = document.getElementById("category").value;
    let language = document.getElementById("language").value;
    let pub = document.getElementById("pub").value.trim();
    let qty = document.getElementById("qty").value.trim();
    let des = document.getElementById("des").value.trim();
    let coverpage = document.getElementById("coverpage").value;
    let author = document.getElementById("author").value.trim();

    if (isbn === "") {
        document.getElementById("isbn-error").innerText = "ISBN is required.";
    }
    else if (author === "") {
        document.getElementById("author-error").innerText = "Enter a valid author name.";
    }else if (title === "") {
        document.getElementById("title-error").innerText = "Title is required.";
    }else if (category === "") {
        document.getElementById("category-error").innerText = "Select a category.";
    }else if (language === "") {
        document.getElementById("language-error").innerText = "Select a language.";
    }else if (!/^[0-9]{4}$/.test(pub) || pub < 1500 || pub > currentYear) {
        document.getElementById("pub-error").innerText = "Enter a valid published year.";
    }else if (qty === "" || isNaN(qty) || qty <= 0) {
        document.getElementById("qty-error").innerText = "Enter a valid quantity.";
    }else if (des.length < 10) {
        document.getElementById("des-error").innerText = "Description must be at least 10 characters long.";
    }else if (coverpage === "") {
        document.getElementById("coverpage-error").innerText = "Please upload a cover page image.";
    }else{
        let formData = new FormData();

        // Get input values
        formData.append("isbn", document.getElementById("isbn").value);
        formData.append("author", document.getElementById("author").value);
        formData.append("title", document.getElementById("title").value);
        formData.append("category", document.getElementById("category").value);
        formData.append("language", document.getElementById("language").value);
        formData.append("pub", document.getElementById("pub").value);
        formData.append("qty", document.getElementById("qty").value);
        formData.append("des", document.getElementById("des").value);
        
        // Handle file input (Cover Page)
        let coverPage = document.getElementById("coverpage").files[0];
        if (coverPage) {
            formData.append("coverpage", coverPage);
        }
    
        // Send data to controller via AJAX (Fetch API)
        fetch("index.php?action=addBookData", {
            method: "POST",
            body: formData
        })
        .then(response => response.json()) // Convert response to JSON
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
            console.error("Error adding book:", error);
            Swal.fire("Error", "Something went wrong!", "error");
        });
    }




}


function deactivateBook(book_id){
    var formData = new FormData();
    formData.append("book_id", book_id);

    fetch("index.php?action=deactivatebook", {
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
                showAlert("Success", "Failed to deactivate book", "success");

            }
        })
        .catch(error => {
            console.error("Error fetching book data:", error);
        });
}

function loadAllCategories(selectedCategoryId = null) {
    return new Promise((resolve, reject) => {
        fetch('index.php?action=getallcategories')
            .then(response => response.json())
            .then(resp => {
                if (resp.success) {
                    const categoryDropdown = document.getElementById('category');
                    categoryDropdown.innerHTML = '';

                    // Add the "Select Category" option first
                    const defaultOption = document.createElement('option');
                    defaultOption.value = '';
                    defaultOption.textContent = 'Select Category';
                    defaultOption.disabled = true;
                    defaultOption.selected = true;
                    categoryDropdown.appendChild(defaultOption);

                    // Populate categories
                    resp.categories.forEach(category => {
                        const option = document.createElement('option');
                        option.value = category.category_id;
                        option.textContent = category.category_name;

                        if (selectedCategoryId && category.category_id == selectedCategoryId) {
                            option.selected = true;
                        }

                        categoryDropdown.appendChild(option);
                    });

                    resolve(true);
                } else {
                    alert('Failed to load categories. Please try again.');
                    reject('Failed to load categories');
                }
            })
            .catch(error => {
                console.error('Error fetching categories:', error);
                reject(error);
            });
    });
}


function loadLanguages(selectedLanguageId = null) {
    return new Promise((resolve, reject) => {
        fetch('index.php?action=getlanguages')
            .then(response => response.json())
            .then(resp => {
                if (resp.success) {
                    const languageDropdown = document.getElementById('language');
                    languageDropdown.innerHTML = '';

                    const defaultOption = document.createElement('option');
                    defaultOption.value = '';
                    defaultOption.textContent = 'Select Language';
                    defaultOption.disabled = true;
                    defaultOption.selected = true;
                    languageDropdown.appendChild(defaultOption);

                    // Populate languages
                    resp.languages.forEach(language => {
                        const option = document.createElement('option');
                        option.value = language.language_id;
                        option.textContent = language.language_name;

                        if (selectedLanguageId && language.language_id == selectedLanguageId) {
                            option.selected = true;
                        }

                        languageDropdown.appendChild(option);
                    });

                    resolve(true);
                } else {
                    alert('Failed to load languages. Please try again.');
                    reject('Failed to load languages');
                }
            })
            .catch(error => {
                console.error('Error fetching languages:', error);
                reject(error);
            });
    });
}


function loadBookDataUpdate(book_id) {
    const formData = new FormData();
    formData.append('book_id', book_id);

    fetch('index.php?action=loadBookData', {
        method: 'POST',
        body: formData,
    })
        .then(response => response.json())
        .then(resp => {
            if (resp.success) {
                document.getElementById('book_id').value = resp.book_id;
                document.getElementById('isbn_no').value = resp.isbn;
                document.getElementById('author').value = resp.author;
                document.getElementById('title').value = resp.title;
                document.getElementById('pub_year').value = resp.pub_year;
                document.getElementById('qty').value = resp.qty;
                document.getElementById('des').value = resp.description;


                loadAllCategories(resp.category_id)
                    .then(() => {
                        // Categories are now loaded, and the correct category is pre-selected
                    })
                    .catch(error => {
                        console.error('Failed to load languages', error);


                    });

                loadLanguages(resp.language_id)
                    .then(() => {
                        // languages are now loaded, and the correct category is pre-selected
                    })
                    .catch(error => {
                        console.error('Failed to load languages', error);


                    });
            } else {
                showAlert("Error", resp.message, "error");

            }
        })
        .catch(error => {
            console.error('Error fetching book data:', error);
            showAlert("Error", "An error occurred. Please try again.", "error");

        });
}


function showImagePreview() {
    var file = document.getElementById('coverpage').files[0];
    if (file) {
        const reader = new FileReader();
        reader.onload = function (e) {
            document.getElementById('book').src = e.target.result;
        };
        reader.readAsDataURL(file);
    }
} 

function validateForm() {
    let isValid = true;
    let currentYear = new Date().getFullYear();

    document.querySelectorAll('.text-danger').forEach(el => el.innerText = '');

    let isbn = document.getElementById("isbn").value.trim();
    if (isbn === "") {
        document.getElementById("isbn-error").innerText = "ISBN is required.";
        isValid = false;
    }

    let author = document.getElementById("author").value.trim();
    if (author === "") {
        document.getElementById("author-error").innerText = "Enter a valid author name.";
        isValid = false;
    }

    let title = document.getElementById("title").value.trim();
    if (title === "") {
        document.getElementById("title-error").innerText = "Title is required.";
        isValid = false;
    }

    let category = document.getElementById("category").value;
    if (category === "") {
        document.getElementById("category-error").innerText = "Select a category.";
        isValid = false;
    }


    let language = document.getElementById("language").value;
    if (language === "") {
        document.getElementById("language-error").innerText = "Select a language.";
        isValid = false;
    }

    let pub = document.getElementById("pub").value.trim();
    if (!/^[0-9]{4}$/.test(pub) || pub < 1500 || pub > currentYear) {
        document.getElementById("pub-error").innerText = "Enter a valid published year.";
        isValid = false;
    }

    let qty = document.getElementById("qty").value.trim();
    if (qty === "" || isNaN(qty) || qty <= 0) {
        document.getElementById("qty-error").innerText = "Enter a valid quantity.";
        isValid = false;
    }

    let des = document.getElementById("des").value.trim();
    if (des.length < 10) {
        document.getElementById("des-error").innerText = "Description must be at least 10 characters long.";
        isValid = false;
    }

    let coverpage = document.getElementById("coverpage").value;
    if (coverpage === "") {
        document.getElementById("coverpage-error").innerText = "Please upload a cover page image.";
        isValid = false;
    }

    return isValid;
}

function updateBookDetails() {
    var book_id = document.getElementById("book_id").value;
    var isbn = document.getElementById("isbn_no").value;
    var title = document.getElementById("title").value;
    var author = document.getElementById("author").value;
    var category = document.getElementById("category").value;
    var language = document.getElementById("language").value;
    var pubYear = document.getElementById("pub_year").value;
    var quantity = document.getElementById("qty").value;
    var description = document.getElementById("des").value;

    // Clear previous error messages
    clearErrors();

    // Validate inputs
    var isValid = true;

    if (isbn === "") {
        document.getElementById("isbn_error").textContent = "ISBN is required.";
        isValid = false;
    }

    if (title === "") {
        document.getElementById("title_error").textContent = "Book Title is required.";
        isValid = false;
    }

    if (author === "") {
        document.getElementById("author_error").textContent = "Author Name is required.";
        isValid = false;
    }

    if (category === "") {
        document.getElementById("category_error").textContent = "Book Category is required.";
        isValid = false;
    }

    if (language === "") {
        document.getElementById("language_error").textContent = "Language is required.";
        isValid = false;
    }

    if (pubYear === "") {
        document.getElementById("pub_year_error").textContent = "Published Year is required.";
        isValid = false;
    }

    if (quantity === "" || quantity <= 0) {
        document.getElementById("qty_error").textContent = "Quantity must be greater than 0.";
        isValid = false;
    }

    if (description === "") {
        document.getElementById("des_error").textContent = "Description is required.";
        isValid = false;
    }

    if (isValid) {
        var formData = new FormData();
        formData.append("book_id", book_id);
        formData.append("isbn", isbn);
        formData.append("title", title);
        formData.append("author", author);
        formData.append("category_id", category);
        formData.append("language_id", language);
        formData.append("pub_year", pubYear);
        formData.append("quantity", quantity);
        formData.append("description", description);

        fetch("index.php?action=updateBook", {
            method: "POST",
            body: formData,
        })
            .then(response => response.json())
            .then(resp => {
                if (resp.success) {
                    showAlert("Success", resp.message, "success").then(() => {
                        location.reload();
                    });                } else {
                    showAlert("Error", resp.message, "error");

                }
            })
            .catch(error => {
                console.error("Error fetching book data:", error);
                showAlert("Error", "An error occurred. Please try again.", "error");

            });
    }
}


function clearErrors() {
    document.getElementById("isbn_error").textContent = "";
    document.getElementById("title_error").textContent = "";
    document.getElementById("author_error").textContent = "";
    document.getElementById("category_error").textContent = "";
    document.getElementById("language_error").textContent = "";
    document.getElementById("pub_year_error").textContent = "";
    document.getElementById("qty_error").textContent = "";
    document.getElementById("des_error").textContent = "";
}

function addCategory() {
    var category = document.getElementById("category").value.trim();
    var categoryInput = document.getElementById("category");

    if (category === "") {
        alert("Category name is required!");
        return;
    }

    var formData = new FormData();
    formData.append("category", category);

    fetch("index.php?action=addCategoryData", {
        method: "POST",
        body: formData,
    })
        .then(response => response.json())
        .then(resp => {
            if (resp.success) {
                showAlert("Success", resp.message, "success");

                categoryInput.value = "";
            } else {
                showAlert("Error", resp.message , "error");

            }
        })
        .catch(error => {
            console.error("Error:", error);
            showAlert("Error", "An error occurred. Please try again.", "error");

        });
}
