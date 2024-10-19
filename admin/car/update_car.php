<?php
session_start();
include("../../connectdb.php");

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $car_id = $_POST['car_id'];
    $register = mysqli_real_escape_string($conn, $_POST['register']);
    $color = mysqli_real_escape_string($conn, $_POST['color']);
    $model_year = mysqli_real_escape_string($conn, $_POST['model_year']);
    $owner_id = mysqli_real_escape_string($conn, $_POST['owner_id']);

    // Update car in the database
    $sql = "UPDATE car SET color='$color', model_year='$model_year', owner_id='$owner_id' WHERE register='$register'";
    
    if (mysqli_query($conn, $sql)) {
        header("Location: car_dashboard.php");
        exit;
    } else {
        echo "Error updating car: " . mysqli_error($conn);
    }
}
?>
