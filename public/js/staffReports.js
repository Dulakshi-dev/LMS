
document.addEventListener("DOMContentLoaded", function () {
    const generateBtn = document.getElementById("generateReport");

    if (generateBtn) {
        generateBtn.addEventListener("click", () => {
            const table = document.getElementById("stafftable");

            console.log("Table:", table);  // Debugging line

            if (table) {
                const tableHTML = table.outerHTML;

                let formData = new FormData();
                formData.append("table_html", tableHTML);

                fetch("index.php?action=printactivestaffreport", {
                    method: "POST",
                    body: formData
                })
                    .then(response => response.blob())
                    .then(blob => {
                        const url = window.URL.createObjectURL(blob);
                        window.open(url); // opens in new tab
                        document.body.removeChild(a);
                        window.URL.revokeObjectURL(url);
                    });
            } else {
                console.error("Table with ID 'stafftable' not found.");
            }
        });
    }
});

function loadUsers(page = 1, status = "Active") {
    var userid = document.getElementById("memberId").value.trim();
    var nic = document.getElementById("nic").value.trim();
    var name = document.getElementById("userName").value.trim();

    let formData = new FormData();
    formData.append("page", page);
    formData.append("memberid", userid);
    formData.append("nic", nic);
    formData.append("username", name);
    formData.append("status", status);

    fetch("index.php?action=loadusers", {
        method: "POST",
        body: formData,
    })
        .then(response => response.json())
        .then(resp => {
            let tableBody = document.getElementById("userTableBody");
            tableBody.innerHTML = "";

            if (resp.success && resp.users.length > 0) {
                resp.users.forEach(user => {

                    let row = `
                        <tr>
                            <td>${user.staff_id}</td>
                            <td>${user.nic}</td>
                            <td>${user.fname} ${user.lname}</td>
                            <td>${user.address}</td>
                            <td>${user.mobile}</td>
                            <td>${user.email}</td>
                        </tr>
                    `;
                    tableBody.innerHTML += row;
                });
            } else {
                tableBody.innerHTML = "<tr><td colspan='7'>No users found</td></tr>";
            }
            createPagination("pagination", resp.totalPages, page, "loadUsers");
        })
        .catch(error => {
            console.error("Error fetching user data:", error);
        });
}


