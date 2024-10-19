<?php
session_start();

// Include the database connection file
include($_SERVER['DOCUMENT_ROOT'] . '/parkoto/connectdb.php');

// Example usage of a function from connectdb.php
// $result = someDatabaseFunction();

//echo "Database connection established successfully.";

// Get the input from the form
$personName = isset($_POST['person_name']) ? $_POST['person_name'] : '';
$ssn = isset($_POST['ssn']) ? $_POST['ssn'] : '';

// Prepare the SQL query to fetch person details along with user and fine information
$sql = "
SELECT p.*, u.person_address, u.phone_number, c.register, c.color, c.model_year, 
       f.amount AS amount, f.reason AS reason
FROM person p
JOIN user u ON p.user_id = u.user_id
LEFT JOIN car c ON p.ssn = c.owner_id
LEFT JOIN fine f ON p.ssn = f.person  -- Adjust based on your actual fine column reference
WHERE p.person_name = ? OR p.ssn = ?
";

$stmt = $conn->prepare($sql);
$stmt->bind_param("ss", $personName, $ssn);
$stmt->execute();
$result = $stmt->get_result();

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Person Query Results</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <style type="text/tailwindcss">
        @layer utilities {      
            td{
                border: 0.1rem solid #18181B;             
            }
        }
    </style>

</head>
<body class="mx-32 my-2">
<?php 
    include("../navbar.php");
?>
    <div class="mt-6 bg-white p-5 rounded shadow">
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

            <?php
            if ($result->num_rows > 0) {
                while ($row = $result->fetch_assoc()) {
                    echo "<tr>
                        <td>" . htmlspecialchars($row['ssn']) . "</td>
                        <td>" . htmlspecialchars($row['person_name']) . "</td>
                        <td>" . htmlspecialchars($row['person_address'] ?? 'N/A') . "</td>
                        <td>" . htmlspecialchars($row['phone_number'] ?? 'N/A') . "</td>
                        <td>" . htmlspecialchars($row['register'] ?? 'N/A') . "</td>
                        <td>" . htmlspecialchars($row['color'] ?? 'N/A') . "</td>
                        <td>" . htmlspecialchars($row['model_year'] ?? 'N/A') . "</td>
                        <td>" . htmlspecialchars($row['fine_amount'] ?? 'N/A') . "</td>
                        <td>" . htmlspecialchars($row['fine_status'] ?? 'N/A') . "</td>
                    </tr>";
                }
            } else {
                echo "<tr><td colspan='9'>No results found</td></tr>";
            }
            $stmt->close();
            $conn->close();
            ?>
        </table>
    </div>
        <?php 
        include("../footer.php");
        ?>
</body>
</html>
