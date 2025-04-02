function notificatioload() {
    fetch("index.php?action=loadnotifications", {
        method: "GET",
    })
    .then(response => response.json())
    .then(resp => {
        if (resp.success) {
            let notificationList = document.getElementById("notification-list");
            notificationList.innerHTML = ""; // Clear previous notifications

            if (resp.notifications.length > 0) {
                resp.notifications.forEach(notification => {
                    let item = `
                        <div class="dropdown-item notification-item ${notification.status === 'unread' ? 'unread' : 'read'}"
                            data-id="${notification.id}">
                            <small class="text-muted">${notification.created_at}</small>
                            <p class="mb-1">${notification.message}</p>
                            <hr>
                        </div>
                    `;
                    notificationList.innerHTML += item;
                });
            } else {
                notificationList.innerHTML = '<p class="text-center text-muted">No new notifications</p>';
            }
        } else {
            console.error("Error:", resp.message);
        }
    })
    .catch(error => {
        console.error("Error:", error);
    });
}

// Attach the function to the bell icon click
document.getElementById("notification-bell").addEventListener("click", function (e) {
    e.preventDefault();
    document.getElementById("notification-dropdown").style.display = "block"; // Show the dropdown
    notificatioload(); // Load notifications
});

// Mark notification as read when clicked
document.addEventListener("DOMContentLoaded", function () {
    document.getElementById("notification-list").addEventListener("click", function (event) {
        let notificationItem = event.target.closest(".notification-item");
        
        if (!notificationItem) return; // Prevent errors on empty space clicks

        let notificationId = notificationItem.dataset.id;

        let formData = new FormData();
        formData.append("notification_id", notificationId);

        fetch("index.php?action=markasread", {
            method: "POST",
            body: formData,
        })
        .then(response => response.json())
        .then(resp => {
            if (resp.success) {
                notificationItem.classList.remove("unread");
                notificationItem.classList.add("read"); 
                notificationItem.style.opacity = "0.5"; // Fade out to show it's read
            }
        })
        .catch(error => console.error("Error:", error));
    });
});
function fetchNotificationCount() {
    fetch("index.php?action=getunreadcount", {
        method: "GET",
    })
    .then(response => response.json())
    .then(resp => {
        if (resp.success) {
            let countBadge = document.getElementById("notification-count");
            if (resp.count > 0) {
                countBadge.innerText = resp.count;
                countBadge.style.display = "inline-block"; // Show badge
            } else {
                countBadge.style.display = "none"; // Hide if no unread notifications
            }
        }
    })
    .catch(error => console.error("Error:", error));
}

// Fetch unread count when the page loads
document.addEventListener("DOMContentLoaded", function () {
    fetchNotificationCount();
});
