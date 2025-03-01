function validateForm() {
    let isValid = true;
    let currentYear = new Date().getFullYear();

    // Clear previous errors
    document.querySelectorAll('.text-danger').forEach(el => el.innerText = '');

    // ISBN validation (10 or 13 digits)
    let isbn = document.getElementById("isbn").value.trim();
    if (!/^\d{10}$|^\d{13}$/.test(isbn)) {
        document.getElementById("isbn-error").innerText = "ISBN must be 10 or 13 digits.";
        isValid = false;
    }

    // Author Name validation (only letters, spaces, hyphens, apostrophes)
    let author = document.getElementById("author").value.trim();
    if (!/^[A-Za-z\s'-]+$/.test(author) || author === "") {
        document.getElementById("author-error").innerText = "Enter a valid author name.";
        isValid = false;
    }

    // Title validation (required)
    let title = document.getElementById("title").value.trim();
    if (title === "") {
        document.getElementById("title-error").innerText = "Title is required.";
        isValid = false;
    }

    // Category validation (required)
    let category = document.getElementById("category").value;
    if (category === "") {
        document.getElementById("category-error").innerText = "Select a category.";
        isValid = false;
    }

    // Language validation (required)
    let language = document.getElementById("language").value;
    if (language === "") {
        document.getElementById("language-error").innerText = "Select a language.";
        isValid = false;
    }

    // Published Year validation (4-digit year between 1500 and current year)
    let pub = document.getElementById("pub").value.trim();
    if (!/^\d{4}$/.test(pub) || pub < 1500 || pub > currentYear) {
        document.getElementById("pub-error").innerText = "Enter a valid year.";
        isValid = false;
    }

    // Quantity validation (must be positive number)
    let qty = document.getElementById("qty").value.trim();
    if (isNaN(qty) || qty <= 0) {
        document.getElementById("qty-error").innerText = "Enter a valid quantity.";
        isValid = false;
    }

    // Description validation (min 10 characters)
    let des = document.getElementById("des").value.trim();
    if (des.length < 10) {
        document.getElementById("des-error").innerText = "enter Description.";
        isValid = false;
    }

    // Cover page validation (must be an image file)
    let coverpage = document.getElementById("coverpage").files[0];
    if (!coverpage) {
        document.getElementById("coverpage-error").innerText = "Please upload a cover page.";
        isValid = false;
    } else {
        let allowedTypes = ['image/jpeg', 'image/png', 'image/gif'];
        if (!allowedTypes.includes(coverpage.type)) {
            document.getElementById("coverpage-error").innerText = "Only JPG, PNG, or GIF files are allowed.";
            isValid = false;
        }
    }

    return isValid;
}
function loadAllCategories(selectedCategoryId = null) {
    return new Promise((resolve, reject) => {
        fetch('index.php?action=getallcategories')
            .then(response => response.json())
            .then(resp => {
                if (resp.success) {
                    const categoryDropdown = document.getElementById('category');
                    categoryDropdown.innerHTML = '';

                    resp.categories.forEach(category => {
                        const option = document.createElement('option');
                        option.value = category.category_id;
                        option.textContent = category.category_name;

                        if (selectedCategoryId && category.category_id == selectedCategoryId) {
                            option.selected = true;
                        }

                        categoryDropdown.appendChild(option);
                    });

                    if (selectedCategoryId) {
                        categoryDropdown.value = selectedCategoryId;
                    }

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

                    resp.languages.forEach(language => {
                        const option = document.createElement('option');
                        option.value = language.language_id;
                        option.textContent = language.language_name;

                        if (selectedLanguageId && language.language_id == selectedLanguageId) {
                            option.selected = true;
                        }

                        languageDropdown.appendChild(option);
                    });

                    if (selectedLanguageId) {
                        languageDropdown.value = selectedLanguageId;
                    }

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
                        alert('Failed to load categories. Please try again.');
                    });

                loadLanguages(resp.language_id)
                    .then(() => {
                        // languages are now loaded, and the correct category is pre-selected
                    })
                    .catch(error => {
                        alert('Failed to load languages. Please try again.');
                    });
            } else {
                alert('Failed to load book data. Please try again.');
            }
        })
        .catch(error => {
            console.error('Error fetching book data:', error);
        });
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

    //validate book details

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
                location.reload();
            } else {
                alert("Failed to update user data. Please try again.");
            }
        })
        .catch(error => {
            console.error("Error fetching user data:", error);
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