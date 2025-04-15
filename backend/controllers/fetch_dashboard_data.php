<?php
session_start();
// Enable error reporting for debugging
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Include database connection
include('../config/db.php');

// Query to get count of each table
$tables = [
    "Billing", "Cart", "Category", "Customer", "Orders",
    "Prescription", "Product", "Review", "Shipping", 
    "Subcategory", "TrackMedicine", "contact_messages"
];

$data = [];

foreach ($tables as $table) {
    $query = "SELECT COUNT(*) as count FROM $table";
    $result = mysqli_query($conn, $query);
    $row = mysqli_fetch_assoc($result);
    $data[$table] = $row['count'];
}

// Return JSON response
echo json_encode($data);
?>
