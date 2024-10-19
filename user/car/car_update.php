<?php
include("../connectdb.php"); // Connect to the database

if (isset($_GET['id'])) {
    $car_id = $_GET['id']; // Get the car ID from the URL

    // Fetch current car data
    $sql = "SELECT * FROM car WHERE id = '$car_id'";
    $result = $conn->query($sql);
    $car = $result->fetch_assoc();

    if (!$car) {
        die("Car not found.");
    }
} else {
    die("No car ID provided.");
}

if (isset($_POST['update_car'])) {
    $register = $_POST['register'];
    $color = $_POST['color'];
    $model_year = $_POST['model_year'];
    $owner_id = $_POST['owner_id'];

    // Prepare update statement
    $stmt = $conn->prepare("UPDATE car SET register = ?, color = ?, model_year = ?, owner_id = ? WHERE id = ?");
    $stmt->bind_param("ssssi", $register, $color, $model_year, $owner_id, $car_id);

    // Fetch the current ssn
    $current_ssn = $person['ssn'];

// Update the owner_id in the car table
    $update_car_sql = "UPDATE car SET owner_id = ? WHERE owner_id = ?";
    $car_stmt = $conn->prepare($update_car_sql);
    $car_stmt->bind_param("ss", $ssn, $current_ssn);
    $car_stmt->execute();


    if ($stmt->execute()) {
        header("Location: car_query.php");
        exit;
    } else {
        echo "Error updating car: " . $stmt->error;
    }
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Update Car</title>
</head>
<body>
    <h1>Update Car Details</h1>
    <form action="car_update.php?id=<?php echo $car_id; ?>" method="post">
        <label for="register">Registration No:</label>
        <input type="text" name="register" value="<?php echo htmlspecialchars($car['register']); ?>" required>
        <br>
        <label for="color">Color:</label>
        <input type="text" name="color" value="<?php echo htmlspecialchars($car['color']); ?>" required>
        <br>
        <label for="model_year">Model Year:</label>
        <input type="text" name="model_year" value="<?php echo htmlspecialchars($car['model_year']); ?>" required>
        <br>
        <label for="owner_id">Owner ID:</label>
        <input type="text" name="owner_id" value="<?php echo htmlspecialchars($car['owner_id']); ?>" required>
        <br>
        <button type="submit" name="update_car">Update</button>
    </form>
</body>
</html>

<?php
$conn->close();
?>
