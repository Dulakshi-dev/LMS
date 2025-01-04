function addBook() {

    //validate book details

    return true;
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
