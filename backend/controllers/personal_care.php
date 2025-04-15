<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Database connection
include('../config/db.php');

// Fetch only supplements category (Category_id = 3)
$sql = "SELECT Product_id, Pro_name, Description, Price, Product_img 
        FROM Product 
        WHERE Category_id = 3";

$result = mysqli_query($conn, $sql);

$products = [];
while ($row = mysqli_fetch_assoc($result)) {
    $row['Product_img'] = "../images/" . $row['Product_img']; 
    $products[] = $row;
}

header('Content-Type: application/json');
echo json_encode($products);
?>
