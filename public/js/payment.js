
function showAlert(title, message, type) {
    return Swal.fire({
        title: title,
        text: message,
        icon: type, // 'success', 'error', 'warning', 'info', 'question'
        confirmButtonText: 'OK'
    });
}

window.onload = function () {
    payhere.onCompleted = function (transactionId) {
        const memberId = window.membershipId;

        let formData = new FormData();
        formData.append("transactionId", transactionId);


        fetch("index.php?action=payment_notify", {
            method: "POST",
            body: formData,
        })
            .then(response => response.json())
            .then(resp => {
                if (resp.success) {
                    showAlert("Success", resp.message, "success");

                } else {
                    showAlert("Error", resp.message, "error");

                }
            })
            .catch(error => {
                console.error("Error fetching user data:", error);
            });
        renewMembership(transactionId, memberId);
        return false;
    };

    payhere.onDismissed = function () {
        showAlert("Info", "Payment Dismissed!", "info");

    };

    payhere.onError = function (error) {
        
        showAlert("Error", "Payment failed: " + error, "error");

    };
};

function proceedPayment(id) {
   
    window.membershipId = id;
    fetch("index.php?action=showPayment", {
        method: "POST",
        body: JSON.stringify({ id: id }), // Include the ID as a POST parameter

    })
        .then(response => response.json())
        .then(resp => {
            if (resp.status === "success") {
                showAlert("Info", "Redirecting to PayHere...", "info");

                // Start PayHere Payment directly here
                payhere.startPayment(resp.payment);
            } else {
                showAlert("Error", "Payment Error: " + resp.error, "error");
            }
        })
        .catch(error => {
            console.error("Error fetching user data:", error);
        });

    return false;

}

function renewMembership(transactionId, memberId) {
    var formData = new FormData();
    formData.append("transactionId", transactionId);
    formData.append("memberId", memberId);


    fetch("index.php?action=renewmembership", {
        method: "POST",
        body: formData,

    })
    
        .then(response => response.json())
        .then(resp => {
            if (resp.success) {
                window.location.href = "index.php?action=login";
            } else {
                showAlert("Error", resp.message, "error");
            }
        })
        .catch(error => {
            console.error("Error fetching user data:", error);
        });

    return false;
}

