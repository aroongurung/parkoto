<?php
session_start();
include("../../connectdb.php");

if (isset($_GET['query'])) {
    $query = mysqli_real_escape_string($conn, $_GET['query']);

    $sql = "SELECT user_id, person_name FROM user WHERE person_name LIKE '%$query%'";
    $result = mysqli_query($conn, $sql);
    
    $suggestions = [];
    while ($row = mysqli_fetch_assoc($result)) {
        $suggestions[] = $row;
    }

    echo json_encode($suggestions);
}

mysqli_close($conn);
?>
