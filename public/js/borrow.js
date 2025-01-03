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
    
    document.getElementById("dueDate").value = dueDate;
}

function loadFines() {
    var dueDate = document.getElementById("dueDate").value;
    var returnDate = document.getElementById("returnDate").value;


//load fines

}
