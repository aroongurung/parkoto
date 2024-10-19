<?php
session_start();
include("../../connectdb.php");

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $register = mysqli_real_escape_string($conn, $_POST['register']); // Use the correct POST variable

    // Delete car from the database
    $sql = "DELETE FROM car WHERE register='$register'"; // Use register for the WHERE clause
    
    if (mysqli_query($conn, $sql)) {
        $_SESSION['success_message'] = "Car deleted successfully!"; // Optional success message
        header("Location: car_dashboard.php");
        exit;
    } else {
        echo "Error deleting car: " . mysqli_error($conn);
    }
}
?>
