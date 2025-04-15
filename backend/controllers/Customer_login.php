<?php
session_start();

// Enable error reporting for debugging
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Include database connection
include('../config/db.php');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Ensure required fields exist
    if (!isset($_POST['email']) || !isset($_POST['password'])) {
        echo json_encode(["status" => "error", "message" => "Both email and password are required."]);
        exit();
    }

    // Sanitize input
    $email = trim($_POST['email']);
    $password = trim($_POST['password']);

    if (empty($email) || empty($password)) {
        echo json_encode(["status" => "error", "message" => "Both fields are required."]);
        exit();
    }

    // Use prepared statements for security
    $query = "SELECT id, name, password FROM Customer WHERE email = ? LIMIT 1";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows === 1) {
        $user = $result->fetch_assoc();

        // Verify password
        if (password_verify($password, $user['password'])) {
            $_SESSION['customer_id'] = $user['id'];  // Store 'id' from Customer table
            $_SESSION['customer_name'] = $user['name'];

            echo json_encode(["status" => "success", "message" => "Login successful! Redirecting to homepage..."]);
            exit();
        } else {
            echo json_encode(["status" => "error", "message" => "Incorrect password. Please try again."]);
            exit();
        }
    } else {
        echo json_encode(["status" => "error", "message" => "No account found with this email. Please register."]);
        exit();
    }

    // Close statement
    $stmt->close();
}

// Close the database connection
$conn->close();
?>