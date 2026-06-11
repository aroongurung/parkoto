<?php
session_start();
include($_SERVER['DOCUMENT_ROOT'] . '../connectdb.php');

error_reporting(E_ALL);
ini_set('display_errors', 1);

// Get the input from the form and sanitize it
$register = isset($_POST['register']) ? trim($_POST['register']) : '';
$ownerId = isset($_POST['owner_id']) ? trim($_POST['owner_id']) : '';

// Debugging
echo "Register: " . htmlspecialchars($register) . "<br>";
echo "Owner ID (SSN): " . htmlspecialchars($ownerId) . "<br>";

// Check for empty values
if (empty($register) || empty($ownerId)) {
    echo "Both fields are required.";
    exit;
}

// Fetch car details
$sqlCar = "SELECT c.color, c.model_year, c.register, c.owner_id FROM car c WHERE c.register = ?";
$stmtCar = $conn->prepare($sqlCar);
$stmtCar->bind_param("s", $register);
$stmtCar->execute();
$resultCar = $stmtCar->get_result();

if ($resultCar->num_rows === 0) {
    $noResult = true;
} else {
    $noResult = false;
    $carData = $resultCar->fetch_assoc();
    $stmtCar->close();

    // Fetch owner details using the owner's SSN
    $sqlOwner = "SELECT p.person_name, u.person_address, u.phone_number FROM person p JOIN user u ON p.user_id = u.user_id WHERE p.ssn = ?";
    $stmtOwner = $conn->prepare($sqlOwner);
    $stmtOwner->bind_param("s", $ownerId);
    $stmtOwner->execute();
    $resultOwner = $stmtOwner->get_result();

    if ($resultOwner->num_rows === 0) {
        echo "No owner found with the provided SSN.";
        exit;
    }

    $ownerData = $resultOwner->fetch_assoc();
    $stmtOwner->close();

    // Fetch fine details
    $sqlFine = "SELECT f.amount, f.reason FROM fine f WHERE f.car = ?";
    $stmtFine = $conn->prepare($sqlFine);
    $stmtFine->bind_param("s", $register);
    $stmtFine->execute();
    $resultFine = $stmtFine->get_result();

    $fineData = [];
    while ($row = $resultFine->fetch_assoc()) {
        $fineData[] = $row; // collect all fines for car
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
    <title>Car Query Results</title>
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

    <div class="flex justify-center items-start min-h-[70vh] mt-8">
        <div class="w-full max-w-6xl bg-white p-8 rounded shadow">
            
            <!-- Info Debug-->
            <div class="text-center mb-6">
                <p><strong>Register:</strong> <?php echo htmlspecialchars($register); ?></p>
                <p><strong>Owner ID (SSN):</strong> <?php echo htmlspecialchars($ownerId); ?></p>
            </div>

            <?php if (isset($noResult) && $noResult): ?>
                <div class="text-center text-red-600 text-xl font-semibold py-10">
                    No car found with the provided register.
                </div>
            <?php else: ?>
                <h1 class="text-2xl font-bold text-center mb-6">Car Query Results</h1>
                <table class="min-w-full mt-4 text-center">
                    <tr class="text-red-700 font-bold text-xl">
                        <th class="border border-zinc-950 px-4 py-2">Register</th>
                        <th class="border border-zinc-950 px-4 py-2">Color</th>
                        <th class="border border-zinc-950 px-4 py-2">Model Year</th>
                        <th class="border border-zinc-950 px-4 py-2">Owner Name</th>
                        <th class="border border-zinc-950 px-4 py-2">Owner Address</th>
                        <th class="border border-zinc-950 px-4 py-2">Owner Phone</th>
                        <th class="border border-zinc-950 px-4 py-2">Fine Amount</th>
                        <th class="border border-zinc-950 px-4 py-2">Fine Reason</th>
                    </tr>

                    <tr>
                        <td><?php echo htmlspecialchars($carData['register'] ?? 'N/A'); ?></td>
                        <td><?php echo htmlspecialchars($carData['color'] ?? 'N/A'); ?></td>
                        <td><?php echo htmlspecialchars($carData['model_year'] ?? 'N/A'); ?></td>
                        <td><?php echo htmlspecialchars($ownerData['person_name'] ?? 'N/A'); ?></td>
                        <td><?php echo htmlspecialchars($ownerData['person_address'] ?? 'N/A'); ?></td>
                        <td><?php echo htmlspecialchars($ownerData['phone_number'] ?? 'N/A'); ?></td>
                        <td>
                            <?php 
                            if (!empty($fineData)) {
                                foreach ($fineData as $fine) {
                                    echo htmlspecialchars($fine['amount']) . "<br>";
                                }
                            } else {
                                echo "No fines";
                            }
                            ?>
                        </td>
                        <td>
                            <?php 
                            if (!empty($fineData)) {
                                foreach ($fineData as $fine) {
                                    echo htmlspecialchars($fine['reason']) . "<br>";
                                }
                            } else {
                                echo "No fines";
                            }
                            ?>
                        </td>
                    </tr>
                </table>
            <?php endif; ?>
        </div>
    </div>

    <?php include("../footer.php"); ?>
</body>
</html>
