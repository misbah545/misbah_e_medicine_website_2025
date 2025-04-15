<?php
session_start();
error_reporting(E_ALL);
ini_set('display_errors', 1);
include('../config/db.php');

$query = "SELECT Category_id, Category_Name FROM Category ORDER BY Category_Name";
$result = mysqli_query($conn, $query);

if (!$result) {
    die(json_encode(["error" => "Error fetching categories: " . mysqli_error($conn)]));
}

$categories = [];
while ($row = mysqli_fetch_assoc($result)) {
    $categories[] = $row;
}

// Wrap response inside an object
echo json_encode(["categories" => $categories]);
?>