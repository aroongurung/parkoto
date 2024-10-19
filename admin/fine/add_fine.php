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

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $person = $_POST['person'];
    $car = $_POST['car'];
    $date = $_POST['date'];
    $amount = $_POST['amount'];
    $reason = $_POST['reason'];

    // Validate person exists
    $checkPerson = "SELECT * FROM person WHERE ssn = ?";
    $stmt = mysqli_prepare($conn, $checkPerson);
    mysqli_stmt_bind_param($stmt, "s", $person);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);

    if (mysqli_num_rows($result) == 0) {
        die("Person does not exist.");
    }

    // Insert the fine
    $sql = "INSERT INTO fine (person, car, date, amount, reason) VALUES (?, ?, ?, ?, ?)";
    $stmt = mysqli_prepare($conn, $sql);
    mysqli_stmt_bind_param($stmt, "sssss", $person, $car, $date, $amount, $reason);

    if (mysqli_stmt_execute($stmt)) {
        header("Location: fine_dashboard.php");
        exit;
    } else {
        die("Error: " . mysqli_error($conn));
    }
}
?>
