<?php
session_start();
// if the user is logged in as admin
if (!isset($_SESSION['user_role']) || $_SESSION['user_role'] !== 'admin') {
    header("Location: login.php");
    exit;
}

// user's name from the session
$user_name = $_SESSION['user_name']; 

// Include database connection
include("../../connectdb.php");

// Fetch users from the database
$sql = "SELECT * FROM user"; 
$result = mysqli_query($conn, $sql);
$users = mysqli_fetch_all($result, MYSQLI_ASSOC);
?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>User Dashboard</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        function openEditModal(user) {
            document.getElementById('editUserId').value = user.user_id;
            document.getElementById('editPersonName').value = user.person_name;
            document.getElementById('editUserName').value = user.user_name;
            document.getElementById('editEmail').value = user.email;
            document.getElementById('editAddress').value = user.person_address;
            document.getElementById('editPhone').value = user.phone_number;
            document.getElementById('editRole').value = user.rol;
            document.getElementById('editModal').classList.remove('hidden');
        }

        function closeEditModal() {
            document.getElementById('editModal').classList.add('hidden');
        }

        function confirmDelete() {
            return confirm("Are you sure you want to delete this user? This action cannot be undone.");
        }
    </script>
</head>
<body class="flex flex-col my-4 mx-48">
    <?php 
    include("../admin_nav.php");
    ?>
    <div class="flex mt-2 gap-4">
        <aside class="w-[20%]  bg-zinc-950 text-white h-screen rounded-md">
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
                        <li class="flex gap-2 py-2 px-4 rounded-lg  hover:bg-red-700 ">
                            <img src="../../assests/dashboard_icon.png" alt="dashboard_icon" class="h-8" />
                            <a href="../admin_home.php" class="block py-2 px-4">Dashboard</a>
                        </li>
                        <li class="flex gap-2 py-2 px-4 rounded-lg  hover:bg-slate-50 hover:text-zinc-950">
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
            <h2 class="text-3xl font-semibold">User Management</h2>

            <!-- Success Message -->
            <?php if (isset($_SESSION['message'])): ?>
                <div class="bg-green-500 text-white p-4 mb-4 rounded">
                    <?php echo $_SESSION['message']; ?>
                </div>
                <?php unset($_SESSION['message']); ?>
            <?php endif; ?>

            <!-- Toggle Button -->
            <button onclick="toggleForm()" class="bg-zinc-950 text-white p-2 rounded-xl delay-300 ease-in-out hover:bg-slate-50 hover:text-zinc-950 border-2 border-zinc-950 mb-1 mt-2">Add New User</button>

            <!-- Add New User Form -->
            <form id="addUserForm" action="add_user.php" method="post" class="mt-6 bg-white p-5 rounded shadow">
                <h3 class="text-2xl font-bold">Add New User</h3>
                <input type="text" name="person_name" placeholder="Person Name" required class="border p-2 mb-4 w-full" />
                <input type="text" name="user_name" placeholder="User Name" required class="border p-2 mb-4 w-full" />
                <input type="email" name="email" placeholder="Email" required class="border p-2 mb-4 w-full" />
                <input type="password" name="user_password" placeholder="Password" required class="border p-2 mb-4 w-full" />
                <input type="text" name="person_address" placeholder="Address" class="border p-2 mb-4 w-full" />
                <input type="text" name="phone_number" placeholder="Phone Number" class="border p-2 mb-4 w-full" />
                <select name="rol" class="border p-2 mb-4 w-full" required>
                    <option value="user">User</option>
                    <option value="admin">Admin</option>
                </select>
                <button type="submit" class="bg-zinc-950 text-white p-2 rounded delay-300 ease-in-out hover:bg-slate-50 hover:text-zinc-950 border-2 border-zinc-950">Add User</button>
            </form>

            <div class="mt-6 bg-white p-5 rounded shadow">
                <h3 class="text-2xl font-bold">Existing Users</h3>
                <table class="min-w-full mt-4 text-center">
                    <thead>
                        <tr class="text-red-700 font-bold text-xl">
                            <th class="border px-4 py-2">Full Name</th>
                            <th class="border px-4 py-2">Username</th>
                            <th class="border px-4 py-2">Email</th>
                            <th class="border px-4 py-2">Address</th>
                            <th class="border px-4 py-2">Phone Number</th>
                            <th class="border px-4 py-2">Role</th>
                            <th class="border px-4 py-2">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($users as $user): ?>
                            <tr>
                                <td class="border px-4 py-2"><?php echo htmlspecialchars($user['person_name']); ?></td>
                                <td class="border px-4 py-2"><?php echo htmlspecialchars($user['user_name']); ?></td>
                                <td class="border px-4 py-2"><?php echo htmlspecialchars($user['email']); ?></td>
                                <td class="border px-4 py-2"><?php echo htmlspecialchars($user['person_address']); ?></td>
                                <td class="border px-4 py-2"><?php echo htmlspecialchars($user['phone_number']); ?></td>
                                <td class="border px-4 py-2">
                                    <form action="update_role.php" method="post" class="inline">
                                        <select name="rol" onchange="this.form.submit()" class="border p-1">
                                            <option value="user" <?php echo $user['rol'] === 'user' ? 'selected' : ''; ?>>User</option>
                                            <option value="admin" <?php echo $user['rol'] === 'admin' ? 'selected' : ''; ?>>Admin</option>
                                        </select>
                                        <input type="hidden" name="user_id" value="<?php echo $user['user_id']; ?>">
                                    </form>
                                </td>
                                <td class="border px-4 py-2">
                                    <button class="mr-4 bg-zinc-950 text-white py-1 px-4  rounded delay-300 ease-in-out hover:bg-slate-50 hover:text-zinc-950 border-2 border-zinc-950" onclick='openEditModal(<?php echo json_encode($user); ?>)'>Edit</button>
                                    <form action="delete_user.php" method="post" class="inline" onsubmit="return confirmDelete();">
                                        <input type="hidden" name="user_id" value="<?php echo $user['user_id']; ?>">
                                        <button type="submit" class=" bg-zinc-950 text-white py-1 px-4  rounded delay-300 ease-in-out hover:bg-slate-50 hover:text-zinc-950 border-2 border-zinc-950">Delete</button>
                                    </form>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>

            <!-- Edit User Modal -->
            <div id="editModal" class="fixed inset-0 flex items-center justify-center bg-gray-900 bg-opacity-50 hidden">
                <div class="bg-white p-5 rounded shadow-md w-1/3">
                    <h3 class="text-2xl font-bold mb-4">Edit User</h3>
                    <form action="update_user.php" method="post">
                        <input type="hidden" id="editUserId" name="user_id">
                        <input type="text" id="editPersonName" name="person_name" placeholder="Enter Full Name" required class="border p-2 mb-4 w-full" />
                        <input type="text" id="editUserName" name="user_name" placeholder="Enter Username" required class="border p-2 mb-4 w-full" />
                        <input type="email" id="editEmail" name="email" placeholder="Enter Email" required class="border p-2 mb-4 w-full" />
                        <input type="text" id="editAddress" name="person_address" placeholder="Enter Address" required class="border p-2 mb-4 w-full" />
                        <input type="text" id="editPhone" name="phone_number" placeholder="Enter Phone Number" required class="border p-2 mb-4 w-full" />
                        <select id="editRole" name="rol" required class="border p-2 mb-4 w-full">
                            <option value="user">User</option>
                            <option value="admin">Admin</option>
                        </select>
                        <div class="flex justify-between">
                            <button type="submit" class="mr-4 bg-blue-500 text-white py-1 px-4  rounded delay-300 ease-in-out hover:bg-slate-50 hover:text-zinc-950 border-2 border-zinc-950" name="update_u">Update</button>
                            <button type="button" class="mr-4 bg-zinc-950 text-white py-1 px-4  rounded delay-300 ease-in-out hover:bg-slate-50 hover:text-zinc-950 border-2 border-zinc-950" onclick="closeEditModal()">Cancel</button>
                        </div>
                    </form>
                </div>
            </div>
        </main>
        <script>
            function toggleForm() {
                const form = document.getElementById('addUserForm');
                form.style.display = (form.style.display === 'none' || form.style.display === '') ? 'block' : 'none';
            }
        </script>
    </div>
    <?php 
    include("../admin_footer.php");
    ?>
    
</body>
</html>
