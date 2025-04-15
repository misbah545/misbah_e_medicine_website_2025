$(document).ready(function () {
    function loadNotifications() {
        $.ajax({
            url: "fetch_notifications.php",
            method: "GET",
            dataType: "json",
            success: function (data) {
                let output = "";
                data.forEach(notification => {
                    output += `<div class='notification'>
                        ${notification.Message} 
                        <button onclick='markRead(${notification.Notification_id})'>Mark as Read</button>
                    </div>`;
                });
                $("#notifications").html(output);
            }
        });
    }
    loadNotifications();

    function markRead(notificationId) {
        $.post("mark_notification_read.php", { notification_id: notificationId }, function () {
            loadNotifications();
        });
    }

    // ✅ Get Customer ID from session storage
    let customer_id = sessionStorage.getItem("customer_id");

    if (!customer_id || customer_id === "null") {
        console.error("❌ Error: Customer ID is missing.");
        alert("Error: Customer ID is missing. Please login again.");
        return;
    }

    console.log("✅ Sending notification for Customer ID:", customer_id);

    // Send notification
    $.post("http://localhost:8000/backend/controllers/send_notification.php", {
        customer_id: customer_id,
        message: "Your prescription is ready for pickup."
    }, function (response) {
        alert(response);
    }).fail(function () {
        alert("❌ Failed to send notification. Please try again.");
    });
});
