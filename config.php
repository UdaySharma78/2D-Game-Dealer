<?php
// Database Configuration - 2D Car Dealer
$host = "localhost";
$user = "root";
$pass = "vipul";
$db   = "car_dealer";

$conn = mysqli_connect($host, $user, $pass, $db);

if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}

// Start session if not already started
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
?>
