document.addEventListener("DOMContentLoaded", function () {
    loadCounts();
    loadTopBooks();
    fetch("index.php?action=getchartdata")
        .then(response => response.json())
        .then(data => {
            // Month names in chronological order for conversion
            const monthNames = ['January', 'February', 'March', 'April', 'May', 'June', 'July', 'August', 'September', 'October', 'November', 'December'];

            // Initialize the data arrays for user registrations and books issued
            let userRegistrationsValues = new Array(12).fill(0);  // Defaulting to 0
            let booksIssuedValues = new Array(12).fill(0);  // Defaulting to 0
            const labels = monthNames;

            // Process user registrations data
            data.userRegistrations.forEach(row => {
                const monthIndex = monthNames.indexOf(row.month);  // Get the index of the month
                if (monthIndex !== -1) {
                    userRegistrationsValues[monthIndex] = parseInt(row.total);  // Convert total to number
                }
            });

            // Process books issued data
            data.booksIssued.forEach(row => {
                const monthIndex = monthNames.indexOf(row.month);  // Get the index of the month
                if (monthIndex !== -1) {
                    booksIssuedValues[monthIndex] = parseInt(row.total);  // Convert total to number
                }
            });

            // Bar Chart for User Registrations per Month
            const ctx1 = document.getElementById('barChart').getContext('2d');
            new Chart(ctx1, {
                type: 'bar',
                data: {
                    labels: labels,  // Ensure month names are correctly used here
                    datasets: [{
                        label: 'User Registrations per Month',
                        data: userRegistrationsValues,  // Use the mapped values for each month
                        backgroundColor: 'rgba(2, 141, 176, 0.6)',
                        borderColor: 'rgba(0, 58, 79, 0.59)',
                        borderWidth: 1
                    }]
                },
                options: {
                    responsive: true,
                    scales: {
                        y: {
                            beginAtZero: true
                        }
                    }
                }
            });

            // Line Chart for Books Issued per Month
            const ctx2 = document.getElementById('lineChart').getContext('2d');
            new Chart(ctx2, {
                type: 'line',
                data: {
                    labels: labels,  // Same month labels
                    datasets: [{
                        label: 'Books Issued per Month',
                        data: booksIssuedValues,  // Use the mapped values for each month
                        backgroundColor: 'rgba(115, 187, 84, 0.46)',
                        borderColor: 'rgba(72, 150, 33, 0.63)',
                        borderWidth: 2,
                        tension: 0.4
                    }]
                },
                options: {
                    responsive: true,
                    scales: {
                        y: {
                            beginAtZero: true
                        }
                    }
                }
            });

            // Pie Chart for Books Issued by Category
            const ctx3 = document.getElementById('pieChart').getContext('2d');
            const borrowCategoryLabels = data.borrowCategory.map(row => row.category_name);  // Extract category names
            const borrowCategoryValues = data.borrowCategory.map(row => row.total);  // Extract total values

            new Chart(ctx3, {
                type: 'doughnut', // Pie chart type
                data: {
                    labels: borrowCategoryLabels,  // Category names as labels
                    datasets: [{
                        label: 'Books Issued by Category',
                        data: borrowCategoryValues,  // The total number of books issued in each category
                        backgroundColor: [
                            'rgba(75, 192, 192, 0.6)',
                            'rgba(153, 102, 255, 0.6)',
                            'rgba(255, 159, 64, 0.6)',
                            'rgba(255, 4, 4, 0.6)',
                            'rgba(255, 99, 132, 0.6)',

                            // Add more colors as needed for additional categories
                        ],
                        borderColor: [
                            'rgba(75, 192, 192, 1)',
                            'rgba(153, 102, 255, 1)',
                            'rgba(255, 159, 64, 1)',
                            'rgb(255, 50, 50)',
                            'rgba(255, 99, 132, 1)',

                            // Corresponding border colors
                        ],
                        borderWidth: 1  // Border width for each slice
                    }]
                },
                options: {
                    responsive: true, // Make it responsive
                    plugins: {
                        legend: {
                            position: 'top',  // Legend position on the chart
                        },
                        tooltip: {
                            enabled: true,  // Enable tooltips for better user interaction
                        }
                    }
                }
            });

            var ctx4 = document.getElementById('polarChart').getContext('2d');
            const memberStatusLabels = data.memberstatus.map(row => row.status);  // Extract category names
            const memberStatusyValues = data.memberstatus.map(row => row.total);  // Extract total values

            new Chart(ctx4, {
                type: 'polarArea', // Pie chart type
                data: {
                    labels: memberStatusLabels,  // Category names as labels
                    datasets: [{
                        label: 'Members By Status',
                        data: memberStatusyValues,  // The total number of books issued in each category
                        backgroundColor: [
                            'rgba(255, 159, 64, 0.6)',
                            'rgba(255, 4, 4, 0.6)',
                            'rgba(255, 99, 132, 0.6)',
                            'rgba(75, 192, 192, 0.6)',
                            'rgba(153, 102, 255, 0.6)',

                            // Add more colors as needed for additional categories
                        ],
                        borderColor: [
                            'rgba(255, 159, 64, 1)',
                            'rgb(255, 50, 50)',
                            'rgba(255, 99, 132, 1)',
                            'rgba(75, 192, 192, 1)',
                            'rgba(153, 102, 255, 1)',
                            // Corresponding border colors
                        ],
                        borderWidth: 1  // Border width for each slice
                    }]
                },
                options: {
                    responsive: true, // Make it responsive
                    plugins: {
                        legend: {
                            position: 'top',  // Legend position on the chart
                        },
                        tooltip: {
                            enabled: true,  // Enable tooltips for better user interaction
                        }
                    }
                }
            });
        })
        .catch(error => console.error('Error loading data:', error));
});

