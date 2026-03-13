<?php
$conn = new mysqli("localhost", "root", "vipul", "costmer");

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
?>