
function loadBooks(page = 1, status = "Active", language = null, category = null) {
    if (!language) {
        language = document.getElementById("language1").value.trim();
    }
    if (!category) {
        category = document.getElementById("category1").value.trim();
    }
    var bookid = document.getElementById("bookid").value.trim();
    var title = document.getElementById("bname").value.trim();
    var isbn = document.getElementById("isbn").value.trim();

    let formData = new FormData();
    formData.append("page", page);
    formData.append("bookid", bookid);
    formData.append("title", title);
    formData.append("isbn", isbn);
    formData.append("status", status);
    formData.append("language", language);
    formData.append("category", category);

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

                    let actionButtons = "";

                    if (status === "Active") {
                        // Show Edit, Email, and Deactivate buttons for active users
                        actionButtons = `
                           <button class="btn btn-success my-1 btn-sm edit-book" data-book_id="${book.book_id}">
                            <i class="fa fa-edit"></i>
                        </button>
                        <button class="btn btn-danger my-1 btn-sm deactivate" data-book_id="${book.book_id}">
                            <i class="fa fa-trash"></i>
                        </button>
                        `;
                    } else {
                        // Show Activate button for deactivated users
                        actionButtons = `
                            <button class="btn btn-success my-1 btn-sm activate" data-book_id="${book.book_id}">
                                <i class="fa fa-plus"></i>
                            </button>
                        `;
                    }

                    let coverImageUrl = `index.php?action=serveimage&image=${encodeURIComponent(book.cover_page)}`;

                    let row = `
                <tr>
                    <td>${book.book_id}</td>
                    <td>${book.isbn}</td>
                    <td class="book-cover"><img src="${coverImageUrl}" alt="Book Cover" style="width: 50px; height: 75px; object-fit: cover;"></td>
                    <td>${book.title}</td>
                    <td>${book.author}</td>
                    <td>${book.pub_year}</td>
                    <td>${book.category_name}</td>
                    <td>${book.language_name}</td>
                    <td>${book.qty}</td>
                    <td>${book.qty - book.available_qty}</td>
                    <td>
                     <div class="action-buttons m-1">
                                    ${actionButtons}
                                </div>
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

function loadCategory() {


    fetch("index.php?action=loadcategories", {
        method: "POST",
    })
        .then(response => response.json())
        .then(resp => {
            let tableBody = document.getElementById("categoryTableBody");
            tableBody.innerHTML = "";

            if (resp.success && resp.categories.length > 0) {
                resp.categories.forEach(category => {
                    let row = `
                    <tr>
                        <td>${category.category_name}</td>
                        <td>${category.book_count}</td>
                        <td>
                         <div class="m-1">
                            <button class="btn btn-danger my-1 btn-sm delete-category"
                                data-category_id="${category.category_id}">
                                <i class="fa fa-trash"></i>
                            </button>
                        </div>`;


                    tableBody.innerHTML += row;
                });
            } else {
                tableBody.innerHTML = "<tr><td colspan='10'>No issued books found</td></tr>";
            }
        })
        .catch(error => {
            console.error("Error fetching book data:", error);
        });
}

document.addEventListener("DOMContentLoaded", function () {
    loadAllCategories(null, 'category1');
    loadLanguages(null, 'language1');

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

    document.body.addEventListener("click", function (event) {
        if (event.target.closest(".activate")) {
            let button = event.target.closest(".activate");
            activateBook(button.dataset.book_id);


        }
    });

    document.body.addEventListener("click", function (event) {
        if (event.target.closest(".delete-category")) {
            let button = event.target.closest(".delete-category");
            deleteCategory(button.dataset.category_id);


        }
    });
})

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

    let category = document.getElementById("category1").value;
    if (category === "") {
        document.getElementById("category-error").innerText = "Select a category.";
        isValid = false;
    }


    let language = document.getElementById("language1").value;
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

function addBook() {

    validateForm();
    if (validateForm()) {
        let formData = new FormData();

        // Get input values
        formData.append("isbn", document.getElementById("isbn").value);
        formData.append("author", document.getElementById("author").value);
        formData.append("title", document.getElementById("title").value);
        formData.append("category", document.getElementById("category1").value);
        formData.append("language", document.getElementById("language1").value);
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

function deactivateBook(book_id) {
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

function activateBook(book_id) {
    var formData = new FormData();
    formData.append("book_id", book_id);

    fetch("index.php?action=activatebook", {
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
                showAlert("Success", "Failed to activate book", "success");

            }
        })
        .catch(error => {
            console.error("Error fetching book data:", error);
        });
}

function deleteCategory(category_id) {
    alert(category_id);
    var formData = new FormData();
    formData.append("category_id", category_id);

    fetch("index.php?action=deletecategory", {
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
                showAlert("Success", "Failed to delete the category", "success");

            }
        })
        .catch(error => {
            console.error("Error fetching book data:", error);
        });
}

function loadAllCategories(selectedCategoryId = null, dropdownId = 'category1') {
    return new Promise((resolve, reject) => {
        fetch('index.php?action=getallcategories')
            .then(response => response.json())
            .then(resp => {
                if (resp.success) {
                    const categoryDropdown = document.getElementById(dropdownId);
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

                        // If a selectedCategoryId is passed, set the corresponding category as selected
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

function loadLanguages(selectedLanguageId = null, dropdownId = 'language1') {
    return new Promise((resolve, reject) => {
        fetch('index.php?action=getlanguages')
            .then(response => response.json())
            .then(resp => {
                if (resp.success) {
                    const languageDropdown = document.getElementById(dropdownId);
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


                loadAllCategories(resp.category_id, 'category2')
                    .then(() => {
                        // Categories are now loaded, and the correct category is pre-selected
                    })
                    .catch(error => {
                        console.error('Failed to load languages', error);


                    });

                loadLanguages(resp.language_id, 'language2')
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
                    });
                } else {
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
                showAlert("Success", resp.message, "success").then(() => {
                    location.reload();
                });
            } else {
                showAlert("Error", resp.message, "error");

            }
        })
        .catch(error => {
            console.error("Error:", error);
            showAlert("Error", "An error occurred. Please try again.", "error");

        });
}

function showAlert(title, message, type) {
    return Swal.fire({
        title: title,
        text: message,
        icon: type, // 'success', 'error', 'warning', 'info', 'question'
        confirmButtonText: 'OK'
    });
}

function generateActiveBookReport() {
    const table = document.getElementById("bookTable");
    const clonedTable = table.cloneNode(true);

    // Step 1: Identify the "Book Cover" column index
    const headerRow = clonedTable.querySelector("thead tr");
    let coverColumnIndex = -1;

    for (let i = 0; i < headerRow.children.length; i++) {
        if (headerRow.children[i].textContent.trim().toLowerCase() === "cover page") {
            coverColumnIndex = i;
            break;
        }
    }

    // Step 2: Remove "Cover Page" header column
    if (coverColumnIndex !== -1) {
        headerRow.deleteCell(coverColumnIndex);

        // Step 3: Remove "Cover Page" cell from each row
        clonedTable.querySelectorAll("tbody tr").forEach(row => {
            if (row.children.length > coverColumnIndex) {
                row.deleteCell(coverColumnIndex);
            }
        });
    }

    // Step 4: Remove the last column (Actions)
    const totalColumns = headerRow.children.length;
    headerRow.deleteCell(totalColumns - 1);
    clonedTable.querySelectorAll("tbody tr").forEach(row => {
        if (row.children.length === totalColumns) {
            row.deleteCell(totalColumns - 1);
        }
    });

    // Step 5: Generate report
    const tableHTML = clonedTable.outerHTML;
    let formData = new FormData();
    formData.append("table_html", tableHTML);
    formData.append("title", "Active Book Report");
    formData.append("filename", "active_book_report.pdf");

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

function generateDeactiveBookReport() {
    const table = document.getElementById("bookTable");
    const clonedTable = table.cloneNode(true);

    // Step 1: Identify the "Book Cover" column index
    const headerRow = clonedTable.querySelector("thead tr");
    let coverColumnIndex = -1;

    for (let i = 0; i < headerRow.children.length; i++) {
        if (headerRow.children[i].textContent.trim().toLowerCase() === "cover page") {
            coverColumnIndex = i;
            break;
        }
    }

    // Step 2: Remove "Cover Page" header column
    if (coverColumnIndex !== -1) {
        headerRow.deleteCell(coverColumnIndex);

        // Step 3: Remove "Cover Page" cell from each row
        clonedTable.querySelectorAll("tbody tr").forEach(row => {
            if (row.children.length > coverColumnIndex) {
                row.deleteCell(coverColumnIndex);
            }
        });
    }

    // Step 4: Remove the last column (Actions)
    const totalColumns = headerRow.children.length;
    headerRow.deleteCell(totalColumns - 1);
    clonedTable.querySelectorAll("tbody tr").forEach(row => {
        if (row.children.length === totalColumns) {
            row.deleteCell(totalColumns - 1);
        }
    });

    // Step 5: Generate report
    const tableHTML = clonedTable.outerHTML;
    let formData = new FormData();
    formData.append("table_html", tableHTML);
    formData.append("title", "Deactive Book Report");
    formData.append("filename", "deactive_book_report.pdf");

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
