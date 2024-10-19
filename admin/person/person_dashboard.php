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

// Check for a success message in the session
$successMessage = "";
if (isset($_SESSION['success_message'])) {
    $successMessage = $_SESSION['success_message'];
    unset($_SESSION['success_message']); // Clear the message after displaying it
}


// Fetch users from the database for suggestions
$sql = "SELECT user_id, person_name FROM user";
$result = mysqli_query($conn, $sql);
$users = mysqli_fetch_all($result, MYSQLI_ASSOC);

// Fetch existing persons from the database
$sql_person = "SELECT p.user_id, u.person_name, p.ssn, p.ssn FROM person p JOIN user u ON p.user_id = u.user_id"; // Replace 'person_id' with your actual primary key column name
$result_person = mysqli_query($conn, $sql_person);
$persons = $result_person ? mysqli_fetch_all($result_person, MYSQLI_ASSOC) : [];
?>


<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Person Dashboard</title>
    <script src="https://cdn.tailwindcss.com"></script>
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
                        <li class="flex gap-2 py-2 px-4 rounded-lg  hover:bg-slate-50 hover:text-zinc-950">
                            <img src="../../assests/user_icon.png" alt="user_icon" class="h-8" />
                            <a href="../../admin/person/person_dashboard.php" class="block py-2 px-4 ">Person</a>
                        </li>
                        <li class="flex gap-2 py-2 px-4 hover:bg-red-700 rounded-lg">
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
            <h2 class="text-3xl font-semibold">Person Management</h2>
            <form action="add_person.php" method="post" class="mt-6 bg-white p-5 rounded shadow">
                <h3 class="text-2xl font-bold">Add New Person</h3>
                <input list="userNames" id="searchPerson" placeholder="Select Full Name" class="border p-2 mb-4 mt-4 w-full" />
                <datalist id="userNames">
                    <?php foreach ($users as $user): ?>
                        <option value="<?php echo htmlspecialchars($user['person_name']); ?>" data-user-id="<?php echo $user['user_id']; ?>"></option>
                    <?php endforeach; ?>
                </datalist>
                <input type="hidden" id="selectedUserId" name="user_id" />
                <input type="text" name="ssn" placeholder="Enter SSN" required class="border p-2 mb-4 w-full" />
                <button type="submit" class="bg-zinc-950 text-white p-2 rounded delay-300 ease-in-out hover:bg-slate-50 hover:text-zinc-950 border-2 border-zinc-950">Add Person</button>
            </form>

            <?php if ($successMessage): ?>
                <div style="color: green;"><?php echo htmlspecialchars($successMessage); ?></div>
            <?php endif; ?>

            <div class="mt-6 bg-white p-5 rounded shadow">
                <h3 class="text-2xl font-bold">Existing Persons</h3>
                <table class="min-w-full mt-4 text-center">
                    <thead>
                        <tr class="text-red-700 font-bold text-xl">
                            <th class="border px-4 py-2">User Name</th>
                            <th class="border px-4 py-2">SSN</th>
                            <th class="border px-4 py-2">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($persons as $p): ?>
                            <tr class="text-center text-xl">
                                <td class="border px-4 py-2"><?php echo htmlspecialchars($p['person_name']); ?></td>
                                <td class="border px-4 py-2"><?php echo htmlspecialchars($p['ssn']); ?></td>
                                <td class="border px-4 py-2">
                                    <button class="mr-4 bg-zinc-950 text-white py-1 px-4  rounded delay-300 ease-in-out hover:bg-slate-50 hover:text-zinc-950 border-2 border-zinc-950" onclick='openEditModal(<?php echo json_encode($p); ?>)'>Edit</button>
                                    <form action="delete_person.php" method="post" class="inline" onsubmit="return confirmDelete()">
                                        <input type="hidden" name="person_id" value="<?php echo $p['ssn']; ?>"> <!-- Use correct identifier here -->
                                        <button type="submit" class="bg-zinc-950 text-white py-1 px-4 rounded delay-300 ease-in-out hover:bg-slate-50 hover:text-zinc-950 border-2 border-zinc-950">Delete</button>
                                    </form>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </main>
    </div>

    <!-- Edit Modal -->
    <div id="editModal" class="modal flex hidden fixed inset-0 items-center justify-center bg-black bg-opacity-50">
        <div class="modal-content bg-white p-5 rounded shadow relative">
            <span class="close cursor-pointer text-4xl absolute right-0 top-0 mr-4" onclick="closeModal()">&times;</span>
            <h2 class="text-xl text-slate-100 bg-zinc-950 pl-4 py-2 my-8">Edit Person</h2>
            <form action="edit_person.php" method="post">
                <input type="hidden" name="person_id" id="edit_person_id" />
                <input class="py-1 pl-4 text-xl border-2 border-zinc-100" type="text" name="person_name" id="edit_person_name" placeholder="Enter Full Name" required />
                <input class="py-1 pl-4 text-xl border-2 border-zinc-100" type="text" name="ssn" id="edit_ssn" placeholder="Enter SSN" required />
                <input type="hidden" name="user_id" id="edit_user_id" />
                <button type="submit" class="bg-red-700 text-white p-2 rounded">Update Person</button>
            </form>
        </div>
    </div>

    <script>
        function openEditModal(person) {
            document.getElementById('edit_person_id').value = person.ssn; // Set person_id as ssn
            document.getElementById('edit_person_name').value = person.person_name;
            document.getElementById('edit_ssn').value = person.ssn; // Optionally, you may want to edit this if needed
            document.getElementById('edit_user_id').value = person.user_id;
            document.getElementById('editModal').classList.remove('hidden');
        }


        function closeModal() {
            document.getElementById('editModal').classList.add('hidden');
        }

        function confirmDelete() {
            return confirm("Are you sure you want to delete this person? This action cannot be undone.");
        }

        document.getElementById('searchPerson').addEventListener('input', function() {
            const selectedUserId = Array.from(document.querySelectorAll('#userNames option')).find(option => option.value === this.value);
            document.getElementById('selectedUserId').value = selectedUserId ? selectedUserId.getAttribute('data-user-id') : '';
        });
    </script>

    <?php include("../admin_footer.php"); ?>
</body>

</html>