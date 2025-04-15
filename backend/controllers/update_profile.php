<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Database connection
include('../config/db.php');session_start();

if (!isset($_SESSION['customer_id'])) {
    echo json_encode(["error" => "User not logged in"]);
    exit;
}

$customer_id = $_SESSION['customer_id'];
$name = $_POST['name'];
$email = $_POST['email'];
$phone = $_POST['phone'];
$address = $_POST['address'];

$query = "UPDATE Customer SET name = ?, email = ?, phone = ?, address = ? WHERE id = ?";
$stmt = $conn->prepare($query);
$stmt->bind_param("ssssi", $name, $email, $phone, $address, $customer_id);

if ($stmt->execute()) {
    echo json_encode(["message" => "Profile updated successfully"]);
} else {
    echo json_encode(["error" => "Failed to update profile"]);
}

$stmt->close();
$conn->close();
?>
