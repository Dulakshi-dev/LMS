function loadBookData() {
    var book_id = document.getElementById("book_id").value;

//validate book id - empty and format

    var formData = new FormData();
    formData.append("book_id", book_id);
    fetch("index.php?action=loadborrowbookdata", {
        method: "POST",
        body: formData,
    })
        .then(response => response.json()) 
        .then(resp => {
            if (resp.success) {
                document.getElementById("isbn").value = resp.isbn;
                document.getElementById("title").value = resp.title;
                document.getElementById("author").value = resp.author;
              
            } else {
                alert("Failed to load book data. Please try again.");
            }
        })
        .catch(error => {
            console.error("Error fetching book data:", error);
        });
}

function loadMemberData() {
    var member_id = document.getElementById("member_id").value;

    //validate member id - empty and format

    var formData = new FormData();
    formData.append("member_id", member_id);
    fetch("index.php?action=loadborrowmemberdata", {
        method: "POST",
        body: formData,
    })
        .then(response => response.json()) 
        .then(resp => {
            if (resp.success) {
                document.getElementById("nic").value = resp.nic;
                document.getElementById("memName").value = resp.name;
              
            } else {
                alert("Failed to load book data. Please try again.");
            }
        })
        .catch(error => {
            console.error("Error fetching book data:", error);
        });
}

function returnButtonClick(button) {

    const dueDate = button.getAttribute("data-due-date");
    const borrow_id = button.getAttribute("data-borrow-id");
    const book_id = button.getAttribute("data-book-id");

    
    document.getElementById("dueDate").value = dueDate;
    document.getElementById("borrowId").value = borrow_id;
    document.getElementById("bookId").value = book_id;

}

function generateFine() {
    
    var dueDate = document.getElementById("dueDate").value;
    var returnDate = document.getElementById("returnDate").value;

    let due = new Date(dueDate);
    let returned = new Date(returnDate);

    // Calculate difference in days
    let timeDiff = returned - due;
    let daysLate = Math.ceil(timeDiff / (1000 * 60 * 60 * 24)); // Convert milliseconds to days

    let fine = Math.max(daysLate * 5, 0);

    // Show alert based on fine amount
    if (fine > 0) {
        document.getElementById("amount").value = fine;
    } else {
        document.getElementById("amount").value = 0;
    }

    return fine;

}
