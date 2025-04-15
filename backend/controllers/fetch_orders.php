<?php
session_start();
// Enable error reporting for debugging
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Include database connection
include('../config/db.php');
$query = "SELECT Orders.Order_id, Customer.name AS customer_name, Product.Pro_name, Orders.Quantity, Orders.Total_Amount, Orders.Order_Date
          FROM Orders
          JOIN Customer ON Orders.id = Customer.id
          JOIN Product ON Orders.Product_id = Product.Product_id
          ORDER BY Orders.Order_id DESC";

$result = mysqli_query($conn, $query);

$orders = [];

while ($row = mysqli_fetch_assoc($result)) {
    $orders[] = $row;
}

echo json_encode($orders);
?>
