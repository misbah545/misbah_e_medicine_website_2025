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

});
