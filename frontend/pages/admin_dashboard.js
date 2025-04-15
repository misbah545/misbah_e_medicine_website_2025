document.addEventListener("DOMContentLoaded", function () {
    const toggleBtn = document.getElementById("toggle-btn");
    const sidebar = document.querySelector(".sidebar");
    const mainContent = document.querySelector(".main-content");

    toggleBtn.addEventListener("click", function () {
        if (sidebar.style.width === "250px") {
            sidebar.style.width = "0";
            mainContent.style.marginLeft = "0";
        } else {
            sidebar.style.width = "250px";
            mainContent.style.marginLeft = "250px";
        }
    });
});

document.addEventListener("DOMContentLoaded", function () {
    const menuItems = document.querySelectorAll(".sidebar ul li a");

    menuItems.forEach(item => {
        item.addEventListener("click", function () {
            menuItems.forEach(link => link.classList.remove("active"));
            this.classList.add("active");
        });
    });
});


$(document).ready(function () {
    function fetchData() {
        $.ajax({
            url: "http://localhost:8000/backend/controllers/fetch_dashboard_data.php",
            type: "GET",
            dataType: "json",
            success: function (data) {
                
                $("#customer").text(data.Customer);
                $("#review").text(data.Review);
                $("#contact_messages").text(data.contact_messages);
            },
            error: function (xhr, status, error) {
                console.error("Error fetching data:", error);
            }
        });
    }

    fetchData();
    setInterval(fetchData, 5000); // Refresh data every 5 seconds
});

$.ajax({
    url: "http://localhost:8000/backend/controllers/fetch_categories.php",
    type: "GET",
    success: function (data) {
        console.log("Categories:", data);
    },
    error: function () {
        console.log("Error fetching categories.");
    }
});
