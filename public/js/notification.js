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
    const triggerButton = document.getElementById("notification-bell");
    const notificationList = document.getElementById("notification-list");

    function toggleDropdown() {
        dropdown.style.display = (dropdown.style.display === "none" || dropdown.style.display === "") ? "block" : "none";
    }

    // Toggle dropdown and load notifications
    triggerButton.addEventListener("click", function (event) {
        event.stopPropagation();
        toggleDropdown();
        notificationload();
    });

    // Unified outside click handling
        document.addEventListener("click", function (event) {
            const isClickInsideDropdown = dropdown.contains(event.target);
            const isBellClick = event.target === triggerButton || triggerButton.contains(event.target);
            const clickedNotification = event.target.closest(".notification-item");
        
            const allItems = document.querySelectorAll(".notification-item");
        
            if (clickedNotification) {
                // Deselect others
                allItems.forEach(item => item.classList.remove("selected"));
                // Select clicked
                clickedNotification.classList.add("selected");
            } else {
                // Clicked outside, remove selection
                allItems.forEach(item => item.classList.remove("selected"));
            }
        
            if (!isClickInsideDropdown && !isBellClick) {
                dropdown.style.display = "none";
            }
        });
        

    // Mark notification as read when clicked
    notificationList.addEventListener("click", function (event) {
        let notificationItem = event.target.closest(".notification-item");
        if (!notificationItem) return;

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
                notificationItem.style.opacity = "0.5";
            }
        })
        .catch(error => console.error("Error:", error));
    });

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


