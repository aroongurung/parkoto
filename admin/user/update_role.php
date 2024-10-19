<?php
session_start();
include("../../connectdb.php");

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $user_id = $_POST['user_id'];
    $new_role = $_POST['rol'];

    // Update the user role
    $sql = "UPDATE user SET rol = '$new_role' WHERE user_id = '$user_id'";
    
    if (mysqli_query($conn, $sql)) {
        // Redirect back to user dashboard
        header("Location: user_dashboard.php");
        exit;
    } else {
        echo "Error: " . mysqli_error($conn);
    }
}
?>
