function loadReservations(page = 1) {
    var memberid = document.getElementById("memberid").value.trim();
    var bookid = document.getElementById("bookid").value.trim();
    var title = document.getElementById("title").value.trim();
    var status = document.getElementById("statusSelection").value;

    let formData = new FormData();
    formData.append("page", page);
    formData.append("memberid", memberid);
    formData.append("bookid", bookid);
    formData.append("title", title);
    formData.append("status", status); 

    fetch("index.php?action=loadreservations", {
        method: "POST",
        body: formData,
    })
    .then(response => response.json())
    .then(resp => {
        let tableBody = document.getElementById("reservationTableBody");
        tableBody.innerHTML = "";

        if (resp.success && resp.reservations.length > 0) {
            resp.reservations.forEach(reservation => {
                let notifiedDate = reservation.notified_date 
                    ? reservation.notified_date 
                    : reservation.reservation_date;
                let actionColumn = reservation.status === "Reserved"
                    ? `
                        <form action="index.php?action=showissuebook" method="POST">
                            <input type="hidden" name="member_id" value="${reservation.member_id}">
                            <input type="hidden" name="book_id" value="${reservation.reservation_book_id}">
                            <button type="submit" class="btn btn-primary btn-sm">Issue</button>
                        </form>
                      `
                    : `<p>${reservation.status}</p>`;

                let row = `
                    <tr>
                        <td>${reservation.reservation_id}</td>
                        <td>${reservation.member_id}</td>
                        <td>${reservation.reservation_book_id}</td>
                        <td>${reservation.title}</td>
                        <td>${reservation.reservation_date}</td>
                        <td>${notifiedDate}</td>
                        <td>${reservation.expiration_date}</td>
                        <td class="action-buttons">${actionColumn}</td>
                    </tr>
                `;

                tableBody.innerHTML += row;
            });
        } else {
            tableBody.innerHTML = "<tr><td colspan='8'>No reservations found</td></tr>";
        }

        createPagination("pagination", resp.totalPages, page, "loadReservations");
    })
    .catch(error => {
        console.error("Error fetching reservations data:", error);
    });
}

document.getElementById("statusSelection").addEventListener("change", function() {
    loadReservations(); 
});

document.addEventListener("DOMContentLoaded", function () {
    loadReservations();
});


function showAlert(title, message, type) {
    return Swal.fire({
        title: title,
        text: message,
        icon: type, // 'success', 'error', 'warning', 'info', 'question'
        confirmButtonText: 'OK'
    });
}


function generateReservedBookReport() {
    const table = document.getElementById("reserveBookTable");
    const clonedTable = table.cloneNode(true);

    // Remove all action buttons
    clonedTable.querySelectorAll(".action-buttons").forEach(el => el.remove());

    // Remove the last column (assuming Actions is the last column)
    const headerRow = clonedTable.querySelector("thead tr");
    const totalColumns = headerRow.children.length;

    // Remove last <th>
    headerRow.deleteCell(totalColumns - 1);

    // Remove last <td> from each row
    clonedTable.querySelectorAll("tbody tr").forEach(row => {
        if (row.children.length === totalColumns) {
            row.deleteCell(totalColumns - 1);
        }
    });

    // Continue with report generation
    const tableHTML = clonedTable.outerHTML;
    let formData = new FormData();
    formData.append("table_html", tableHTML);
    formData.append("title", "Reserved Books Report");
    formData.append("filename", "reserved_books_report.pdf");

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