<?php
$host = "localhost";   // your server (if online, replace with server IP)
$user = "root";        // default user in XAMPP
$pass = "";            // default password in XAMPP
$dbname = "classic_store";

// Create connection
$conn = new mysqli($host, $user, $pass, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
?>
