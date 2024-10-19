<?php
session_start();
if (!isset($_SESSION['user_role']) || $_SESSION['user_role'] !== 'admin') {
    header("Location: login.php");
    exit;
}

// Get the user's name from the session
$user_name = $_SESSION['user_name']; // Make sure to set this during login

include("../../connectdb.php");

if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}

// SQL query to fetch existing fines
$sql = "SELECT f.*, p.person_name, c.register AS car_register, c.owner_id FROM fine f 
        JOIN person p ON f.person = p.ssn 
        JOIN car c ON f.car = c.register";
$result = mysqli_query($conn, $sql);
if (!$result) {
    die("Query failed: " . mysqli_error($conn));
}
$fines = mysqli_fetch_all($result, MYSQLI_ASSOC);

// Fetch all persons and cars for the initial load
$persons = mysqli_query($conn, "SELECT ssn, person_name FROM person");
$cars = mysqli_query($conn, "SELECT register, owner_id FROM car");
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Fine Dashboard</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        function openEditModal(fine) {
            document.getElementById('editFineId').value = fine.id;
            document.getElementById('editPersonId').value = fine.person;
            document.getElementById('editCarId').value = fine.car;
            document.getElementById('editDate').value = fine.date;
            document.getElementById('editAmount').value = fine.amount;
            document.getElementById('editReason').value = fine.reason;
            document.getElementById('editModal').classList.remove('hidden');
        }

        function closeEditModal() {
            document.getElementById('editModal').classList.add('hidden');
        }

        function toggleAddFineSection() {
            const form = document.getElementById('addFineForm');
            form.classList.toggle('hidden');
        }

        function searchPersons() {
            const query = document.getElementById('personSearch').value;
            fetch(`search_persons.php?query=${query}`)
                .then(response => response.json())
                .then(data => {
                    const personSelect = document.getElementById('person');
                    personSelect.innerHTML = '<option value="">Select Person</option>';
                    data.forEach(person => {
                        personSelect.innerHTML += `<option value="${person.ssn}">${person.person_name}</option>`;
                    });
                });
        }

        function searchCars() {
            const query = document.getElementById('carSearch').value;
            fetch(`search_cars.php?query=${query}`)
                .then(response => response.json())
                .then(data => {
                    const carSelect = document.getElementById('car');
                    carSelect.innerHTML = '<option value="">Select Car</option>';
                    if (data.length > 0) {
                        data.forEach(car => {
                            carSelect.innerHTML += `<option value="${car.register}" data-owner="${car.owner_id}">${car.register}</option>`;
                        });
                        // Automatically select the first result to populate the fields
                        const firstCar = data[0];
                        document.getElementById('person').value = firstCar.owner_id; // Set Owner (SSN)
                        fetch(`get_owner_name.php?owner_id=${firstCar.owner_id}`)
                            .then(response => response.json())
                            .then(ownerData => {
                                document.getElementById('ownerName').value = ownerData.person_name || 'Owner not found'; // Set Owner Name
                            });
                    } else {
                        document.getElementById('person').value = '';
                        document.getElementById('ownerName').value = '';
                    }
                });
        }


        function updatePersonFromCar() {
            const carSelect = document.getElementById('car');
            const selectedOption = carSelect.options[carSelect.selectedIndex];
            if (selectedOption.value) {
                const ownerId = selectedOption.getAttribute('data-owner');
                document.getElementById('person').value = ownerId;

                // Fetch the owner's name and display it
                fetch(`get_owner_name.php?owner_id=${ownerId}`)
                    .then(response => response.json())
                    .then(data => {
                        document.getElementById('ownerName').value = data.person_name || 'Owner not found';
                    });
            } else {
                document.getElementById('person').value = '';
                document.getElementById('ownerName').value = '';
            }
        }
    </script>
</head>

