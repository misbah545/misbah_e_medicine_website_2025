$(document).ready(function () {
    function fetchFeedback() {
        $.ajax({
            url: "http://localhost:8000/backend/controllers/fetch_feedback.php",
            type: "GET",
            dataType: "json",
            success: function (feedbacks) {
                let feedbackTable = $("#feedbackTable");
                feedbackTable.empty();

                feedbacks.forEach(feedback => {
                    feedbackTable.append(`
                        <tr>
                            <td>${feedback.Review_id}</td>
                            <td>${feedback.customer_name}</td>
                            <td>${feedback.Pro_name}</td>
                            <td>${feedback.Review_Text}</td>
                            <td>⭐ ${feedback.Rating}</td>
                            <td>${feedback.Review_Date}</td>
                            <td>
                                <button class="delete-btn" data-id="${feedback.Review_id}"><i class="fas fa-trash"></i></button>
                            </td>
                        </tr>
                    `);
                });
            },
            error: function (xhr, status, error) {
                console.error("Error fetching feedback:", error);
            }
        });
    }

    fetchFeedback();
    setInterval(fetchFeedback, 5000); // Refresh every 5 seconds

    // Delete feedback functionality
    $(document).on("click", ".delete-btn", function () {
        let reviewId = $(this).data("id");

        if (confirm("Are you sure you want to delete this feedback?")) {
            $.ajax({
                url: "http://localhost:8000/backend/controllers/delete_feedback.php",
                type: "POST",
                data: { Review_id: reviewId },
                success: function (response) {
                    alert("Feedback deleted successfully!");
                    fetchFeedback();
                },
                error: function () {
                    alert("Error deleting feedback.");
                }
            });
        }
    });
});
