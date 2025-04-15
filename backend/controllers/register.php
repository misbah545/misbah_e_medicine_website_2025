<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Include the database connection file
include('../config/db.php');

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Collect the form data
    $name = mysqli_real_escape_string($conn, $_POST['register_customer_name']);
    $email = mysqli_real_escape_string($conn, $_POST['register_customer_email']);
    $password = mysqli_real_escape_string($conn, $_POST['register_customer_password']);
    $gender = mysqli_real_escape_string($conn, $_POST['register_customer_gender']);
    $address = mysqli_real_escape_string($conn, $_POST['register_customer_address']);
    $phone = mysqli_real_escape_string($conn, $_POST['register_customer_phone']);
    
    // Server-side validation
    $errors = [];

    // Check if the name is empty
    if (empty($name)) {
        $errors[] = "Full Name is required";
    }

    // Check if the email is valid
    if (empty($email) || !filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $errors[] = "Please enter a valid email address";
    }

    // Check if the password is at least 6 characters long
    if (empty($password) || strlen($password) < 6) {
        $errors[] = "Password must be at least 6 characters long";
    }

    // Check if the phone number is valid
    if (empty($phone) || !preg_match("/^[0-9]{10}$/", $phone)) {
        $errors[] = "Please enter a valid 10-digit phone number";
    }

    // Check if the address is empty
    if (empty($address)) {
        $errors[] = "Address is required";
    }

    // Check if the email already exists in the database
    $check_email_query = "SELECT * FROM Customer WHERE email = '$email'";
    $result = mysqli_query($conn, $check_email_query);

    if (mysqli_num_rows($result) > 0) {
        $errors[] = "Email is already registered. Please use a different email.";
    }

    // If there are errors, return them as JSON
    if (count($errors) > 0) {
        echo json_encode(["status" => "error", "messages" => $errors]);
    } else {
        // Encrypt the password using password_hash for security
        $hashed_password = password_hash($password, PASSWORD_DEFAULT);

        // Insert the form data into the database
        $query = "INSERT INTO Customer (name, email, password, gender, address, phone) VALUES ('$name', '$email', '$hashed_password', '$gender', '$address', '$phone')";
        
        if (mysqli_query($conn, $query)) {
            echo json_encode(["status" => "success", "message" => "Registration successful!"]);
        } else {
            echo json_encode(["status" => "error", "messages" => ["Error: " . mysqli_error($conn)]]);
        }
    }
}

// Close the database connection
mysqli_close($conn);
?>