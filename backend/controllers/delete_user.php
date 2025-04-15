<?php
session_start();
// Enable error reporting for debugging
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Include database connection
include('../config/db.php');
//include("../../backend/config.php"); // Adjust path if needed

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $userId = $_POST['id'];
    
    $query = "DELETE FROM Customer WHERE id = ?";
    $stmt = mysqli_prepare($conn, $query);
    mysqli_stmt_bind_param($stmt, "i", $userId);
    
    if (mysqli_stmt_execute($stmt)) {
        echo "User deleted successfully!";
    } else {
        echo "Error deleting user.";
    }
    
    mysqli_stmt_close($stmt);
}
?>
