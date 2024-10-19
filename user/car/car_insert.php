<?php
include("../connectdb.php");
echo "You are connected<br>";

if (isset($_POST['car_register'])) {
    // Validate and sanitize inputs
    $register = !empty($_POST['register']) ? $conn->real_escape_string(trim($_POST['register'])) : null;
    $color = !empty($_POST['color']) ? $conn->real_escape_string(trim($_POST['color'])) : null;
    $model_year = !empty($_POST['model_year']) ? $conn->real_escape_string(trim($_POST['model_year'])) : null;
    $owner_id = !empty($_POST['owner_id']) ? $conn->real_escape_string(trim($_POST['owner_id'])) : null;

    // Check if all fields are filled
    if ($register && $color && $model_year && $owner_id) {
        // Check if the owner_id exists in the person table
        $owner_check_sql = "SELECT * FROM person WHERE ssn = ?";
        $owner_stmt = $conn->prepare($owner_check_sql);
        $owner_stmt->bind_param("s", $owner_id);
        $owner_stmt->execute();
        $owner_result = $owner_stmt->get_result();

        if ($owner_result->num_rows > 0) {
            // Owner exists, proceed with car insertion
            $stmt = $conn->prepare("INSERT INTO car (register, color, model_year, owner_id) VALUES (?, ?, ?, ?)");
            
            if ($stmt) {
                $stmt->bind_param("ssss", $register, $color, $model_year, $owner_id);

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
            echo "Owner ID does not exist in the person table.";
        }

        $owner_stmt->close();
    } else {
        echo "<h1>Press Back Arrow to return 🔙</h1>";
    }
    // Redirect to the same page
    header("Location: " . $_SERVER['PHP_SELF']);
    exit; // Ensure no further code is executed
}

// Display success message if it exists
if (isset($_SESSION['success_message'])) {
    echo "<h2 class='text-green-600'>" . $_SESSION['success_message'] . "</h2>";
    unset($_SESSION['success_message']); // Clear message after displaying
}


// Close connection if it was created
if ($conn) {
    $conn->close();
}
?>
