<?php
include("../connectdb.php"); // Connect to the database

if (isset($_GET['ssn'])) {
    $person_id = $_GET['ssn']; // Get the person's SSN from the URL

    // Fetch current person data
    $sql = "SELECT * FROM person WHERE ssn = '$person_id'";
    $result = $conn->query($sql);
    $person = $result->fetch_assoc();

    if (!$person) {
        die("Person not found.");
    }
} else {
    die("No person ID provided.");
}

if (isset($_POST['update_person'])) {
    $ssn = $_POST['ssn'];
    $person_name = $_POST['person_name'];
    $person_address = $_POST['person_address'];
    $phone_number = $_POST['phone_number'];

    // Fetch the current SSN
    $current_ssn = $person['ssn'];

    // Start a transaction
    $conn->begin_transaction();

    try {
        // If the SSN is changing, update the car table first
        if ($current_ssn !== $ssn) {
            // Update the owner_id in the car table
            $update_car_sql = "UPDATE car SET owner_id = ? WHERE owner_id = ?";
            $car_stmt = $conn->prepare($update_car_sql);
            $car_stmt->bind_param("ss", $ssn, $current_ssn);
            $car_stmt->execute();
        }

        // Prepare update statement for person
        $stmt = $conn->prepare("UPDATE person SET ssn = ?, person_name = ?, person_address = ?, phone_number = ? WHERE ssn = ?");
        $stmt->bind_param("sssss", $ssn, $person_name, $person_address, $phone_number, $current_ssn);
        $stmt->execute();

        // Commit the transaction
        $conn->commit();

        header("Location: person_query.php");
        exit;
    } catch (mysqli_sql_exception $exception) {
        // Rollback the transaction if something failed
        $conn->rollback();
        echo "Error updating Person: " . $exception->getMessage();
    }
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Update Person</title>
</head>
<body>
    <h1>Update Person Details</h1>
    <form action="person_update.php?ssn=<?php echo htmlspecialchars($person_id); ?>" method="post">
        <label for="ssn">SSN:</label>
        <input type="text" name="ssn" value="<?php echo htmlspecialchars($person['ssn']); ?>" required>
        <br>
        <label for="person_name">Name:</label>
        <input type="text" name="person_name" value="<?php echo htmlspecialchars($person['person_name']); ?>" required>
        <br>
        <label for="person_address">Address:</label>
        <input type="text" name="person_address" value="<?php echo htmlspecialchars($person['person_address']); ?>" required>
        <br>
        <label for="phone_number">Phone Number:</label>
        <input type="text" name="phone_number" value="<?php echo htmlspecialchars($person['phone_number']); ?>" required>
        <br>
        <button type="submit" name="update_person">Update</button>
    </form>
</body>
</html>

<?php
$conn->close();
?>
