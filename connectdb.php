<?php 
$db_server = "localhost"; // or the server address
$db_user = "root"; // your MySQL username
$db_pass = ""; // your MySQL password
$db_name = "parkoto"; // your database name

// Create a new connection
$conn = new mysqli($db_server, $db_user, $db_pass, $db_name);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
// Test query to check if connection works
$result = mysqli_query($conn, "SELECT * FROM person LIMIT 1");
if (!$result) {
    die("Test query failed: " . mysqli_error($conn));
}

// remove the echo here if don't want to output during the connection phase
// echo "Connected to DB"; // Optional debug line

// No need to close the connection here
// $conn->close();
?>
