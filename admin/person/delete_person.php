<?php
session_start();
include("../../connectdb.php");

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Get the person_id from the POST request
    $person_id = $_POST['person_id'];

    // Delete person from the database
    // Assuming person_id is linked to ssn
    $sql = "DELETE FROM person WHERE ssn='$person_id'"; // Use ssn as primary key

    if (mysqli_query($conn, $sql)) {
        $_SESSION['success_message'] = "Person deleted successfully!";
        header("Location: person_dashboard.php");
        exit;
    } else {
        echo "Error deleting person: " . mysqli_error($conn);
    }
}
?>
