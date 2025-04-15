<?php
session_start();
// Enable error reporting for debugging
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Include database connection
include('../config/db.php');
//include("../../backend/config.php");

$query = "SELECT Product.Product_id, Product.Pro_name, Product.Description, Product.Price, Product.Stock, Category.Category_Name, Subcategory.SubCategory_Name
          FROM Product
          JOIN Category ON Product.Category_id = Category.Category_id
          JOIN Subcategory ON Product.SubCategory_id = Subcategory.SubCategory_id
          ORDER BY Product.Pro_name";

$result = mysqli_query($conn, $query);

$products = [];

while ($row = mysqli_fetch_assoc($result)) {
    $products[] = $row;
}

echo json_encode($products);
?>
