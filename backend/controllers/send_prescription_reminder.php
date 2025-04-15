<?php
session_start();
// Enable error reporting for debugging
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Include database connection
include('../config/db.php');
//include 'config.php'; // Database connection
$today = date('Y-m-d');
$query = "SELECT Customer_id, Prescription_Name FROM TrackMedicine WHERE End_date = DATE_ADD('$today', INTERVAL 1 DAY)";
$result = mysqli_query($conn, $query);
while ($row = mysqli_fetch_assoc($result)) {
    $customerId = $row['Customer_id'];
    $message = "Reminder: Your prescription '" . $row['Prescription_Name'] . "' is ending tomorrow.";
    mysqli_query($conn, "INSERT INTO Notifications (Customer_id, Message) VALUES ('$customerId', '$message')");
}
?>
