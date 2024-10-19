<?php
session_start();
include("../../connectdb.php");

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $fine_id = $_POST['fine_id'];
    $person_id = $_POST['person']; // Use 'person' as the key
    $car_id = $_POST['car'];       // Use 'car' as the key
    $date = $_POST['date'];
    $amount = $_POST['amount'];
    $reason = $_POST['reason'];

    // Update fine in the database
    $sql = "UPDATE fine SET person='$person_id', car='$car_id', date='$date', amount='$amount', reason='$reason' WHERE id='$fine_id'";

    if (mysqli_query($conn, $sql)) {
        header("Location: fine_dashboard.php");
        exit;
    } else {
        echo "Error updating fine: " . mysqli_error($conn);
    }
}
?>
