<?php
session_start();
include("../../connectdb.php");

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Get and sanitize the input values
    $user_id = mysqli_real_escape_string($conn, $_POST['user_id']);
    $person_name = mysqli_real_escape_string($conn, $_POST['person_name']);
    $ssn = mysqli_real_escape_string($conn, $_POST['ssn']);
    $person_id = mysqli_real_escape_string($conn, $_POST['person_id']); // Get the person_id (ssn)

    // Update person in the database
    $sql = "UPDATE person SET person_name='$person_name', ssn='$ssn', user_id='$user_id' WHERE ssn='$person_id'"; // Use ssn as identifier
    
    if (mysqli_query($conn, $sql)) {
        $_SESSION['success_message'] = "Person updated successfully!";
        header("Location: person_dashboard.php");
        exit;
    } else {
        echo "Error updating person: " . mysqli_error($conn);
    }
}
?>
