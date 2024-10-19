<?php
session_start();
include("../../connectdb.php");

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $person_id = $_POST['person_id'];
    $person_name = mysqli_real_escape_string($conn, $_POST['person_name']);
    $ssn = mysqli_real_escape_string($conn, $_POST['ssn']);
    $user_id = mysqli_real_escape_string($conn, $_POST['user_id']);

    // Update person in the database
    $sql = "UPDATE person SET person_name='$person_name', ssn='$ssn', user_id='$user_id' WHERE id='$person_id'";
    
    if (mysqli_query($conn, $sql)) {
        header("Location: person_dashboard.php");
        exit;
    } else {
        echo "Error updating person: " . mysqli_error($conn);
    }
}
?>
