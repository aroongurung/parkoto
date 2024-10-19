<?php
session_start();
include("../connectdb.php");

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $title = mysqli_real_escape_string($conn, $_POST['title']);
    $date = mysqli_real_escape_string($conn, $_POST['date']);
    $description = mysqli_real_escape_string($conn, $_POST['description']);
    
    $query = "INSERT INTO appointment (title, date, description) VALUES ('$title', '$date', '$description')";
    if (mysqli_query($conn, $query)) {
        header("Location: admin_home.php");
    } else {
        echo "Error: " . mysqli_error($conn);
    }
}
