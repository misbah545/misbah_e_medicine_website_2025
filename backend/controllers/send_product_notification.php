<?php
session_start();
// Enable error reporting for debugging
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Include database connection
include('../config/db.php');
$productId = 1; // Example product ID
$query = "SELECT Pro_name FROM Product WHERE Product_id = '$productId' AND Stock > 0";
$result = mysqli_query($conn, $query);
if ($row = mysqli_fetch_assoc($result)) {
    $message = "Good news! The product '" . $row['Pro_name'] . "' is back in stock.";
    $customerQuery = "SELECT id FROM Customer";
    $customerResult = mysqli_query($conn, $customerQuery);
    while ($customer = mysqli_fetch_assoc($customerResult)) {
        $customerId = $customer['id'];
        mysqli_query($conn, "INSERT INTO Notifications (Customer_id, Message) VALUES ('$customerId', '$message')");
    }
}
?>