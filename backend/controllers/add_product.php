<?php
session_start();
header('Content-Type: application/json');

// Enable error reporting for debugging
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Include database connection
include('../config/db.php');

// Debugging: Log the received POST data
error_log("POST Data: " . print_r($_POST, true));

if (!isset($_POST['Pro_name']) || !isset($_POST['Description']) || !isset($_POST['Price']) || !isset($_POST['Stock']) || !isset($_POST['Category_id']) || !isset($_POST['SubCategory_id'])) {
    echo json_encode(["error" => "All fields are required!"]);
    exit;
}

$pro_name = $_POST['Pro_name'];
$description = $_POST['Description'];
$price = floatval($_POST['Price']); // Ensure price is a float
$stock = intval($_POST['Stock']); // Ensure stock is an integer
$category_id = intval($_POST['Category_id']); // Ensure category_id is an integer
$subcategory_id = intval($_POST['SubCategory_id']); // Ensure subcategory_id is an integer

// Optional field for Product_img
$product_img = isset($_POST['Product_img']) ? $_POST['Product_img'] : null;

// Debugging: Log the processed variables
error_log("Processed Data: Pro_name=$pro_name, Description=$description, Price=$price, Stock=$stock, Category_id=$category_id, SubCategory_id=$subcategory_id, Product_img=$product_img");

$query = "INSERT INTO Product (Pro_name, Description, Price, Stock, Category_id, SubCategory_id, Product_img) VALUES (?, ?, ?, ?, ?, ?, ?)";
$stmt = $conn->prepare($query);
$stmt->bind_param("ssdiiis", $pro_name, $description, $price, $stock, $category_id, $subcategory_id, $product_img);

if ($stmt->execute()) {
    echo json_encode(["success" => "Product added successfully!"]);
} else {
    echo json_encode(["error" => "Failed to add product: " . $conn->error]);
}

// Debugging: Log the query execution result
error_log("Query Execution: " . ($stmt->affected_rows > 0 ? "Success" : "Failure"));

$stmt->close();
$conn->close();
?>