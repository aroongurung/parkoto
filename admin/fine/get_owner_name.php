<?php
session_start();
if (!isset($_SESSION['user_role']) || $_SESSION['user_role'] !== 'admin') {
    header("Location: login.php");
    exit;
}
include("../../connectdb.php");

if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}

if (isset($_GET['owner_id'])) {
    $ownerId = $_GET['owner_id'];

    $sql = "SELECT person_name FROM person WHERE ssn = ?";
    $stmt = mysqli_prepare($conn, $sql);
    mysqli_stmt_bind_param($stmt, "s", $ownerId);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);

    if ($row = mysqli_fetch_assoc($result)) {
        echo json_encode(['person_name' => $row['person_name']]);
    } else {
        echo json_encode(['person_name' => null]);
    }
} else {
    echo json_encode(['person_name' => null]);
}
?>
