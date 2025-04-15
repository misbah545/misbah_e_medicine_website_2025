document.addEventListener("DOMContentLoaded", function () {
    const urlParams = new URLSearchParams(window.location.search);
    const productId = urlParams.get('product_id');

    fetch(`http://localhost:8000/backend/controllers/get_product_details.php?product_id=${productId}`)
        .then(response => response.json())
        .then(data => {
            document.getElementById('product-name').innerText = data.product.Pro_name;
            document.getElementById('product-price').innerText = "₹" + data.product.Price;
            document.getElementById('product-description').innerText = data.product.Description;
            document.getElementById('product-image').src = "../assets/images/" + data.product.Product_img;

            let reviewsList = document.getElementById('reviews-list');
            reviewsList.innerHTML = "";

            if (data.reviews.length === 0) {
                reviewsList.innerHTML = "<p>No reviews yet.</p>";
            } else {
                data.reviews.forEach(review => {
                    let reviewItem = `<div class='review-box'>
                        <p><strong>Rating:</strong> <span class="rating">${review.Rating} ⭐</span></p>
                        <p>${review.Review_Text}</p>
                        <p class="date">${review.Review_Date}</p>
                    </div>`;
                    reviewsList.innerHTML += reviewItem;
                });
            }
        })
        .catch(error => console.error("Error fetching product details:", error));
});

// ✅ Fixed submitReview() function
function submitReview() {
    let reviewText = document.getElementById("review-text").value.trim();
    let rating = document.getElementById("rating").value;
    const urlParams = new URLSearchParams(window.location.search);
    const productId = urlParams.get('product_id');

    if (reviewText === "") {
        alert("Review cannot be empty!");
        return;
    }

    fetch("http://localhost:8000/backend/controllers/review.php", {
        method: "POST",
        headers: { "Content-Type": "application/x-www-form-urlencoded" },
        body: `product_id=${productId}&review_text=${reviewText}&rating=${rating}`
    })
    .then(response => response.json())
    .then(data => {
        alert(data.message);
        if (data.success) location.reload(); // ✅ Refresh page only if review is submitted
    })
    .catch(error => console.error("Error submitting review:", error));
}
