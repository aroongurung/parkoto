<?php
session_start();
include("../../connectdb.php");

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $register = mysqli_real_escape_string($conn, $_POST['register']);
    $color = mysqli_real_escape_string($conn, $_POST['color']);
    $model_year = mysqli_real_escape_string($conn, $_POST['model_year']);
    $owner_id = mysqli_real_escape_string($conn, $_POST['owner_id']);

    // Insert new car into the database
    $sql = "INSERT INTO car (register, color, model_year, owner_id) VALUES ('$register', '$color', '$model_year', '$owner_id')";
    
    if (mysqli_query($conn, $sql)) {
        header("Location: car_dashboard.php");
        exit;
    } else {
        echo "Error adding car: " . mysqli_error($conn);
    }
}
?>
