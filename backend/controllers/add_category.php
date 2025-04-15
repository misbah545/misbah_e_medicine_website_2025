<?php
session_start();
// Enable error reporting for debugging
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Include database connection
include('../config/db.php');

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Use the correct key sent from the frontend
    $categoryName = isset($_POST['Category_Name']) ? $_POST['Category_Name'] : '';

    if (empty($categoryName)) {
        echo "Error: Category name is required!";
        exit;
    }

    // Debugging: Log the received category name
    error_log("Category Name: " . $categoryName);

    // Insert the category into the database
    $query = "INSERT INTO Category (Category_Name) VALUES (?)";
    $stmt = mysqli_prepare($conn, $query);
    mysqli_stmt_bind_param($stmt, "s", $categoryName);

    if (mysqli_stmt_execute($stmt)) {
        echo "Category added successfully!";
    } else {
        echo "Error adding category: " . mysqli_error($conn);
    }

    mysqli_stmt_close($stmt);
    mysqli_close($conn);
}
?>