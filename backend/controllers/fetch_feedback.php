<?php
session_start();
// Enable error reporting for debugging
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Include database connection
include('../config/db.php');
//include("../../backend/config.php"); // Adjust path if needed

$query = "SELECT Review.Review_id, Customer.name AS customer_name, Product.Pro_name, Review.Review_Text, Review.Rating, Review.Review_Date
          FROM Review
          JOIN Customer ON Review.Customer_id = Customer.id
          JOIN Product ON Review.Product_id = Product.Product_id
          ORDER BY Review.Review_Date DESC";

$result = mysqli_query($conn, $query);

$feedbacks = [];

while ($row = mysqli_fetch_assoc($result)) {
    $feedbacks[] = $row;
}

echo json_encode($feedbacks);
?>
