<?php
session_start();
// Check if the user is logged in as admin
if (!isset($_SESSION['user_role']) || $_SESSION['user_role'] !== 'admin') {
    header("Location: login.php");
    exit;
}

// Include database connection
include("../../connectdb.php");

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (empty($_POST['user_id'])) {
        echo "No person selected.";
        exit;
    }

    $user_id = $_POST['user_id'];
    $ssn = mysqli_real_escape_string($conn, $_POST['ssn']);
    $person_name = mysqli_real_escape_string($conn, $_POST['person_name']); // Assuming this is passed from the form

    // Prepare the SQL statement
    $insert_sql = "INSERT INTO person (person_name, ssn, user_id) VALUES (?, ?, ?)";
    $insert_stmt = mysqli_prepare($conn, $insert_sql);

    // Check if preparation was successful
    if ($insert_stmt === false) {
        echo "Error preparing statement: " . mysqli_error($conn);
        exit;
    }

    mysqli_stmt_bind_param($insert_stmt, "ssi", $person_name, $ssn, $user_id);

    // Execute the statement
    if (mysqli_stmt_execute($insert_stmt)) {
        $_SESSION['success_message'] = "Person registered successfully!";
        header("Location: person_dashboard.php");
        exit;
    } else {
        echo "Error executing statement: " . mysqli_stmt_error($insert_stmt);
    }
}

mysqli_close($conn);
?>
