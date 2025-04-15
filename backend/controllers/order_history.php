<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Database connection
include('../config/db.php');
session_start();

if (!isset($_SESSION['customer_id'])) {
    echo json_encode(["error" => "User not logged in"]);
    exit;
}

$customer_id = $_SESSION['customer_id'];
$query = "SELECT Orders.Order_id AS order_id, Product.Pro_name AS product_name, Orders.Price AS price, Orders.Order_Date AS date 
          FROM Orders 
          JOIN Product ON Orders.Product_id = Product.Product_id 
          WHERE Orders.id = ?";
$stmt = $conn->prepare($query);
$stmt->bind_param("i", $customer_id);
$stmt->execute();
$result = $stmt->get_result();

$orders = [];
while ($row = $result->fetch_assoc()) {
    $orders[] = $row;
}

echo json_encode($orders);

$stmt->close();
$conn->close();
?>
