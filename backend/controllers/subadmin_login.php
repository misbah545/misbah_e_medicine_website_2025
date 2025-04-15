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
        //echo "<div style='color:green;'>Login successful! Redirecting to SubAdmin Dashboard..</div>";
        echo "<script>alert('Login successful! Redirecting to SubAdmin Dashboard...'); window.location.href='http://localhost:8000/frontend/pages/subadmin_dashboard.html';</script>";
        exit();
    }    

    else {
        echo "<script>alert('Invalid credentials! Please try again.'); window.location.href='http://localhost:8000/frontend/pages/subadmin_login.html';</script>";
    }
}
?>