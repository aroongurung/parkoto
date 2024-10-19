<?php
session_start();
include("../../connectdb.php");

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $user_id = $_POST['user_id'];
    $person_name = mysqli_real_escape_string($conn, $_POST['person_name']);
    $user_name = mysqli_real_escape_string($conn, $_POST['user_name']);
    $email = mysqli_real_escape_string($conn, $_POST['email']);
    $person_address = mysqli_real_escape_string($conn, $_POST['person_address']);
    $phone_number = mysqli_real_escape_string($conn, $_POST['phone_number']);
    $rol = $_POST['rol'];

    // Update user in the database
    $sql = "UPDATE user SET person_name='$person_name', user_name='$user_name', email='$email', person_address='$person_address', phone_number='$phone_number', rol='$rol' WHERE user_id='$user_id'";
    
    if (mysqli_query($conn, $sql)) {
        $_SESSION['message'] = "User updated successfully!";
        header("Location: user_dashboard.php");
        exit;
    } else {
        echo "Error updating user: " . mysqli_error($conn);
    }
}
?>
