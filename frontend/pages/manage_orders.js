$(document).ready(function () {
    function fetchOrders() {
        $.ajax({
            url: "http://localhost:8000/backend/controllers/fetch_orders.php",
            type: "GET",
            dataType: "json",
            success: function (orders) {
                let orderTable = $("#orderTable");
                orderTable.empty();

                if (orders.length === 0) {
                    orderTable.append(`
                        <tr>
                            <td colspan="7" style="text-align: center;">No orders found.</td>
                        </tr>
                    `);
                } else {
                    orders.forEach(order => {
                        orderTable.append(`
                            <tr>
                                <td>${order.Order_id}</td>
                                <td>${order.customer_name}</td>
                                <td>${order.Pro_name}</td>
                                <td>${order.Quantity}</td>
                                <td>₹${order.Total_Amount}</td>
                                <td>${order.Order_Date}</td>
                                <td>
                                    <button class="delete-btn" data-id="${order.Order_id}"><i class="fas fa-trash"></i></button>
                                </td>
                            </tr>
                        `);
                    });
                }
            },
            error: function (xhr, status, error) {
                console.error("Error fetching orders:", error);
            }
        });
    }

    fetchOrders();
    setInterval(fetchOrders, 5000); // Refresh every 5 seconds

    // Delete order functionality
    $(document).on("click", ".delete-btn", function () {
        let orderId = $(this).data("id");

        if (confirm("Are you sure you want to delete this order?")) {
            $.ajax({
                url: "http://localhost:8000/backend/controllers/delete_order.php",
                type: "POST",
                data: { Order_id: orderId },
                success: function (response) {
                    alert("Order deleted successfully!");
                    fetchOrders();
                },
                error: function () {
                    alert("Error deleting order.");
                }
            });
        }
    });
});