<?php
session_start();
include '../config/db.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // ✅ Check if user is logged in
    if (!isset($_SESSION['customer_id'])) {
        echo json_encode(['success' => false, 'message' => 'Please login to submit a review.']);
        exit;
    }

    $customer_id = $_SESSION['customer_id']; // ✅ Get logged-in customer ID
    $product_id = $_POST['product_id'];
    $review_text = $_POST['review_text'];
    $rating = $_POST['rating'];

    // ✅ Check if fields are empty
    if (empty($review_text) || empty($rating) || empty($product_id)) {
        echo json_encode(['success' => false, 'message' => 'All fields are required.']);
        exit;
    }

    // ✅ Insert review into database
    $query = "INSERT INTO Review (Customer_id, Product_id, Review_Text, Rating, Review_Date) 
              VALUES ('$customer_id', '$product_id', '$review_text', '$rating', NOW())";

    if (mysqli_query($conn, $query)) {
        echo json_encode(['success' => true, 'message' => 'Review submitted successfully!']);
    } else {
        echo json_encode(['success' => false, 'message' => 'Failed to submit review']);
    }
}
?>
