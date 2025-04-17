function loadPayments(page = 1) {

    var memberid = document.getElementById("memberId").value.trim();
    var transactionid = document.getElementById("transactionId").value.trim();

    let formData = new FormData();
    formData.append("page", page);
    formData.append("memberid", memberid);
    formData.append("transactionid", transactionid);

    fetch("index.php?action=loadpayments", {
        method: "POST",
        body: formData,
    })
        .then(response => response.json())
        .then(resp => {
            let tableBody = document.getElementById("paymentTableBody");
            tableBody.innerHTML = "";

            if (resp.success && resp.payments.length > 0) {
                resp.payments.forEach(payment => {
                  
                    let row = `

                    <tr>
                        <td>${payment.transaction_id}</td>
                        <td>${payment.member_id}</td>
                        <td>${payment.amount}</td>
                        <td>${payment.payed_at}</td>
                        <td>${payment.next_due_date}</td>
                       
                    </tr>
                `;
                    tableBody.innerHTML += row;

                });
            } else {
                tableBody.innerHTML = "<tr><td colspan='7'>No payments found</td></tr>";
            }
            createPagination("pagination", resp.totalPages, page, "loadPayments");
        })
        .catch(error => {
            console.error("Error fetching payment data:", error);
        });
}

function generatePaymentReport() {
    const table = document.getElementById("paymentTable");
    const clonedTable = table.cloneNode(true);

    const tableHTML = clonedTable.outerHTML;
    let formData = new FormData();
    formData.append("table_html", tableHTML);
    formData.append("title", "Payment Report");
    formData.append("filename", "payment_report.pdf");

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