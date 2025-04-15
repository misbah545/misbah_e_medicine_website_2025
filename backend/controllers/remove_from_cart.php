<?php
session_start();
include('../config/db.php'); // Database connection

header('Content-Type: application/json');

if (!isset($_SESSION['customer_id'])) {
    echo json_encode(["status" => "error", "message" => "User not logged in"]);
    exit();
}

if (!isset($_POST['product_id'])) {
    echo json_encode(["status" => "error", "message" => "Product ID missing"]);
    exit();
}

$customer_id = $_SESSION['customer_id'];
$product_id = $_POST['product_id'];

// Check if the item exists before deleting
$check_sql = "SELECT * FROM Orders WHERE id = ? AND Product_id = ?";
$check_stmt = $conn->prepare($check_sql);
$check_stmt->bind_param("ii", $customer_id, $product_id);
$check_stmt->execute();
$result = $check_stmt->get_result();

if ($result->num_rows === 0) {
    echo json_encode(["status" => "error", "message" => "Item not found"]);
    exit();
}

// Proceed with deletion
$sql = "DELETE FROM Orders WHERE id = ? AND Product_id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("ii", $customer_id, $product_id);

if ($stmt->execute() && $stmt->affected_rows > 0) {
    echo json_encode(["status" => "success", "message" => "Item removed from cart"]);
} else {
    echo json_encode(["status" => "error", "message" => "Failed to remove item"]);
}

$stmt->close();
$conn->close();
?>