<?php
session_start();
// Enable error reporting for debugging
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Include database connection
include('../config/db.php');
//include 'config.php';
$customerId = $_SESSION['user_id'];
$query = "SELECT * FROM Notifications WHERE Customer_id = '$customerId' AND Status = 'unread' ORDER BY Created_at DESC";
$result = mysqli_query($conn, $query);
$notifications = [];
while ($row = mysqli_fetch_assoc($result)) {
    $notifications[] = $row;
}
echo json_encode($notifications);
?>