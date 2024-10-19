<?php
include("../connectdb.php");

$cars = [];
$persons = [];
$searchPerformed = false; // Flag to track if search has been performed

// Handle search submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $carSearch = !empty($_POST['car']) ? $conn->real_escape_string(trim($_POST['car'])) : null;
    $personSearch = !empty($_POST['person']) ? $conn->real_escape_string(trim($_POST['person'])) : null;

    // Fetch car data based on search
    if ($carSearch) {
        $carSql = "
            SELECT car.*, person.ssn AS owner_id, person.person_name 
            FROM car 
            LEFT JOIN person ON car.owner_id = person.ssn 
            WHERE car.register LIKE '%$carSearch%' OR car.color LIKE '%$carSearch%'
        ";
        $carResult = $conn->query($carSql);
        if ($carResult && $carResult->num_rows > 0) {
            while ($row = $carResult->fetch_assoc()) {
                $cars[] = $row;
            }
        }
    }

    // Fetch person data based on search
    if ($personSearch) {
        $personSql = "
            SELECT person.*, car.register AS car_registered 
            FROM person 
            LEFT JOIN car ON person.ssn = car.owner_id 
            WHERE person.person_name LIKE '%$personSearch%' OR person.ssn LIKE '%$personSearch%'
        ";
        $personResult = $conn->query($personSql);
        if ($personResult && $personResult->num_rows > 0) {
            while ($row = $personResult->fetch_assoc()) {
                $persons[] = $row;
            }
        }
    }

    // Set the search performed flag to true
    $searchPerformed = true;
}

// Handle penalty fine submission
if (isset($_POST['apply_penalty'])) {
    $selectedCar = $_POST['car'];
    $selectedPerson = $_POST['person'];
    $fineAmount = $_POST['fine_amount'];
    $reason = $_POST['reason'];

    if ($fineAmount && $reason) {
        // Use prepared statements to insert penalty fine
        $stmt = $conn->prepare("INSERT INTO fine (car, person, amount, reason) VALUES (?, ?, ?, ?)");
        $stmt->bind_param("ssis", $selectedCar, $selectedPerson, $fineAmount, $reason);

        if ($stmt->execute()) {
            echo "<p>Penalty fine applied successfully.</p>";
        } else {
            echo "<p>Error applying penalty fine: " . $stmt->error . "</p>";
        }

        $stmt->close();
    } else {
        echo "<p>Please provide a fine amount and reason.</p>";
    }
}

// Close connection
$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Fine Management</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <style type="text/tailwindcss">
         @layer utilities{
            nav{
                color: #ECDFCC;
            }
            footer{
                color: #ECDFCC;
                margin-bottom: -2rem;
            }
            .primary-btn {
                background-color: #c53030;
                width: 8rem;
                height: 2.6rem;
                border-radius: 0.4rem;
                color: #181C14;
                transition: 0.2s ease-in;
                font-size: 1.6rem;
                text-align: center;
            }      
            .primary-btn:hover {
                background-color: #181C14;
                color: #FFFAFA;
            }
                   
          body{
            color: #181C14;
            margin: 0.8rem 8rem;
            
          }
          .card-fine {
                background-color: #F8FAFC;
                border: 0.2rem solid #E2E8F0;
                padding: 0.8rem 1.2rem;
                min-height: 30rem;
                min-width: 24rem;
                border-radius: 0.6rem;
                box-shadow: 0.8rem 0.8rem 1.2rem rgb(26, 20, 20);
                display: flex;           
                align-items: center;
                text-align: center;
                
            
            }

            label {
                font-size: 1.4rem;
                font-weight: 600;
                width: 10rem;
                display: inline-block;
                text-align: right;
                
            }

            input {
                text-align: left;
                padding: 0.2rem 1rem;
                border-radius: 0.4rem;
                margin-bottom: 0.2rem;
                border: 0.1rem solid #181C14;
                background-color: #F8FAFC;
                color: #18181B;
                width: 55%;
            
            }

            ::placeholder {
                text-align: center;
            }

            a:hover {
                color: #181C14;
                padding: 0.2rem 0.6rem;
                transition: 0.3s ease-in;
                border-radius: 0.2rem;
            }

            h1 {
                color: #181C14;
            }
            select{
                width: 55%;
            }
        
             
          
         }
    </style>
    <script>
        // Function to update person dropdown based on selected car
        function updatePersonDropdown(selectedCar) {
            const personDropdown = document.getElementById('person-dropdown');
            personDropdown.innerHTML = ''; // Clear current options

            // Define cars and their owners (You might want to get this dynamically from your PHP)
            const cars = <?php echo json_encode($cars); ?>;
            const selectedCarData = cars.find(car => car.register === selectedCar);

            if (selectedCarData) {
                const ownerId = selectedCarData.owner_id; // Get the owner ID for the selected car

                // Populate the person dropdown with the owner only
                const option = document.createElement('option');
                option.value = ownerId;
                option.textContent = ownerId; // Assuming owner ID is the person's SSN
                personDropdown.appendChild(option);
            }
        }
    </script>
