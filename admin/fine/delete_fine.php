<?php
session_start();
include("../../connectdb.php");

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $fine_id = $_POST['fine_id'];

    // Delete fine from the database
    $sql = "DELETE FROM fine WHERE id='$fine_id'";
    
    if (mysqli_query($conn, $sql)) {
        header("Location: fine_dashboard.php");
        exit;
    } else {
        echo "Error deleting fine: " . mysqli_error($conn);
    }
}
?>
