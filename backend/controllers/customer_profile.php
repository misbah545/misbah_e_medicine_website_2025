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
$query = "SELECT name, email, phone, address FROM Customer WHERE id = ?";
$stmt = $conn->prepare($query);
$stmt->bind_param("i", $customer_id);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows > 0) {
    echo json_encode($result->fetch_assoc());
} else {
    echo json_encode(["error" => "User not found"]);
}

$stmt->close();
$conn->close();
?>