function loadCounts() {
    fetch("index.php?action=getcounts", {
        method: "POST",
    })
        .then(response => response.json())
        .then(resp => {
            if (resp.success && resp.libraryData) {
                let data = resp.libraryData;

                // Assuming you have the following HTML elements to display the counts
                document.getElementById("books").innerHTML = data.bookcount;
                document.getElementById("members").innerHTML = data.membercount;
                document.getElementById("reservations").innerHTML = data.reservationcount;
                document.getElementById("issuedBooks").innerHTML = data.issuecount;
                document.getElementById("totalfines").innerHTML = data.finestotal;

            } else {
                showAlert("Error", resp.message || "No information found", "error");
            }
        })
        .catch(error => {
            showAlert("Error", "Something went wrong", "error");
            console.error("Error fetching library info:", error);
        });
}
function loadTopBooks() {

    fetch("index.php?action=gettopbooks", {
        method: "POST",
    })
        .then(response => response.json())
        .then(resp => {

            let body = document.getElementById("topBookBody");
            body.innerHTML = "";  // Clear existing content

            if (resp.success && resp.books.length > 0) {
                let row = '';  // Initialize row for books
                resp.books.forEach((book, index) => {
                    let coverImageUrl = `index.php?action=serveimage&image=${encodeURIComponent(book.cover_page)}`;
                    let bookCard = `

                    <div class="col-md-3 col-sm-12 col-lg-2 mb-4">
                        <div class="card h-100 d-flex flex-column">
                            <img src="${coverImageUrl}" class="card-img-top img-fluid" alt="Book Cover" style="height: 230px; object-fit: cover;">
                            <div class="card-body d-flex flex-column justify-content-between">
                                <h5 class="card-title text-truncate">${book.title}</h5>
                                <p class="card-text">${book.author}</p>
                            </div>
                        </div>
                    </div>
                    `;

                    row += bookCard;  // Add book to row

                    // After every 4 books, insert the row into the container
                    if ((index + 1) % 4 === 0 || index + 1 === resp.books.length) {
                        body.innerHTML += `<div class="row gap-3">${row}</div>`; // Wrap books in a row
                        row = '';  // Reset row for the next set of books
                    }
                });
            } else {
                body.innerHTML = "<p>No books found</p>";
            }

        })
        .catch(error => {
            console.error("Error fetching book data:", error);
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