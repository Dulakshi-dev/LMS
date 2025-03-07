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
                    alert("Payment processed successfully.");
                } else {
                    alert("Payment failed or was incomplete.");
                }
            })
            .catch(error => {
                console.error("Error fetching user data:", error);
            });
        renewMembership(transactionId, memberId);
        return false;
    };

    payhere.onDismissed = function () {
        alert("Payment dismissed!");
    };

    payhere.onError = function (error) {
        alert("Payment failed: " + error);
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
                alert("Redirecting to PayHere...");

                // Start PayHere Payment directly here
                payhere.startPayment(resp.payment);
            } else {
                alert("Payment Error: " + resp.error);
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


    fetch("index.php?action=insertPayment", {
        method: "POST",
        body: formData,

    })
    
        .then(response => response.json())
        .then(resp => {
            if (resp.success) {
                window.location.href = "index.php?action=login";
            } else {
                alert("Failed to register member");
            }
        })
        .catch(error => {
            console.error("Error fetching user data:", error);
        });

    return false;
}

