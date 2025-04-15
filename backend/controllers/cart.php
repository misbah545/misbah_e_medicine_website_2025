<?php
session_start();
include('../config/db.php'); // Database connection

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Ensure customer is logged in
    if (!isset($_SESSION['customer_id'])) {
        echo json_encode(["status" => "error", "message" => "User not logged in"]);
        exit();
    }

    $product_id = $_POST['product_id'];
    $quantity = $_POST['quantity'];
    $customer_id = $_SESSION['customer_id'];

    // Fetch product price
    $query = "SELECT Price FROM Product WHERE Product_id = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("i", $product_id);
    $stmt->execute();
    $result = $stmt->get_result();
    $product = $result->fetch_assoc();

    if (!$product) {
        echo json_encode(["status" => "error", "message" => "Product not found"]);
        exit();
    }

    $price = $product['Price'];
    $total_amount = $price * $quantity;

    // Check if product already in Orders (like a cart)
    $checkQuery = "SELECT * FROM Orders WHERE id = ? AND Product_id = ? AND Order_Status = 'Pending'";
    $stmt = $conn->prepare($checkQuery);
    $stmt->bind_param("ii", $customer_id, $product_id);
    $stmt->execute();
    $checkResult = $stmt->get_result();

    if ($checkResult->num_rows > 0) {
        // Update quantity if already exists
        $updateQuery = "UPDATE Orders SET Quantity = Quantity + ?, Total_Amount = Total_Amount + ? WHERE id = ? AND Product_id = ? AND Order_Status = 'Pending'";
        $stmt = $conn->prepare($updateQuery);
        $stmt->bind_param("idii", $quantity, $total_amount, $customer_id, $product_id);
    } else {
        // Insert new item into Orders (like cart)
        $insertQuery = "INSERT INTO Orders (id, Product_id, Price, Quantity, Total_Amount, Order_Status) 
                        VALUES (?, ?, ?, ?, ?, 'Pending')";
        $stmt = $conn->prepare($insertQuery);
        $stmt->bind_param("iidid", $customer_id, $product_id, $price, $quantity, $total_amount);
    }

    if ($stmt->execute()) {
        echo json_encode(["status" => "success", "message" => "Added to cart"]);
    } else {
        echo json_encode(["status" => "error", "message" => "Failed to add to cart"]);
    }

    $stmt->close();
    $conn->close();
} else {
    echo json_encode(["status" => "error", "message" => "Invalid request"]);
}
?>