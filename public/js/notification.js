function notificationload() {
    fetch("index.php?action=loadnotifications", {
        method: "GET",
    })
    .then(response => response.json())
    .then(resp => {
        let notificationList = document.getElementById("notification-list");
        notificationList.innerHTML = ""; // Clear old notifications

        if (resp.success && resp.notifications.length > 0) {
            resp.notifications.forEach(notification => {
                let item = `
                    <div class="dropdown-item notification-item ${notification.status === 'unread' ? 'unread' : 'read'}"
                        data-id="${notification.id}">
                        <small class="text-muted">${notification.created_at}</small>
                        <p>${notification.message}</p>
                        <hr>
                    </div>
                `;
                notificationList.innerHTML += item;
            });
        } else {
            notificationList.innerHTML = '<p class="text-center text-muted">No new notifications</p>';
        }
    })
    .catch(error => {
        console.error("Error:", error);
    });
}

document.addEventListener("DOMContentLoaded", function () {
    const dropdown = document.getElementById("notification-dropdown");
    const triggerButton = document.getElementById("notification-bell"); // Button to open dropdown
    const notificationList = document.getElementById("notification-list");

    // Function to toggle dropdown visibility
    function toggleDropdown() {
        if (dropdown.style.display === "none" || dropdown.style.display === "") {
            dropdown.style.display = "block";
        } else {
            dropdown.style.display = "none";
        }
    }

    // Open dropdown when clicking the bell icon
    triggerButton.addEventListener("click", function (event) {
        event.stopPropagation(); // Prevent the click event from propagating to document
        toggleDropdown();
        notificationload(); // Load notifications when the dropdown opens
    });

    // Close dropdown when clicking outside
    document.addEventListener("click", function (event) {
        if (!dropdown.contains(event.target) && event.target !== triggerButton) {
            dropdown.style.display = "none"; // Close the dropdown
        }
    });

    // Mark notification as read when clicked
    notificationList.addEventListener("click", function (event) {
        let notificationItem = event.target.closest(".notification-item");
        
        if (!notificationItem) return; // Prevent errors if click is not on an item

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
                notificationItem.style.opacity = "0.5"; // Fade out to indicate it's read
            }
        })
        .catch(error => console.error("Error:", error));
    });

    // Fetch unread count when the page loads
    fetchNotificationCount();
});

// Function to fetch unread notification count
function fetchNotificationCount() {
    fetch("index.php?action=getunreadcount", {
        method: "GET",
    })
    .then(response => response.json())
    .then(resp => {
        let countBadge = document.getElementById("notification-count");
        if (resp.success && resp.count > 0) {
            countBadge.innerText = resp.count;
            countBadge.style.display = "inline-block"; // Show badge
        } else {
            countBadge.style.display = "none"; // Hide if no unread notifications
        }
    })
    .catch(error => console.error("Error:", error));
}


