<?php
session_start();
include('../config/db.php');

header('Content-Type: application/json');

if (!isset($_SESSION['customer_id'])) {
    echo json_encode([]);
    exit();
}

$customer_id = $_SESSION['customer_id'];

// Fetch only existing items in cart
$sql = "SELECT o.Product_id, p.Pro_name, p.Price, o.Quantity 
        FROM Orders o
        JOIN Product p ON o.Product_id = p.Product_id
        WHERE o.id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $customer_id);
$stmt->execute();
$result = $stmt->get_result();

$cartItems = [];
while ($row = $result->fetch_assoc()) {
    $cartItems[] = $row;
}

echo json_encode($cartItems);

$stmt->close();
$conn->close();
?>