<?php
include '../config/db.php';

$product_id = $_GET['product_id'];

$product_query = "SELECT * FROM Product WHERE Product_id = $product_id";
$product_result = mysqli_query($conn, $product_query);
$product = mysqli_fetch_assoc($product_result);

$review_query = "SELECT * FROM Review WHERE Product_id = $product_id";
$review_result = mysqli_query($conn, $review_query);
$reviews = [];
while ($row = mysqli_fetch_assoc($review_result)) {
    $reviews[] = $row;
}

echo json_encode(['product' => $product, 'reviews' => $reviews]);
?>
