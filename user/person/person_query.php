<?php
session_start();
include($_SERVER['DOCUMENT_ROOT'] . '/parkoto/connectdb.php');

error_reporting(E_ALL);
ini_set('display_errors', 1);

// Get the input from the form and sanitize it
$personName = isset($_POST['person_name']) ? trim($_POST['person_name']) : '';
$ssn = isset($_POST['ssn']) ? trim($_POST['ssn']) : '';

// Debugging: Output the received values
echo "Person Name: " . htmlspecialchars($personName) . "<br>";
echo "SSN: " . htmlspecialchars($ssn) . "<br>";

// Check for empty values
if (empty($personName) || empty($ssn)) {
    echo "Both fields are required.";
    exit;
}

// Step 1: Fetch person details
$sqlPerson = "SELECT p.person_name, u.person_address, u.phone_number 
              FROM person p 
              JOIN user u ON p.user_id = u.user_id 
              WHERE p.ssn = ? AND p.person_name = ?";
$stmtPerson = $conn->prepare($sqlPerson);
$stmtPerson->bind_param("ss", $ssn, $personName);
$stmtPerson->execute();
$resultPerson = $stmtPerson->get_result();

if ($resultPerson->num_rows === 0) {
    echo "No person found with the provided name and SSN.";
    exit;
}

$personData = $resultPerson->fetch_assoc();
$stmtPerson->close();

// Step 2: Fetch associated car details
$sqlCar = "SELECT c.register, c.color, c.model_year 
           FROM car c 
           WHERE c.owner_id = ?";
$stmtCar = $conn->prepare($sqlCar);
$stmtCar->bind_param("s", $ssn);
$stmtCar->execute();
$resultCar = $stmtCar->get_result();

$cars = [];
while ($row = $resultCar->fetch_assoc()) {
    $cars[] = $row; // Collect all cars for the person
}
$stmtCar->close();

// Step 3: Fetch fine details for each car
$fines = [];
foreach ($cars as $car) {
    $sqlFine = "SELECT f.amount, f.reason 
                FROM fine f 
                WHERE f.car = ?";
    $stmtFine = $conn->prepare($sqlFine);
    $stmtFine->bind_param("s", $car['register']);
    $stmtFine->execute();
    $resultFine = $stmtFine->get_result();

    while ($fine = $resultFine->fetch_assoc()) {
        $fines[$car['register']][] = $fine; // Associate fines with the respective car
    }
    $stmtFine->close();
}

$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Person and Car Query Results</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <style type="text/tailwindcss">
        @layer utilities {      
            td {
                border: 0.1rem solid #18181B;             
            }
        }
    </style>
</head>
<body class="mx-32 my-2">
    <?php include("../navbar.php"); ?>
    <div class="mt-2 bg-white p-5 rounded shadow">
        <h1 class="text-2xl font-bold text-center">Person Query Results</h1>
        <table class="min-w-full mt-4 text-center">
            <tr class="text-red-700 font-bold text-xl">
                <th class="border border-zinc-950 px-4 py-2">SSN</th>
                <th class="border border-zinc-950 px-4 py-2">Name</th>
                <th class="border border-zinc-950 px-4 py-2">Address</th>
                <th class="border border-zinc-950 px-4 py-2">Phone Number</th>
                <th class="border border-zinc-950 px-4 py-2">Car Register</th>
                <th class="border border-zinc-950 px-4 py-2">Car Color</th>
                <th class="border border-zinc-950 px-4 py-2">Model Year</th>
                <th class="border border-zinc-950 px-4 py-2">Fine Amount</th>
                <th class="border border-zinc-950 px-4 py-2">Fine Reason</th>
            </tr>

            <tr>
                <td><?php echo htmlspecialchars($ssn); ?></td>
                <td><?php echo htmlspecialchars($personData['person_name']); ?></td>
                <td><?php echo htmlspecialchars($personData['person_address']); ?></td>
                <td><?php echo htmlspecialchars($personData['phone_number']); ?></td>
                <td>
                    <?php 
                    if (!empty($cars)) {
                        foreach ($cars as $car) {
                            echo htmlspecialchars($car['register']) . "<br>";
                        }
                    } else {
                        echo "No cars found";
                    }
                    ?>
                </td>
                <td>
                    <?php 
                    if (!empty($cars)) {
                        foreach ($cars as $car) {
                            echo htmlspecialchars($car['color']) . "<br>";
                        }
                    } else {
                        echo "No cars found";
                    }
                    ?>
                </td>
                <td>
                    <?php 
                    if (!empty($cars)) {
                        foreach ($cars as $car) {
                            echo htmlspecialchars($car['model_year']) . "<br>";
                        }
                    } else {
                        echo "No cars found";
                    }
                    ?>
                </td>
                <td>
                    <?php 
                    foreach ($cars as $car) {
                        if (!empty($fines[$car['register']])) {
                            foreach ($fines[$car['register']] as $fine) {
                                echo htmlspecialchars($fine['amount']) . "<br>";
                            }
                        } else {
                            echo "No fines";
                        }
                    }
                    ?>
                </td>
                <td>
                    <?php 
                    foreach ($cars as $car) {
                        if (!empty($fines[$car['register']])) {
                            foreach ($fines[$car['register']] as $fine) {
                                echo htmlspecialchars($fine['reason']) . "<br>";
                            }
                        } else {
                            echo "No fines";
                        }
                    }
                    ?>
                </td>
            </tr>
        </table>
    </div>
    <?php include("../footer.php"); ?>
</body>
</html>
