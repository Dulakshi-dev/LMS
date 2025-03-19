
function showAlert(title, message, type) {
    return Swal.fire({
        title: title,
        text: message,
        icon: type, // 'success', 'error', 'warning', 'info', 'question'
        confirmButtonText: 'OK'
    });
}

function loadReservations() {
    fetch("index.php?action=loadreservedbooks", {
        method: "POST",
    })
        .then(response => response.json())
        .then(resp => {
            let tableBody = document.getElementById("reservationTableBody");
            tableBody.innerHTML = "";

            if (resp.success && resp.reservations.length > 0) {
                resp.reservations.forEach(reservation => {
                    let coverImageUrl = `index.php?action=serveimage&image=${encodeURIComponent(reservation.cover_page)}`;

                    let row = `
                        <tr>
                            <td>${reservation.reservation_id}</td>
                            <td>${reservation.reservation_book_id}</td>
                             <td><img src="${coverImageUrl}" alt="Book Cover" style="width: 50px; height: 75px; object-fit: cover;"></td>
                            <td>${reservation.title}</td>
                            <td>${reservation.reservation_date}</td>
                            <td>${reservation.expiration_date}</td>
                             <td>${reservation.status}</td>

                            <td> <button class="btn btn-success my-1 btn-sm cancel-reservation" data-reservation_id="${reservation.reservation_id}">
                           Cancel
                        </button></td>
                        </tr>
                    `;

                    tableBody.innerHTML += row;
                });
            } else {
                tableBody.innerHTML = "<tr><td colspan='8'>No reservations found</td></tr>";
            }

        })
        .catch(error => {
            console.error("Error fetching reservations data:", error);
        });
}


document.addEventListener("DOMContentLoaded", function () {
    loadReservations();

    document.body.addEventListener("click", function (event) {
        if (event.target.closest(".cancel-reservation")) {
            let button = event.target.closest(".cancel-reservation");
            cancelReservation(button.dataset.reservation_id);
        }
    });
});

function cancelReservation(reservation_id){
    var formData = new FormData();
    formData.append("reservation_id", reservation_id);

    fetch("index.php?action=cancelreservation", {
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
        });
}

