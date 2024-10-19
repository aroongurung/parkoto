<?php
session_start();
include("../../connectdb.php");

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Get and sanitize input
    $person_name = mysqli_real_escape_string($conn, $_POST['person_name']);
    $user_name = mysqli_real_escape_string($conn, $_POST['user_name']);
    $email = mysqli_real_escape_string($conn, $_POST['email']);
    $user_password = password_hash($_POST['user_password'], PASSWORD_DEFAULT); // Hash the password
    $person_address = mysqli_real_escape_string($conn, $_POST['person_address']);
    $phone_number = mysqli_real_escape_string($conn, $_POST['phone_number']);
    $rol = mysqli_real_escape_string($conn, $_POST['rol']);

    // Insert into the database
    $sql = "INSERT INTO user (person_name, user_name, email, user_password, person_address, phone_number, rol) 
            VALUES ('$person_name', '$user_name', '$email', '$user_password', '$person_address', '$phone_number', '$rol')";

    if (mysqli_query($conn, $sql)) {
        // Set success message in session
        $_SESSION['message'] = "User created successfully!";
        // Redirect back to user dashboard
        header("Location: user_dashboard.php");
        exit;
    } else {
        echo "Error: " . mysqli_error($conn);
    }
}
?>
