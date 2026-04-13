<?php
session_start();
// Enable error reporting for debugging
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Include database connection
include('../config/db.php');

// Predefined admin credentials
$predefined_admin_name = "subadmin"; // Set your admin username here
$predefined_password = "Subadmin123"; // Set your admin password here

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $admin_name = $_POST['admin_name'];
    $password = $_POST['password'];

    // Check if the entered credentials match the predefined ones
    if ($admin_name === $predefined_admin_name && $password === $predefined_password) {
        $_SESSION['admin_logged_in'] = true;
        $_SESSION['admin_name'] = $admin_name;
        echo json_encode(["status" => "success", "message" => "Login successful! Redirecting to SubAdmin Dashboard..."]);
        exit();
    }    

    else {
        echo json_encode(["status" => "error", "message" => "Invalid credentials! Please try again."]);
    }
}
?>