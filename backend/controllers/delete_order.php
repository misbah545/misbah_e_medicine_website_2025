<?php
session_start();
// Enable error reporting for debugging
error_reporting(E_ALL);
ini_set('display_errors', 1);
//include("../../backend/config.php"); // Adjust path if needed
// Include database connection
include('../config/db.php');

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $orderId = $_POST['Order_id'];
    
    $query = "DELETE FROM Orders WHERE Order_id = ?";
    $stmt = mysqli_prepare($conn, $query);
    mysqli_stmt_bind_param($stmt, "i", $orderId);
    
    if (mysqli_stmt_execute($stmt)) {
        echo "Order deleted successfully!";
    } else {
        echo "Error deleting order.";
    }
    
    mysqli_stmt_close($stmt);
}
?>
