
function loadOpeningHours() {
    fetch("index.php?action=getopeninghours", {
        method: "POST",
    })
        .then(response => response.json())
        .then(resp => {
            if (resp.success) {
                resp.data.forEach(item => {
                    // Check if open_time or close_time is 00:00:00 and set to "Closed"
                    if (item.day_label === "Weekday") {
                        document.getElementById("weekdayfrom").textContent = item.open_time === "00:00:00" ? "Closed" : item.open_time;
                        document.getElementById("weekdayto").textContent = item.close_time === "00:00:00" ? "Closed" : item.close_time;
                    } else if (item.day_label === "Weekend") {
                        document.getElementById("weekendfrom").textContent = item.open_time === "00:00:00" ? "Closed" : item.open_time;
                        document.getElementById("weekendto").textContent = item.close_time === "00:00:00" ? "Closed" : item.close_time;
                    } else if (item.day_label === "Holiday") {
                        document.getElementById("holidayfrom").textContent = item.open_time === "00:00:00" ? "Closed" : item.open_time;
                        document.getElementById("holidayto").textContent = item.close_time === "00:00:00" ? "Closed" : item.close_time;
                    }
                });
            } else {
                showAlert("Error", resp.message, "error");
            }
        })
        .catch(error => {
            showAlert("Error", "Something went wrong", "error");
            console.error("Error fetching opening hours:", error);
        });
}

function loadNewsUpdates() {
    // Function to fetch and display the latest news

    fetch("index.php?action=getnewsupdates", {
        method: "POST",
    })
        .then(response => response.json()) // Parse JSON response
        .then(resp => {
            const newsContainer = document.getElementById('news-container');
            newsContainer.innerHTML = ''; // Clear existing content

            if (resp.success && resp.newsData.results.length > 0) {
                resp.newsData.results.forEach(news => {
                    let image = `index.php?action=servenewsimage&image=${encodeURIComponent(news.image_path)}`;


                    const newsCard = document.createElement('div');
                    newsCard.classList.add('col-md-4', 'px-4');

                    newsCard.innerHTML = `
                        <div class="card border-0 shadow-lg overflow-hidden book">
                            <img src="${image}" class="card-img-top" style="height: 350px; object-fit: cover;" alt="News Image">
                            <div class="card-body text-center">
                             <p class="card-text">${news.date}</p>
                                <h5 class="card-title text-warning">${news.title}</h5>
                               
                                <a href="#" class="btn btn-warning text-dark fw-bold px-4 py-2 rounded-pill shadow-sm" 
                                    data-bs-toggle="modal" data-bs-target="#newsModal" 
                                    onclick="showNewsModal('${image}','${news.date}','${news.title}','${news.description}')">
                                    Read More
                                </a>
                            </div>
                        </div>
                    `;

                    newsContainer.appendChild(newsCard); // Add the card to the container

                });
            }
        })
        .catch(error => console.error('Error fetching news:', error)); // Handle errors
}

function showNewsModal(imageUrl, date, title, description) {
    document.getElementById("news-image").src = imageUrl;
    document.getElementById("date").innerHTML = date;
    document.getElementById("title").innerHTML = title;
    document.getElementById("description").innerHTML = description;

}

function loadLibraryInfo() {
    fetch("index.php?action=getlibraryinfo", {
        method: "POST",
    })
        .then(response => response.json())
        .then(resp => {
            if (resp.success && resp.libraryData) {
                let data = resp.libraryData;

                document.getElementById("address").innerHTML = data.address;
                document.getElementById("phone").innerHTML = data.mobile;
                document.getElementById("email").innerHTML = data.email;

            } else {
                showAlert("Error", resp.message || "No information found", "error");
            }
        })
        .catch(error => {
            showAlert("Error", "Something went wrong", "error");
            console.error("Error fetching library info:", error);
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

function sendContactMail() {
    var name = document.getElementById("name").value.trim();
    var email = document.getElementById("emailadd").value.trim();
    var msg = document.getElementById("message").value.trim();

    let formData = new FormData();
    formData.append("name", name);
    formData.append("email", email);
    formData.append("msg", msg);

    fetch("index.php?action=contactlibrary", {
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
                showAlert("Error", "Failed to Send Your Massage", "error");
            }
        })
        .catch(error => {
            console.error("Error fetching user data:", error);
        });
}

function loadTopBooks() {

    fetch("index.php?action=gettopbooks", {
        method: "POST",
    })
        .then(response => response.json()) 
        .then(resp => {

            let body = document.getElementById("topBookBody");
            body.innerHTML = "";

            if (resp.success && resp.books.length > 0) {
                resp.books.forEach(book => {
                    let coverImageUrl = `index.php?action=serveimage&image=${encodeURIComponent(book.cover_page)}`;

                    let row = `
              

                <div class="col-md-3 mb-4">
                <div class="card">
                    <img src="${coverImageUrl}" class="card-img-top" alt="Book 5">
                    <div class="card-body">
                        <h5 class="card-title">${book.title}</h5>
                        <p class="card-text">${book.author}</p>
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
        .catch(error => {
            console.error("Error fetching book data:", error);
        });
}