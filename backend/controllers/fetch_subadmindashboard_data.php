<?php
session_start();
header('Content-Type: application/json');

// Enable error reporting for debugging
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Include database connection
include('../config/db.php');

// Initialize an array to store the counts
$data = [
    'billing' => 0,
    'cart' => 0,
    'category' => 0,
    'orders' => 0,
    'prescription' => 0,
    'product' => 0,
    'review' => 0,
    'shipping' => 0,
    'subcategory' => 0,
    'trackMedicine' => 0,
    'contact_messages' => 0
];

// Define the queries to get the counts
$queries = [
    'billing' => "SELECT COUNT(*) as count FROM Billing",
    'cart' => "SELECT COUNT(*) as count FROM Cart",
    'category' => "SELECT COUNT(*) as count FROM Category",
    'orders' => "SELECT COUNT(*) as count FROM Orders",
    'prescription' => "SELECT COUNT(*) as count FROM Prescription",
    'product' => "SELECT COUNT(*) as count FROM Product",
    'review' => "SELECT COUNT(*) as count FROM Review",
    'shipping' => "SELECT COUNT(*) as count FROM Shipping",
    'subcategory' => "SELECT COUNT(*) as count FROM Subcategory",
    'trackMedicine' => "SELECT COUNT(*) as count FROM TrackMedicine",
    'contact_messages' => "SELECT COUNT(*) as count FROM contact_messages"
];

// Execute each query and store the result in the $data array
foreach ($queries as $key => $query) {
    $result = mysqli_query($conn, $query);
    if ($result) {
        $row = mysqli_fetch_assoc($result);
        $data[$key] = $row['count'];
    } else {
        error_log("Error executing query for $key: " . mysqli_error($conn));
    }
}

// Return JSON response
echo json_encode($data);
?>