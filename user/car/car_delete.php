<?php
include("../connectdb.php"); // Ensure this file connects to your database

if (isset($_GET['id'])) {
    $car_id = $_GET['id'];

    // Delete query
    $delete_sql = "DELETE FROM car WHERE id = '$car_id'";
    if ($conn->query($delete_sql)) {
        header("Location: car_query.php"); // Redirect back to the display page
        exit;
    } else {
        echo "Error deleting car: " . $conn->error;
    }
} else {
    die("No car ID provided.");
}

$conn->close();
?>
