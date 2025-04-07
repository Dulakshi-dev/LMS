function viewallbooks() {
  loadAllBooks();
  loadLanguages();
  loadAllCategories();

  document.getElementById("title").addEventListener("input", function () {
    loadAllBooks(1);
  });

  document.getElementById("language").addEventListener("change", function () {
    loadAllBooks(1);
  });

  document.getElementById("category").addEventListener("change", function () {
    loadAllBooks(1);
  });
}

function loadAllBooks(page = 1, language = null, category = null) {
  if (!language) {
    language = document.getElementById("language").value.trim();
  }
  if (!category) {
    category = document.getElementById("category").value.trim();
  }
  title = document.getElementById("title").value.trim();

  let formData = new FormData();
  formData.append("page", page);
  formData.append("title", title);
  formData.append("language", language);
  formData.append("category", category);

  fetch("index.php?action=getallbooks", {
    method: "POST",
    body: formData,
  })
    .then((response) => response.json())
    .then((resp) => {
      let body = document.getElementById("bookBody");
      body.innerHTML = "";

      if (resp.success && resp.books.length > 0) {
        resp.books.forEach((book) => {
          let coverImageUrl = `index.php?action=serveimage&image=${encodeURIComponent(
            book.cover_page
          )}`;
          let availability =
            book.available_qty > 0 ? "Available" : "Not Available";

          let row = `
                <div class="col-md-3 col-sm-6">
                  <div class="book-card">
                    <div class="book-image">
                      <img src="${coverImageUrl}" alt="Book Cover">
                    </div>
                    <div class="p-3 d-flex justify-content-between align-items-center">
                      <div class="text-start">
                        <div class="book-title">${book.title}</div>
                        <div>${book.author}</div>
                      </div>
                      <button class="btn btn-sm view-details" id="success"
                        data-title="${book.title}"
                        data-author="${book.author}"
                        data-id="${book.book_id}"
                        data-description="${book.description}"
                        data-availability="${availability}">
                        View Details
                      </button>
                    </div>
                  </div>
                </div>
                `;

          body.innerHTML += row;
        });
      } else {
        body.innerHTML = "<p>No books found</p>";
      }

      createPagination("pagination", resp.totalPages, page, (newPage) => {
        loadAllBooks(newPage, language, category);
      });
    })
    .catch((error) => {
      console.error("Error fetching book data:", error);
    });
}

function loadDashboardBooks() {
  fetch("index.php?action=loaddashboardbooks", {
    method: "POST",
  })
    .then((response) => response.json())
    .then((resp) => {
      if (!resp.success || !resp.books) {
        console.error("Error: No books data received");
        return;
      }

      // Get containers for each category
      let recommendedContainer = document.getElementById("recommendedBooks");
      let latestContainer = document.getElementById("latestBooks");
      let topContainer = document.getElementById("topBooks");

      // Clear previous content
      recommendedContainer.innerHTML = "";
      latestContainer.innerHTML = "";
      topContainer.innerHTML = "";

      // Function to generate book cards
      function generateBookCard(book) {
        let coverImageUrl = `index.php?action=serveimage&image=${encodeURIComponent(
          book.cover_page
        )}`;
        let availability =
          book.available_qty > 0 ? "Available" : "Not Available";

        return `
                <div class="col-md-6  col-lg-3">
                  <div class="book-card">
                    <div class="book-image">
                      <img src="${coverImageUrl}" alt="Book Cover">
                    </div>
                    <div class="p-3 d-block d-md-flex justify-content-between align-items-center">
                      <div class="text-start">
                        <div class="book-title">${book.title}</div>
                        <div>${book.author}</div>
                      </div>
                      <div class="text-end">
                      <button class="btn btn-sm view-details" id="success"
                        data-title="${book.title}"
                        data-author="${book.author}"
                        data-id="${book.book_id}"
                        data-description="${book.description}"
                        data-availability="${availability}">
                        View Details
                      </button>
                      </div>
                      
                    </div>
                  </div>
                </div>
            `;
      }

      // Populate Recommended Books
      if (resp.books.recommended.length > 0) {
        resp.books.recommended.forEach((book) => {
          recommendedContainer.innerHTML += generateBookCard(book);
        });
      } else {
        recommendedContainer.innerHTML =
          "<p>No recommended books available</p>";
      }

      // Populate Latest Arrivals
      if (resp.books.latest.length > 0) {
        resp.books.latest.forEach((book) => {
          latestContainer.innerHTML += generateBookCard(book);
        });
      } else {
        latestContainer.innerHTML = "<p>No latest books available</p>";
      }

      // Populate Top Books
      if (resp.books.top.length > 0) {
        resp.books.top.forEach((book) => {
          topContainer.innerHTML += generateBookCard(book);
        });
      } else {
        topContainer.innerHTML = "<p>No top books available</p>";
      }
    })
    .catch((error) => {
      console.error("Error fetching book data:", error);
    });
}

