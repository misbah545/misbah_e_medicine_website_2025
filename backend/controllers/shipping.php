<?php
session_start();
header("Content-Type: application/json");
error_reporting(E_ALL);
ini_set('display_errors', 1);

require('../config/db.php');
require_once __DIR__ . '/../../vendor/autoload.php';

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

if (!isset($_SESSION['customer_id'])) {
    echo json_encode(["status" => "error", "message" => "Session expired. Please login again."]);
    exit;
}

$customer_id = $_SESSION['customer_id']; // Fix: Fetch customer_id from session

// Fetch customer email from database
$email_query = "SELECT email FROM Customer WHERE id = ?";
$stmt = $conn->prepare($email_query);
$stmt->bind_param("i", $customer_id);
$stmt->execute();
$email_result = $stmt->get_result();
$stmt->close();

if ($email_result->num_rows === 0) {
    echo json_encode(["status" => "error", "message" => "Customer email not found."]);
    exit;
}

$customer_data = $email_result->fetch_assoc();
$customer_email = $customer_data['email'];

// Debugging: Check if email is fetched correctly
// var_dump($customer_email); exit;

function sendMail($to, $subject, $message) {
    $mail = new PHPMailer(true);

    try {
        $mail->isSMTP();
        $mail->Host = 'smtp.gmail.com';
        $mail->SMTPAuth = true;
        $mail->Username = 'emedicinewebsite@gmail.com'; // Your Gmail
        $mail->Password = 'jpqt dkaw zhgs qclr'; // Gmail App Password (Replace)
        $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
        $mail->Port = 587;

        $mail->setFrom('no-reply@emedicine.com', 'E-Medicine Website');
        $mail->addAddress($to);
        $mail->Subject = $subject;
        $mail->Body = $message;
        $mail->isHTML(false);
        $mail->CharSet = 'UTF-8';

        // Enable Debugging (For Testing Only)
        // $mail->SMTPDebug = 2;

        $mail->send();
        return true;
    } catch (Exception $e) {
        error_log("PHPMailer Error: " . $mail->ErrorInfo); // Log Error
        return $mail->ErrorInfo; // Return Error Message
    }
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (!isset($_POST['shipp_address'], $_POST['shipp_city'], $_POST['shipp_state'], $_POST['shipp_zip'], $_POST['shipp_country'])) {
        echo json_encode(["status" => "error", "message" => "Missing required fields."]);
        exit;
    }

    $shipp_address = mysqli_real_escape_string($conn, $_POST['shipp_address']);
    $shipp_city = mysqli_real_escape_string($conn, $_POST['shipp_city']);
    $shipp_state = mysqli_real_escape_string($conn, $_POST['shipp_state']);
    $shipp_zip = mysqli_real_escape_string($conn, $_POST['shipp_zip']);
    $shipp_country = mysqli_real_escape_string($conn, $_POST['shipp_country']);
    $shipp_date = date("Y-m-d");
    $delivery_date = date('Y-m-d', strtotime('+2 days'));

    // Insert into Shipping table
    $sql = "INSERT INTO Shipping (Customer_id, Shipp_Address, Shipp_City, Shipp_State, Shipp_Zip, Shipp_Country, Shipp_Date, Delivery_Date)
            VALUES (?, ?, ?, ?, ?, ?, ?, ?)";
    
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("isssssss", $customer_id, $shipp_address, $shipp_city, $shipp_state, $shipp_zip, $shipp_country, $shipp_date, $delivery_date);
    
    if ($stmt->execute()) {
        $stmt->close();

        // Prepare email
        $subject = "Shipping Details Confirmed";
        $message = "Dear Customer,\n\nYour order will be delivered to:\n\n"
                 . "$shipp_address, $shipp_city, $shipp_state - $shipp_zip, $shipp_country.\n\n"
                 . "Estimated Delivery Date: $delivery_date\n\n"
                 . "Thank you for shopping with us!\nE-Medicine Team";

        // Send email
        $emailResult = sendMail($customer_email, $subject, $message);
        
        if ($emailResult === true) {
            echo json_encode(["status" => "success", "message" => "Shipping details saved and email sent."]);
        } else {
            echo json_encode(["status" => "success", "message" => "Shipping details saved, but email failed to send.", "error" => $emailResult]);
        }
        exit;
    } else {
        echo json_encode(["status" => "error", "message" => "Database error: " . mysqli_error($conn)]);
        exit;
    }
}

echo json_encode(["status" => "error", "message" => "Invalid request."]);
exit;
?>