<body class="flex flex-col my-4 mx-48">
    <?php include("../admin_nav.php"); ?>
    <div class="flex mt-2 gap-4">
        <aside class="w-[20%] bg-zinc-950 text-white h-screen rounded-md">
            <div class="p-5">
                <h1 class="text-2xl font-bold underline underline-offset-8 decoration-red-700 text-center">Admin Dashboard</h1>
                <div class="flex mt-5 items-center justify-around">
                    <img src="../../assests/avatar_icon.png" alt="user_icon" class="h-14" />
                    <h2 class="mt-2 text-lg"><!--Welcome,-->
                        <span class="text-red-700 text-3xl">
                            <p class="text-sm text-center">Welcome</p>
                            <?php echo htmlspecialchars(ucfirst($user_name)); ?>
                        </span><!--!-->
                    </h2>
                </div>


                <nav class="mt-10">
                    <ul class="text-xl font-semibold flex flex-col gap-4 justify-center">
                        <li class="flex gap-2 py-2 px-4 rounded-lg  hover:bg-red-700">
                            <img src="../../assests/dashboard_icon.png" alt="dashboard_icon" class="h-8" />
                            <a href="../admin_home.php" class="block py-2 px-4">Dashboard</a>
                        </li>
                        <li class="flex gap-2 py-2 px-4 rounded-lg  hover:bg-red-700">
                            <img src="../../assests/group_icon.png" alt="group_icon" class="h-8" />
                            <a href="../../admin/user/user_dashboard.php" class="block py-2 px-4">Users</a>
                        </li>
                        <li class="flex gap-2 py-2 px-4 hover:bg-red-700 rounded-lg">
                            <img src="../../assests/user_icon.png" alt="user_icon" class="h-8" />
                            <a href="../../admin/person/person_dashboard.php" class="block py-2 px-4 ">Person</a>
                        </li>
                        <li class="flex gap-2 py-2 px-4 hover:bg-red-700 rounded-lg">
                            <img src="../../assests/car_icon.png" alt="car_icon" class="h-8" />
                            <a href="../../admin/car/car_dashboard.php" class="block py-2 px-4 ">Car</a>
                        </li>
                        <li class="flex gap-2 py-2 px-4 rounded-lg  hover:bg-slate-50 hover:text-zinc-950">
                            <img src="../../assests/fine_icon.png" alt="finepenalty_icon" class="h-8" />
                            <a href="../../admin/fine/fine_dashboard.php" class="block py-2 px-4 ">Fine</a>
                        </li>
                        <li class="flex gap-2 py-2 px-4 hover:bg-red-700 rounded-lg mt-16">
                            <img src="../../assests/logout_icon.png" alt="logout_icon" class="h-8" />
                            <a href="../../logout.php" class="block py-2 px-4 ">Logout</a>
                        </li>
                    </ul>
                </nav>
            </div>
        </aside>

        <main class="flex-1 bg-gray-100 p-6">
            <h2 class="text-3xl font-semibold">Fine Management</h2>
            <button id="toggleAddFine" class="bg-zinc-950 text-white p-2 rounded delay-300 ease-in-out hover:bg-slate-50 hover:text-zinc-950 border-2 border-zinc-950 mb-1 mt-2" onclick="toggleAddFineSection()">Add New Fine</button>

            <form id="addFineForm" action="add_fine.php" method="post" class="mt-6 bg-white p-5 rounded shadow hidden">
                <h3 class="text-2xl font-bold">Add New Fine</h3>
                <input type="text" id="carSearch" placeholder="Search Car" class="border p-2 mb-4 w-full" onkeyup="searchCars()">

                <select id="car" name="car" required class="border p-2 mb-4 w-full" onchange="updatePersonFromCar()">
                    <option value="">Select Car</option>
                    <?php while ($row = mysqli_fetch_assoc($cars)): ?>
                        <option value="<?php echo $row['register']; ?>" data-owner="<?php echo $row['owner_id']; ?>">
                            <?php echo $row['register']; ?>
                        </option>
                    <?php endwhile; ?>
                </select>

                <input type="text" id="person" name="person" placeholder="Owner (SSN)" class="border p-2 mb-4 w-full outline-1 text-red-700 font-bold" readonly>
                <input type="text" id="ownerName" name="owner_name" placeholder="Owner Name" class="border p-2 mb-4 w-full outline-1 text-red-700 font-bold" readonly>

                <input type="date" name="date" required class="border p-2 mb-4 w-full" />
                <input type="number" name="amount" placeholder="Enter Amount(€ Euro)" required class="border p-2 mb-4 w-full" />
                <input type="text" name="reason" placeholder="Enter Reason (For e.g Wrong Parking)" required class="border p-2 mb-4 w-full" />
                <button type="submit" class="bg-zinc-950 text-white p-2 rounded delay-300 ease-in-out hover:bg-slate-50 hover:text-zinc-950 border-2 border-zinc-950 mb-1 mt-2">Add Fine</button>
            </form>

            <div class="mt-6 bg-white p-5 rounded shadow">
                <h3 class="text-2xl font-bold">Existing Fines</h3>
                <?php if (empty($fines)): ?>
                    <p>No fines found.</p>
                <?php else: ?>
                    <table class="min-w-full mt-4 text-center">
                        <thead>
                            <tr>
                                <th class="border px-4 py-2">Person</th>
                                <th class="border px-4 py-2">Car</th>
                                <th class="border px-4 py-2">Date</th>
                                <th class="border px-4 py-2">Amount</th>
                                <th class="border px-4 py-2">Reason</th>
                                <th class="border px-4 py-2">Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($fines as $fine): ?>
                                <tr>
                                    <td class="border px-4 py-2"><?php echo htmlspecialchars($fine['person_name']); ?></td>
                                    <td class="border px-4 py-2"><?php echo htmlspecialchars($fine['car_register']); ?></td>
                                    <td class="border px-4 py-2"><?php echo htmlspecialchars($fine['date']); ?></td>
                                    <td class="border px-4 py-2"><?php echo htmlspecialchars($fine['amount']); ?></td>
                                    <td class="border px-4 py-2"><?php echo htmlspecialchars($fine['reason']); ?></td>
                                    <td class="border px-4 py-2">
                                        <button class="mr-4 bg-zinc-950 text-white py-1 px-4  rounded delay-300 ease-in-out hover:bg-slate-50 hover:text-zinc-950 border-2 border-zinc-950" onclick='openEditModal(<?php echo json_encode($fine); ?>)'>Edit</button>
                                        <form action="delete_fine.php" method="post" class="inline" onsubmit="return confirm('Are you sure you want to delete this fine? This action cannot be undone.');">
                                            <input type="hidden" name="fine_id" value="<?php echo $fine['id']; ?>">
                                            <button type="submit" class="mr-4 bg-zinc-950 text-white py-1 px-4  rounded delay-300 ease-in-out hover:bg-slate-50 hover:text-zinc-950 border-2 border-zinc-950">Delete</button>
                                        </form>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
            </div>
        <?php endif; ?>
        </main>
    </div>
    <!-- Edit Modal -->
    <div id="editModal" class="hidden fixed inset-0 flex items-center justify-center bg-gray-900 bg-opacity-50">
        <div class="bg-white p-6 rounded shadow-md w-96">
            <h3 class="text-2xl font-bold">Edit Fine</h3>
            <form action="update_fine.php" method="post">
                <input type="hidden" id="editFineId" name="fine_id">
                <input type="text" id="editPersonId" name="person" placeholder="Person SSN" class="border p-2 mb-4 w-full">
                <input type="text" id="editCarId" name="car" placeholder="Car Register" class="border p-2 mb-4 w-full">
                <input type="date" id="editDate" name="date" class="border p-2 mb-4 w-full">
                <input type="number" id="editAmount" name="amount" placeholder="Amount (€)" class="border p-2 mb-4 w-full">
                <input type="text" id="editReason" name="reason" placeholder="Reason" class="border p-2 mb-4 w-full">
                <button type="submit" class="bg-zinc-950 text-white p-2 rounded">Update Fine</button>
                <button type="button" class="bg-red-500 text-white p-2 rounded mt-2" onclick="closeEditModal()">Cancel</button>
            </form>
        </div>
    </div>

    <?php
    include("../admin_footer.php");
    ?>
</body>

</html>