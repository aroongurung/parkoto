<?php
session_start();
// Check if the user is logged in as admin
if (!isset($_SESSION['user_role']) || $_SESSION['user_role'] !== 'admin') {
    header("Location: login.php");
    exit;
}
// Get the user's name from the session
$user_name = $_SESSION['user_name']; // Make sure to set this during login

// Include database connection
include("../../connectdb.php");

// Fetch cars from the database
$sql = "SELECT * FROM car"; // Adjust table name as per your DB
$result = mysqli_query($conn, $sql);
$cars = mysqli_fetch_all($result, MYSQLI_ASSOC);
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Car Dashboard</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        function openEditModal(car) {
            document.getElementById('editCarId').value = car.id;
            document.getElementById('editRegister').value = car.register;
            document.getElementById('editColor').value = car.color;
            document.getElementById('editModelYear').value = car.model_year;
            document.getElementById('editOwnerId').value = car.owner_id;
            document.getElementById('editModal').classList.remove('hidden');
        }

        function closeEditModal() {
            document.getElementById('editModal').classList.add('hidden');
        }

        function confirmDelete() {
            return confirm("Are you sure you want to delete this car? This action cannot be undone.");
        }

        function toggleAddCarForm() {
            const addCarForm = document.getElementById('addCarForm');
            addCarForm.classList.toggle('hidden');
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
                        <li class="flex gap-2 py-2 px-4 rounded-lg  hover:bg-slate-50 hover:text-zinc-950">
                            <img src="../../assests/car_icon.png" alt="car_icon" class="h-8" />
                            <a href="../../admin/car/car_dashboard.php" class="block py-2 px-4 ">Car</a>
                        </li>
                        <li class="flex gap-2 py-2 px-4 hover:bg-red-700 rounded-lg">
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
            <h2 class="text-3xl font-semibold">Car Management</h2>
            <button onclick="toggleAddCarForm()" class="bg-zinc-950 text-white p-2 rounded delay-300 ease-in-out hover:bg-slate-50 hover:text-zinc-950 border-2 border-zinc-950 mb-1 mt-2">Add New Car</button>
            
            <form id="addCarForm" action="add_car.php" method="post" class="mt-6 bg-white p-5 rounded shadow hidden">
                <h3 class="text-2xl font-bold">Add New Car</h3>
                <input type="text" name="register" placeholder="Enter Registration Number" required class="border p-2 mb-4 w-full" />
                <input type="text" name="color" placeholder="Enter Color" required class="border p-2 mb-4 w-full" />
                <input type="text" name="model_year" placeholder="Enter Model Year" required class="border p-2 mb-4 w-full" />
                <input type="text" name="owner_id" placeholder="Enter Owner ID" required class="border p-2 mb-4 w-full" />
                <button type="submit" class="bg-zinc-950 text-white p-2 rounded delay-300 ease-in-out hover:bg-slate-50 hover:text-zinc-950 border-2 border-zinc-950 mb-1 mt-2">Add Car</button>
            </form>

            <div class="mt-6 bg-white p-5 rounded shadow">
                <h3 class="text-2xl font-bold">Existing Cars</h3>
                <table class="min-w-full mt-4 text-center">
                    <thead>
                        <tr>
                            <th class="border px-4 py-2">Registration</th>
                            <th class="border px-4 py-2">Color</th>
                            <th class="border px-4 py-2">Model Year</th>
                            <th class="border px-4 py-2">Owner ID</th>
                            <th class="border px-4 py-2">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($cars as $car): ?>
                            <tr>
                                <td class="border px-4 py-2"><?php echo htmlspecialchars($car['register']); ?></td>
                                <td class="border px-4 py-2"><?php echo htmlspecialchars($car['color']); ?></td>
                                <td class="border px-4 py-2"><?php echo htmlspecialchars($car['model_year']); ?></td>
                                <td class="border px-4 py-2"><?php echo htmlspecialchars($car['owner_id']); ?></td>
                                <td class="border px-4 py-2">
                                    <button class="mr-4 bg-zinc-950 text-white py-1 px-4  rounded delay-300 ease-in-out hover:bg-slate-50 hover:text-zinc-950 border-2 border-zinc-950" onclick='openEditModal(<?php echo json_encode($car); ?>)'>Edit</button>
                                    <form action="delete_car.php" method="post" class="inline" onsubmit="return confirmDelete()">
                                        <input type="hidden" name="register" value="<?php echo $car['register']; ?>"> <!-- Adjust as needed -->
                                        <button type="submit" class="mr-4 bg-zinc-950 text-white py-1 px-4  rounded delay-300 ease-in-out hover:bg-slate-50 hover:text-zinc-950 border-2 border-zinc-950">Delete</button>
                                    </form>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>

            <!-- Edit Car Modal -->
            <div id="editModal" class="fixed inset-0 flex items-center justify-center bg-gray-900 bg-opacity-50 hidden">
                <div class="bg-white p-5 rounded shadow-md w-1/3">
                    <h3 class="text-2xl font-bold">Edit Car</h3>
                    <form action="update_car.php" method="post">
                        <input type="hidden" id="editCarId" name="car_id">
                        <input type="text" id="editRegister" name="register" placeholder="Enter Registration Number" required class="border p-2 mb-4 w-full" />
                        <input type="text" id="editColor" name="color" placeholder="Enter Color" required class="border p-2 mb-4 w-full" />
                        <input type="text" id="editModelYear" name="model_year" placeholder="Enter Model Year" required class="border p-2 mb-4 w-full" />
                        <input type="text" id="editOwnerId" name="owner_id" placeholder="Enter Owner ID" required class="border p-2 mb-4 w-full" />
                        <div class="flex justify-between">
                            <button type="submit" class="mr-4 bg-zinc-950 text-white py-1 px-4  rounded delay-300 ease-in-out hover:bg-slate-50 hover:text-zinc-950 border-2 border-zinc-950">Update</button>
                            <button type="button" class="mr-4 bg-zinc-950 text-white py-1 px-4  rounded delay-300 ease-in-out hover:bg-slate-50 hover:text-zinc-950 border-2 border-zinc-950" onclick="closeEditModal()">Cancel</button>
                        </div>
                    </form>
                </div>
            </div>
        </main>
    </div>
    <?php include("../admin_footer.php"); ?>
</body>

</html>
