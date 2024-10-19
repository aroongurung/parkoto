<?php
include("../connectdb.php");

if (isset($_POST['person_register'])) {
    // Validate and sanitize inputs
    $ssn = !empty($_POST['ssn']) ? $conn->real_escape_string(trim($_POST['ssn'])) : null;
    $person_name = !empty($_POST['person_name']) ? $conn->real_escape_string(trim($_POST['person_name'])) : null;
    $person_address = !empty($_POST['person_address']) ? $conn->real_escape_string(trim($_POST['person_address'])) : null;
    $phone_number = !empty($_POST['phone_number']) ? $conn->real_escape_string(trim($_POST['phone_number'])) : null;

    // Check if all fields are filled
    if ($ssn && $person_name && $person_address && $phone_number) {
        // Use prepared statements
        $stmt = $conn->prepare("INSERT INTO person (ssn, person_name, person_address, phone_number) VALUES (?, ?, ?, ?)");
        
        if ($stmt) {
            $stmt->bind_param("ssss", $ssn, $person_name, $person_address, $phone_number);

            if ($stmt->execute()) {
                echo "Data Submitted Successfully";
            } else {
                echo "Data Not Submitted: " . $stmt->error;
            }

            $stmt->close();
        } else {
            echo "Preparation of statement failed: " . $conn->error;
        }
    } else {
        echo "<h1>Press Back Arrow to return 🔙</h1>";
    }
}

// Close connection if it was created
if ($conn) {
    $conn->close();
}
?>
