<?php
// Database connection settings
$servername = "localhost";
$username = "root"; // The default user for XAMPP
$password = "yEnma123@"; 
$dbname = "campusfixit"; // Your database name

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
?>
