<?php
session_start();
// Enable error reporting for debugging
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Include database connection
include('../config/db.php');
//include '../config/db.php'; // ✅ Ensure correct path
header("Content-Type: application/json");

// Debugging: Check if Customer ID is received
if (!isset($_POST['customer_id']) || empty($_POST['customer_id'])) {
    echo json_encode(["error" => "❌ Customer ID is missing.", "debug" => $_POST]);
    exit();
}

$customer_id = intval($_POST['customer_id']); // Convert to integer
$message = isset($_POST['message']) ? $_POST['message'] : "No message provided.";

// Debugging: Print received data
error_log("Customer ID: " . $customer_id . " | Message: " . $message);

$sql = "INSERT INTO Notifications (Customer_id, Message, Status) VALUES (?, ?, 'unread')";
$stmt = $conn->prepare($sql);
$stmt->bind_param("is", $customer_id, $message);

if ($stmt->execute()) {
    echo json_encode(["success" => "✅ Notification sent successfully!"]);
} else {
    echo json_encode(["error" => "❌ Failed to send notification."]);
}
?>
