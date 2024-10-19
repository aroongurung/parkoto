<?php
session_start();
include("connectdb.php");

if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $person_name = $conn->real_escape_string(trim($_POST['person_name']));
    $user_name = $conn->real_escape_string(trim($_POST['user_name']));
    $email = $conn->real_escape_string(trim($_POST['email']));
    $user_password = $conn->real_escape_string(trim($_POST['user_password']));
    $person_address = $conn->real_escape_string(trim($_POST['person_address']));
    $phone_number = $conn->real_escape_string(trim($_POST['phone_number']));
    $rol = $conn->real_escape_string(trim($_POST['rol']));

    $hashed_password = password_hash($user_password, PASSWORD_DEFAULT);

    $stmt = $conn->prepare("INSERT INTO User (person_name, user_name, email, user_password, person_address, phone_number, rol) VALUES (?, ?, ?, ?, ?, ?, ?)");

    if ($stmt) {
        $stmt->bind_param("sssssss", $person_name, $user_name, $email, $hashed_password, $person_address, $phone_number, $rol);

        try {
            if ($stmt->execute()) {
                $_SESSION['success_message'] = "User registered successfully. You will be redirected to the login page shortly.";

                // Redirect to the login page
                header("Location: login.php?role=" . $rol);
                exit();
            }
        } catch (mysqli_sql_exception $e) {
            $_SESSION['success_message'] = "Error: " . $e->getMessage();
            header("Location: signup.php");
            exit();
        }

        $stmt->close();
    } else {
        $_SESSION['success_message'] = "Preparation of statement failed: " . $conn->error;
        header("Location: signup.php");
        exit();
    }
}

if ($conn) {
    $conn->close();
}
