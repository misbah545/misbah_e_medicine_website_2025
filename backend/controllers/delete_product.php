<?php
session_start();
header('Content-Type: application/json');

// Enable error reporting for debugging
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Include database connection
include('../config/db.php');
header('Content-Type: application/json');
//include('../config/db.php');

// Debugging: Check if the request is received
file_put_contents("debug_log_delete.txt", json_encode($_POST) . "\n", FILE_APPEND);

if (!isset($_POST['Product_id'])) {
    echo json_encode(["error" => "Product ID is required!"]);
    exit;
}

$product_id = $_POST['Product_id'];

$query = "DELETE FROM Product WHERE Product_id = ?";
$stmt = $conn->prepare($query);
$stmt->bind_param("i", $product_id);

if ($stmt->execute()) {
    echo json_encode(["success" => "Product deleted successfully!"]);
} else {
    echo json_encode(["error" => "Failed to delete product: " . $conn->error]);
}
?>