</head>
<body>
    <?php 
    include("../assests/navbar.php");   
    ?>
    <div class="flex justify-center space-x-12 mt-2">
    <!-- Card 1 -->
    <div class="card-fine flex flex-col  border rounded-lg shadow-lg bg-white py-[1rem] px-[2rem] flex-1 w-[60%]">
        <h1 class="text-5xl font-bold mb-[2rem] underline decoration-red-700">Fine</h1>
        
        <form method="POST" action="">
            <label for="car" class="mt-2">Car:</label>
            <input type="text" name="car" placeholder="Car Registration" class="border rounded-md p-2 mt-1 mb-4">
            <br>
            <label for="person" class="mt-2">Person:</label>
            <input type="text" name="person" placeholder="Person Name or SSN" class="border rounded-md p-2 mt-1 mb-4">
            <br>
            <button class="primary-btn mt-4" type="submit">Search</button>
            <!--<button class="primary-btn mt-4 mb-2 bg-red-700" onclick="location.href='fine.php'">Add</button>-->
        </form>
        
        <?php if ($searchPerformed): ?>
            <?php if (!empty($cars)): ?>
                <h2 class="mt-4 text-2xl font-bold text-zinc-950">Search Results for Cars</h2>
                <ul>
                    <?php foreach ($cars as $car): ?>
                        <li class="text-red-700">
                            <?php echo htmlspecialchars($car['register']) . " - " . htmlspecialchars($car['color']); ?>
                            <p>Owner ID: <?php echo htmlspecialchars($car['owner_id']); ?> (<?php echo htmlspecialchars($car['person_name']); ?>)</p>
                        </li>
                    <?php endforeach; ?>
                </ul>
            <?php else: ?>
                <p class="text-red-700">No cars found for the given search criteria.</p>
            <?php endif; ?>

            <?php if (!empty($persons)): ?>
                <h2 class="mt-4 text-2xl font-bold text-zinc-950">Search Results for Persons</h2>
                <ul>
                    <?php foreach ($persons as $person): ?>
                        <li class="text-red-700">
                            <?php echo htmlspecialchars($person['person_name']) . " - " . htmlspecialchars($person['ssn']); ?>
                            <p>Address: <?php echo htmlspecialchars($person['person_address']); ?></p>
                            <p>Phone: <?php echo htmlspecialchars($person['phone_number']); ?></p>
                            <p>Car Registered: <?php echo htmlspecialchars($person['car_registered'] ?? 'None'); ?></p>
                        </li>
                    <?php endforeach; ?>
                </ul>
            <?php else: ?>
                <p class="text-red-700">No persons found for the given search criteria.</p>
            <?php endif; ?>
        <?php endif; ?>
    </div>

    <!-- Card 2 -->
    <div class="card-fine flex flex-col items-center border rounded-lg shadow-lg bg-white flex-1 w-[40%] p-2">
    <h2 class="my-4 text-3xl font-bold underline decoration-red-700 underline-offset-8">Apply Penalty Fine</h2>
    <form method="POST">
        <label for="selected_car" class="mt-2">Select Car</label>
        <select name="car" id="car-dropdown" onchange="updatePersonDropdown(this.value)" required class="border rounded-md p-2 mt-1 mb-4 ">
            <option value="">--Select Car--</option>
            <?php foreach ($cars as $car): ?>
                <option value="<?php echo htmlspecialchars($car['register']); ?>"><?php echo htmlspecialchars($car['register']); ?></option>
            <?php endforeach; ?>
        </select>
        <br>

        <label for="selected_person" class="mt-2">Select Person</label>
        <select name="person" id="person-dropdown" required class="border rounded-md p-2 mt-1 mb-4">
            <option value="">--Select Person--</option>
            <!-- Options will be populated based on car selection -->
        </select>
        <br>
        <label for="fine_amount" class="mt-2">Amount(€):</label>
        <input type="number" name="fine_amount" placeholder="Enter Fine Amount" required class="border rounded-md p-2 mt-1 mb-4">
        <br>
        <label for="reason" class="mt-2">Reason:</label>
        <input type="text" name="reason" placeholder="Reason for Fine" required class="border rounded-md p-2 mt-1 mb-4">
        <br>
        <button type="submit" name="apply_penalty" class="primary-btn mt-4">Apply</button>
    </form>
</div>

</div>

    <?php include("../assests/footer.php"); ?>
</body>
</html>
