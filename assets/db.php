<?php
// Database configuration
$host     = "localhost";           // Database host
$username = "your_db_username";    // Database username
$password = "your_db_password";    // Database password
$database = "your_database_name";  // Database name

// Create a new MySQLi connection
$conn = new mysqli($host, $username, $password, $database);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// (Optional) Set the character set to utf8mb4 for full Unicode support
$conn->set_charset("utf8mb4");
?>
