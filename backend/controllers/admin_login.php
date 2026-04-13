<?php
session_start();
// Enable error reporting for debugging
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Include database connection
include('../config/db.php');

// Predefined admin credentials
$predefined_admin_name = "admin"; // Set your admin username here
$predefined_password = "admin123"; // Set your admin password here

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $admin_name = trim($_POST['admin_name']); // Sanitize input
    $password = trim($_POST['password']); // Sanitize input

    // Check if the entered credentials match the predefined ones
    if ($admin_name === $predefined_admin_name && $password === $predefined_password) {
        $_SESSION['admin_logged_in'] = true;
        $_SESSION['admin_name'] = $admin_name; // Store admin name in session
        echo json_encode(["status" => "success", "message" => "Login successful! Redirecting to Admin Dashboard..."]);
        exit();
    } else {
        // Invalid credentials
        echo json_encode(["status" => "error", "message" => "Invalid Admin credentials! Please try again."]);
        exit();
    }
}
?>