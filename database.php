<?php
$servername = "localhost:3307";
$username = "root"; // Default username for XAMPP
$password = ""; // Default password for XAMPP (empty)
$dbname = "attendance_system"; // Your database name

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error); // Detailed error message
} else {
    echo "connected";
}
?>