document.addEventListener("click", function (event) {
  if (event.target.classList.contains("view-details")) {
    const button = event.target;
    const title = button.getAttribute("data-title");
    const author = button.getAttribute("data-author");
    const id = button.getAttribute("data-id");
    const description = button.getAttribute("data-description");
    const imgElement = button.closest(".book-card")?.querySelector("img");
    const coverImage = imgElement
      ? imgElement.getAttribute("src")
      : "default-cover.jpg";
    const availability = button.getAttribute("data-availability");

    document.getElementById("book-title").textContent = title;
    document.getElementById("book-author").textContent = author;
    document.getElementById("book-id").textContent = id;
    document.getElementById("book-description").textContent = description;
    document.getElementById("book-availability").textContent = availability;

    const reserveBtn = document.getElementById("reserve-btn");
    const saveBtn = document.getElementById("save-btn");
    const availabilityText = document.getElementById("book-availability");

    if (availability === "Not Available") {
      availabilityText.style.color = "#F08080"; // Light Coral
    } else {
      availabilityText.style.color = "#98FF98"; // Mint Green
    }

    const offcanvasImage = document.querySelector(
      ".offcanvas-body .book-details img"
    );
    offcanvasImage.setAttribute("src", coverImage);
    offcanvasImage.style.width = "150px";
    offcanvasImage.style.height = "200px";
    offcanvasImage.style.objectFit = "cover";

    if (reserveBtn) {
      reserveBtn.onclick = function () {
        reserve(id, availability);
      };
    }
    if (saveBtn) {
      saveBtn.onclick = function () {
        save(id);

        //window.location.href = "index.php?action=save&book_id=" + id ;
      };
    }

    // Open Offcanvas
    let bookDetailsCanvas = document.getElementById("bookDetailsCanvas");
    let offcanvasInstance =
      bootstrap.Offcanvas.getOrCreateInstance(bookDetailsCanvas);
    offcanvasInstance.show();
  }
});

function reserve(book_id, availability) {
  var formData = new FormData();
  formData.append("book_id", book_id);
  formData.append("availability", availability);

  // Send a POST request to the backend
  fetch("index.php?action=reserve", {
    method: "POST",
    body: formData,
  })
    .then((response) => response.json()) // Convert response to JSON
    .then((resp) => {
      if (resp.success) {
        showAlert("Success", resp.message, "success");
      } else {
        showAlert("Error", resp.message, "error");
      }
    })
    .catch((error) => {
      console.error("Error reserving book:", error); // Log error to the console
      showAlert("Error", "Something went wrong. Please try again", "error");
    });
}

function save(book_id) {
  var formData = new FormData();
  formData.append("book_id", book_id);

  // Send a POST request to the backend
  fetch("index.php?action=save", {
    method: "POST",
    body: formData,
  })
    .then((response) => response.json()) // Convert response to JSON
    .then((resp) => {
      if (resp.success) {
        showAlert("Success", resp.message, "success");
      } else {
        showAlert("Error", resp.message, "error");
      }
    })
    .catch((error) => {
      console.error("Error saving the book:", error); // Log error to the console
      showAlert("Error", "Something went wrong. Please try again", "error");
    });
}

function showAlert(title, message, type) {
  return Swal.fire({
    title: title,
    text: message,
    icon: type, // 'success', 'error', 'warning', 'info', 'question'
    confirmButtonText: "OK",
  });
}

