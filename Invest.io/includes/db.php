<?php
// Database connection settings
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "invest.io";

// Static API key for authentication
define('API_SECRET_KEY', 'ghfkffu6378382826hhdjgk');

// Create a database connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
?>

