<?php 
$db_server = "localhost"; // server address
$db_user = "root"; // MySQL username
$db_pass = ""; // MySQL password
$db_name = "parkoto"; // database name

// Create a new connection
$conn = new mysqli($db_server, $db_user, $db_pass, $db_name);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// echo "Connected to DB"; // Optional debug line

// No need to close the connection here
// $conn->close();
?>