function loadAllCategories(selectedCategoryId = null) {
  return new Promise((resolve, reject) => {
    fetch("index.php?action=getallcategories")
      .then((response) => response.json())
      .then((resp) => {
        if (resp.success) {
          const categoryDropdown = document.getElementById("category");
          categoryDropdown.innerHTML = "";

          // Add the "Select Category" option first
          const defaultOption = document.createElement("option");
          defaultOption.value = "";
          defaultOption.textContent = "Select Category";
          defaultOption.selected = true;
          categoryDropdown.appendChild(defaultOption);

          // Populate categories
          resp.categories.forEach((category) => {
            const option = document.createElement("option");
            option.value = category.category_id;
            option.textContent = category.category_name;

            if (
              selectedCategoryId &&
              category.category_id == selectedCategoryId
            ) {
              option.selected = true;
            }

            categoryDropdown.appendChild(option);
          });

          resolve(true);
        } else {
          alert("Failed to load categories. Please try again.");
          reject("Failed to load categories");
        }
      })
      .catch((error) => {
        console.error("Error fetching categories:", error);
        reject(error);
      });
  });
}

function loadLanguages(selectedLanguageId = null) {
  return new Promise((resolve, reject) => {
    fetch("index.php?action=getlanguages")
      .then((response) => response.json())
      .then((resp) => {
        if (resp.success) {
          const languageDropdown = document.getElementById("language");
          languageDropdown.innerHTML = "";

          const defaultOption = document.createElement("option");
          defaultOption.value = "";
          defaultOption.textContent = "Select Language";
          defaultOption.selected = true;
          languageDropdown.appendChild(defaultOption);

          // Populate languages
          resp.languages.forEach((language) => {
            const option = document.createElement("option");
            option.value = language.language_id;
            option.textContent = language.language_name;

            if (
              selectedLanguageId &&
              language.language_id == selectedLanguageId
            ) {
              option.selected = true;
            }

            languageDropdown.appendChild(option);
          });

          resolve(true);
        } else {
          alert("Failed to load languages. Please try again.");
          reject("Failed to load languages");
        }
      })
      .catch((error) => {
        console.error("Error fetching languages:", error);
        reject(error);
      });
  });
}

function searchBook() {
  title = document.getElementById("title").value.trim();

  if (title === "") {
    document.getElementById("searchResults").classList.add("d-none");
  } else {
    document.getElementById("searchResults").classList.remove("d-none");
  }
  var formData = new FormData();
  formData.append("title", title);

  // Send a POST request to the backend
  fetch("index.php?action=searchbook", {
    method: "POST",
    body: formData,
  })
    .then((response) => response.json()) // Convert response to JSON
    .then((resp) => {
      let body = document.getElementById("searchBody");
      body.innerHTML = "";
      if (resp.success && resp.books.length > 0) {
        resp.books.forEach((book) => {
          let coverImageUrl = `index.php?action=serveimage&image=${encodeURIComponent(
            book.cover_page
          )}`;
          let availability =
            book.available_qty > 0 ? "Available" : "Not Available";

          let row = `
                <div class="col-md-3 col-sm-6">
                  <div class="book-card">
                    <div class="book-image">
                      <img src="${coverImageUrl}" alt="Book Cover">
                    </div>
                    <div class="p-3 d-flex justify-content-between align-items-center">
                      <div class="text-start">
                        <div class="book-title">${book.title}</div>
                        <div>${book.author}</div>
                      </div>
                      <button class="btn btn-sm view-details" id="success"
                        data-title="${book.title}"
                        data-author="${book.author}"
                        data-id="${book.book_id}"
                        data-description="${book.description}"
                        data-availability="${availability}">
                        View Details
                      </button>
                    </div>
                  </div>
                </div>
                `;

          body.innerHTML += row;
        });
      } else {
        body.innerHTML = "<p>No books found</p>";
      }
    })
    .catch((error) => {
      console.error("Error saving the book:", error); // Log error to the console
      showAlert("Error", "Something went wrong. Please try again", "error");
    });
}
