<?php
// Database connection settings for local development
$servername = "localhost";
$username = "root";
$password = "";  
$dbname = "student_portal";

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
?>
