<?php

// Database configuration

$servername = "localhost"; // Change if your database is hosted elsewhere
$username = "u627118125_123admission"; // Your MySQL username
$password = "123Admission@123"; // Your MySQL password
$database = "u627118125_123admission";
// Create connection
$conn = new mysqli($servername, $username, $password, $database);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Set charset to utf8mb4 (supports emojis and special characters)
$conn->set_charset("utf8mb4");

?>