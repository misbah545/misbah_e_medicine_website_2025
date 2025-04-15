<?php
$servername = "localhost";          // Database server
$username = "phpmyadmin";           // Database username
$password = "Misbah545@shaikh";     // Database password
$dbname = "phpmyadmin";             // Database name

// Create connection
$conn = mysqli_connect($servername, $username, $password, $dbname);

// Check connection
if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}
?>


