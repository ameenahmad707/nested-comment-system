<?php
$servername = "localhost"; // Change if needed
$username = "root"; // Change to your database username
$password = ""; // Change to your database password
$dbname = "conteect"; // Change to your database name

// Create a database connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check the connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Set character encoding to UTF-8
$conn->set_charset("utf8");
?>
