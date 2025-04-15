<?php
session_start();
// Enable error reporting for debugging
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Include database connection
include('../config/db.php');
//include 'config.php';
$notificationId = $_POST['notification_id'];
mysqli_query($conn, "UPDATE Notifications SET Status = 'read' WHERE Notification_id = '$notificationId'");
?>