<?php
session_start();
// Enable error reporting for debugging
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Include database connection
include('../config/db.php');

// Check if category_id is provided
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['category_id'])) {
    $category_id = intval($_POST['category_id']); // Sanitize input

    // Delete query
    $query = "DELETE FROM Category WHERE Category_id = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("i", $category_id);

    if ($stmt->execute()) {
        echo json_encode(["status" => "success", "message" => "Category deleted successfully!"]);
    } else {
        echo json_encode(["status" => "error", "message" => "Failed to delete category."]);
    }

    $stmt->close();
} else {
    echo json_encode(["status" => "error", "message" => "Invalid request!"]);
}
?>