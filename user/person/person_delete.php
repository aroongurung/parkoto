<?php
include("../connectdb.php"); // Ensure this file connects to your database

if (isset($_GET['ssn'])) {
    $person_id = $_GET['ssn'];

    // Optional: Check for related entries in the car table
    $check_sql = "SELECT * FROM car WHERE owner_id = ?";
    $check_stmt = $conn->prepare($check_sql);
    $check_stmt->bind_param("s", $person_id);
    $check_stmt->execute();
    $check_result = $check_stmt->get_result();

    // If there are related records, delete them first
    if ($check_result->num_rows > 0) {
        $delete_car_sql = "DELETE FROM car WHERE owner_id = ?";
        $delete_car_stmt = $conn->prepare($delete_car_sql);
        $delete_car_stmt->bind_param("s", $person_id);
        $delete_car_stmt->execute();
        $delete_car_stmt->close();
    }

    // Now delete the person record
    $delete_sql = "DELETE FROM person WHERE ssn = ?";
    $delete_stmt = $conn->prepare($delete_sql);
    $delete_stmt->bind_param("s", $person_id);

    if ($delete_stmt->execute()) {
        header("Location: person_query.php"); // Redirect back to the display page
        exit;
    } else {
        echo "Error deleting person: " . $delete_stmt->error;
    }

    $delete_stmt->close();
} else {
    die("No Person ID provided.");
}


$conn->close();
?>
