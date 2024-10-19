<?php
session_start();
// if the user is logged in as admin
if (!isset($_SESSION['user_role']) || $_SESSION['user_role'] !== 'admin') {
    header("Location: login.php");
    exit;
}

// user's name from the session
$user_name = $_SESSION['user_name'];

// Database connection
include("../connectdb.php");

// Fetch total users and admins
$totalUsers = mysqli_fetch_assoc(mysqli_query($conn, "SELECT COUNT(*) as count FROM user"))['count'];
$totalAdmins = mysqli_fetch_assoc(mysqli_query($conn, "SELECT COUNT(*) as count FROM user WHERE rol = 'admin'"))['count'];
$totalcars = mysqli_fetch_assoc(mysqli_query($conn, "SELECT COUNT(*) as count FROM car"))['count'];
$totalpersons = mysqli_fetch_assoc(mysqli_query($conn, "SELECT COUNT(*) as count FROM person"))['count'];
$totalfines = mysqli_fetch_assoc(mysqli_query($conn, "SELECT COUNT(*) as count FROM fine"))['count'];
$totalTodos = mysqli_fetch_assoc(mysqli_query($conn, "SELECT COUNT(*) as count FROM todo"))['count'];
$totalAppointments = mysqli_fetch_assoc(mysqli_query($conn, "SELECT COUNT(*) as count FROM appointment"))['count'];

// Prepare data for the graph
$graphData = [
    'Users' => $totalUsers,
    'Persons' => $totalpersons,
    'Cars' => $totalcars,
    'Fines' => $totalfines,
    'To-Do List' => $totalTodos,
    'Appointments' => $totalAppointments
];
// For the to-do list
$todoList = [];
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['todo'])) {
        $todo = mysqli_real_escape_string($conn, $_POST['todo']);
        mysqli_query($conn, "INSERT INTO todo (task) VALUES ('$todo')");
    } elseif (isset($_POST['delete_todo'])) {
        $id = (int)$_POST['delete_todo'];
        mysqli_query($conn, "DELETE FROM todo WHERE id = $id");
    } elseif (isset($_POST['edit_todo'])) {
        $id = (int)$_POST['id'];
        $task = mysqli_real_escape_string($conn, $_POST['task']);
        mysqli_query($conn, "UPDATE todo SET task = '$task' WHERE id = $id");
    }
}

// Fetch the updated todo list
$result = mysqli_query($conn, "SELECT * FROM todo");
while ($row = mysqli_fetch_assoc($result)) {
    $todoList[] = $row;
}

// For appointments
$appointmentList = [];
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['save_appointment'])) {
        // Saving appointment
        $title = mysqli_real_escape_string($conn, $_POST['title']);
        $date = mysqli_real_escape_string($conn, $_POST['date']);
        $description = mysqli_real_escape_string($conn, $_POST['description']);
        mysqli_query($conn, "INSERT INTO appointment (title, date, description) VALUES ('$title', '$date', '$description')");
    } elseif (isset($_POST['delete_appointment'])) {
        // Deleting appointment
        $id = (int)$_POST['delete_appointment'];
        mysqli_query($conn, "DELETE FROM appointment WHERE id = $id");
    } elseif (isset($_POST['edit_appointment'])) {
        // Editing appointment
        $id = (int)$_POST['id'];
        $title = mysqli_real_escape_string($conn, $_POST['title']);
        $date = mysqli_real_escape_string($conn, $_POST['date']);
        $description = mysqli_real_escape_string($conn, $_POST['description']);
        mysqli_query($conn, "UPDATE appointment SET title = '$title', date = '$date', description = '$description' WHERE id = $id");
    }
}

// Fetch the updated appointment list
$result = mysqli_query($conn, "SELECT * FROM appointment");
while ($row = mysqli_fetch_assoc($result)) {
    $appointmentList[] = $row;
}

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Home</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/js/all.min.js"></script>

    <style>
        .modal {
            display: none;
            position: fixed;
            z-index: 1000;
            left: 0;
            top: 0;
            width: 100%;
            height: 100%;
            overflow: auto;
            background-color: rgba(0, 0, 0, 0.5);
        }

        .modal-content {
            background-color: #fefefe;
            margin: 15% auto;
            padding: 20px;
            border: 1px solid #888;
            width: 40%;
        }

        .close {
            color: #aaa;
            float: right;
            font-size: 28px;
            font-weight: bold;
        }

        .close:hover,
        .close:focus {
            color: black;
            text-decoration: none;
            cursor: pointer;
        }
    </style>
    <script>
        function openModal(modalId) {
            document.getElementById(modalId).style.display = "block";
        }

        function closeModal(modalId) {
            document.getElementById(modalId).style.display = "none";
        }

        function openEditModal(task, id) {
            document.getElementById('editTask').value = task;
            document.getElementById('editTaskId').value = id;
            document.getElementById('editModal').style.display = "block";
        }

        function openEditAppointmentModal(appointment) {
            document.getElementById('editAppointmentTitle').value = appointment.title;
            document.getElementById('editAppointmentDate').value = appointment.date.split("T")[0] + "T" + appointment.date.split("T")[1];
            document.getElementById('editAppointmentDescription').value = appointment.description;
            document.getElementById('editAppointmentId').value = appointment.id;
            document.getElementById('editAppointmentModal').style.display = "block";
        }
    </script>
</head>

<body class="flex flex-col my-4 mx-48">
    <?php include("admin_nav.php"); ?>
    <div class="flex mt-2 gap-4">

        <aside class="w-[20%] bg-zinc-950 text-white h-screen rounded-md">
            <div class="p-5">
                <h1 class="text-2xl font-bold underline underline-offset-8 decoration-red-700">Admin Dashboard</h1>
                <div class="flex mt-5 gap-6 items-center">
                    <img src="../assests/avatar_icon.png" alt="user_icon" class="h-14" />
                    <h2 class="mt-2 text-lg"><!--Welcome,-->
                        <span class="text-red-700 text-2xl">
                            <p class="text-sm text-center">Welcome</p>
                            <?php echo htmlspecialchars(ucfirst($user_name)); ?>
                        </span><!--!-->
                    </h2>
                </div>
                <nav class="mt-10">
                    <ul class="text-xl font-semibold flex flex-col gap-4 justify-center">
                        <li class="flex gap-2 py-2 px-4 hover:bg-red-700 rounded-lg">
                            <img src="../assests/group_icon.png" alt="group_icon" class="h-8" />
                            <a href="../admin/user/user_dashboard.php" class="block py-2 px-4">Users</a>
                        </li>
                        <li class="flex gap-2 py-2 px-4 hover:bg-red-700 rounded-lg">
                            <img src="../assests/user_icon.png" alt="user_icon" class="h-8" />
                            <a href="../admin/person/person_dashboard.php" class="block py-2 px-4 ">Person</a>
                        </li>
                        <li class="flex gap-2 py-2 px-4 hover:bg-red-700 rounded-lg">
                            <img src="../assests/car_icon.png" alt="car_icon" class="h-8" />
                            <a href="../admin/car/car_dashboard.php" class="block py-2 px-4 ">Car</a>
                        </li>
                        <li class="flex gap-2 py-2 px-4 hover:bg-red-700 rounded-lg">
                            <img src="../assests/fine_icon.png" alt="finepenalty_icon" class="h-8" />
                            <a href="../admin/fine/fine_dashboard.php" class="block py-2 px-4 ">Fine</a>
                        </li>
                        <li class="flex gap-2 py-2 px-4 hover:bg-red-700 rounded-lg mt-16">
                            <img src="../assests/logout_icon.png" alt="logout_icon" class="h-8" />
                            <a href="../logout.php" class="block py-2 px-4 ">Logout</a>
                        </li>
                    </ul>
                </nav>
            </div>
        </aside>

        <main class="flex-1 bg-gray-100 p-6">
            <h2 class="text-3xl font-semibold">Welcome, Admin!</h2>
            <p class="mt-4">Here you can manage all aspects of the application.</p>

            <!-- Dashboard Overview -->
            <div class="mt-6 grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4">
                <!-- Graph -->
                <div class="bg-white p-5 rounded shadow">
                    <h3 class="text-xl font-bold">Data Overview</h3>
                    <canvas id="myChart"></canvas>
                </div>
                <!-- Calendar Box -->
                <div class="bg-white p-5 rounded shadow cursor-pointer" onclick="openModal('calendarModal')">
                    <h3 class="text-2xl font-bold text-red-700">Calendar</h3>
                    <p class="mt-6">Your events and schedules here.</p>
                    <img src="../assests/google_calender_icon.png" alt="google_calender_icon" class="h-12 mt-2" />
                </div>

                <!-- Email Box -->
                <div class="bg-white p-5 rounded shadow cursor-pointer" onclick="openModal('emailModal')">
                    <h3 class="text-2xl font-bold text-red-700">Email</h3>
                    <p class="mt-6">Check your emails.</p>
                    <div class="flex items-center gap-4 mt-2">
                        <a href="https://mail.google.com" target="_blank">
                            <img src="../assests/gmail_logo.png" alt="Gmail" class="h-6 mr-2" />
                        </a>
                        <a href="https://outlook.live.com" target="_blank">
                            <img src="../assests/outlook_logo.png" alt="Outlook" class="h-6" />
                        </a>
                    </div>
                </div>

                <!-- To-Do List Box -->
                <div class="bg-white p-5 rounded shadow cursor-pointer" onclick="openModal('todoModal')">
                    <h3 class="text-2xl font-bold text-red-700">To-Do List (<span class="text-zinc-950"><?php echo count($todoList); ?></span>)</h3>
                    <p class="mt-6">Manage your tasks.</p>
                    <img src="../assests/to-do-list_icon.png" alt="todolist" class="h-12 mt-1" />
                </div>

                <!-- Appointment Box -->
                <div class="bg-white p-5 rounded shadow cursor-pointer" onclick="openModal('appointmentModal')">
                    <h3 class="text-2xl font-bold text-red-700">Appointments (<span class="text-zinc-950"><?php echo count($appointmentList); ?></span>)</h3>
                    <p class="mt-6">Manage your appointments.</p>
                </div>

                <!-- Total Users Box -->
                <div class="bg-white p-5 rounded shadow">
                    <h3 class="text-2xl font-bold text-red-700">Total Users</h3>
                    <p class="mt-4 font-bold text-2xl"><?php echo $totalUsers; ?></p>
                </div>

                <!-- Total Admins Box -->
                <div class="bg-white p-5 rounded shadow">
                    <h3 class="text-2xl font-bold text-red-700">Total Admins</h3>
                    <p class="mt-4 font-bold text-2xl"><?php echo $totalAdmins; ?></p>
                </div>

                <!-- Total Cars Box -->
                <div class="bg-white p-5 rounded shadow">
                    <h3 class="text-2xl font-bold text-red-700">Total Cars Registered</h3>
                    <p class="mt-4 font-bold text-2xl"><?php echo $totalcars; ?></p>
                </div>
                <!-- Total Persons Box -->
                <div class="bg-white p-5 rounded shadow">
                    <h3 class="text-2xl font-bold text-red-700">Total Persons</h3>
                    <p class="mt-4 font-bold text-2xl"><?php echo $totalpersons; ?></p>
                </div>
                <!-- Total Fines Box -->
                <div class="bg-white p-5 rounded shadow">
                    <h3 class="text-2xl font-bold text-red-700">Total Fines Issued</h3>
                    <p class="mt-4 font-bold text-2xl"><?php echo $totalfines; ?></p>
                </div>
                
            </div>

            <!-- Calendar Modal -->
            <div id="calendarModal" class="modal">
                <div class="modal-content">
                    <span class="close" onclick="closeModal('calendarModal')">&times;</span>
                    <h2>Google Calendar</h2>
                    <iframe src="https://calendar.google.com/calendar/embed?src=YOUR_CALENDAR_ID&ctz=America/New_York"
                        style="border: 0" width="100%" height="600" frameborder="0" scrolling="no"></iframe>
                </div>
            </div>

            <!-- Email Modal -->
            <div id="emailModal" class="modal">
                <div class="modal-content">
                    <span class="close" onclick="closeModal('emailModal')">&times;</span>
                    <h2>Email</h2>
                    <p>
                        <a href="https://mail.google.com" target="_blank" alt="gmail.logo" class="text-blue-500">Gmail</a> |
                        <a href="https://outlook.live.com" target="_blank" alt="outlook.logo" class="text-blue-500">Outlook</a>
                    </p>
                </div>
            </div>

            <!-- To-Do List Modal -->
            <div id="todoModal" class="modal">
                <div class="modal-content">
                    <span class="close" onclick="closeModal('todoModal')">&times;</span>
                    <h2 class="mb-4 font-bold text-2xl">To-Do List</h2>
                    <form method="POST">
                        <input type="text" name="todo" placeholder="Add new task..." class="border rounded p-2 w-[90%]" required>
                        <button type="submit" class="bg-zinc-950 text-white rounded delay-300 ease-in-out hover:bg-slate-50 hover:text-zinc-950 border-2 border-zinc-950 px-4 py-2">Add</button>
                    </form>
                    <ul>
                        <?php foreach ($todoList as $task): ?>
                            <li class="flex justify-between items-center border py-2 mt-2">
                                <span class="text-red-700 font-semibold text-xl px-2"><?php echo htmlspecialchars($task['task']); ?></span>
                                <div class="px-2">
                                    <button onclick="openEditModal('<?php echo htmlspecialchars($task['task']); ?>', <?php echo $task['id']; ?>)" class="mr-4 bg-zinc-950 text-white py-1 px-4  rounded delay-300 ease-in-out hover:bg-slate-50 hover:text-zinc-950 border-2 border-zinc-950">Edit</button>
                                    <form method="POST" class="inline" onsubmit="return confirm('Are you sure you want to delete this task?');">
                                        <input type="hidden" name="delete_todo" value="<?php echo $task['id']; ?>">
                                        <button type="submit" class=" bg-zinc-950 text-white py-1 px-4  rounded delay-300 ease-in-out hover:bg-slate-50 hover:text-zinc-950 border-2 border-zinc-950">Delete</button>
                                    </form>
                                </div>
                            </li>
                        <?php endforeach; ?>
                    </ul>
                </div>
            </div>

            <!-- Edit Task Modal -->
            <div id="editModal" class="modal">
                <div class="modal-content">
                    <span class="close" onclick="closeModal('editModal')">&times;</span>
                    <h2>Edit Task</h2>
                    <form method="POST">
                        <input type="text" id="editTask" name="task" class="border rounded p-2" required>
                        <input type="hidden" id="editTaskId" name="id">
                        <button type="submit" name="edit_todo" class=" bg-zinc-950 text-white rounded px-4 py-2 delay-300 ease-in-out hover:bg-slate-50 hover:text-zinc-950 border-2 border-zinc-950">Update Task</button>
                    </form>
                </div>
            </div>
            <!-- Appointment Modal -->
            <div id="appointmentModal" class="modal">
                <div class="modal-content">
                    <span class="close" onclick="closeModal('appointmentModal')">&times;</span>
                    <h2>Appointment</h2>
                    <form method="POST">
                        <input type="text" name="title" placeholder="Appointment Title" class="border rounded p-2" required>
                        <input type="datetime-local" name="date" class="border rounded p-2" required>
                        <textarea name="description" placeholder="Description" class="border rounded p-2" rows="2"></textarea>
                        <button type="submit" name="save_appointment" class="bg-zinc-950 text-white rounded px-4 py-2">Save Appointment</button>
                    </form>
                    <ul class="mt-4">
                        <?php foreach ($appointmentList as $appointment): ?>
                            <li class="flex justify-between items-center border py-2 mt-2">
                                <span class="text-red-700 font-semibold"><?php echo htmlspecialchars($appointment['title']); ?></span>
                                <div class="px-2">
                                    <button type="button" onclick='openEditAppointmentModal(<?php echo json_encode($appointment); ?>)' class="bg-zinc-950 text-white py-1 px-4 rounded">Edit</button>
                                    <form method="POST" class="inline" onsubmit="return confirm('Are you sure you want to delete this appointment?');">
                                        <input type="hidden" name="delete_appointment" value="<?php echo $appointment['id']; ?>">
                                        <button type="submit" class="bg-zinc-950 text-white py-1 px-4 rounded">Delete</button>
                                    </form>
                                </div>
                            </li>
                        <?php endforeach; ?>
                    </ul>
                </div>
            </div>

            <!-- Edit Appointment Modal -->
            <div id="editAppointmentModal" class="modal">
                <div class="modal-content">
                    <span class="close" onclick="closeModal('editAppointmentModal')">&times;</span>
                    <h2>Edit Appointment</h2>
                    <form method="POST">
                        <input type="text" id="editAppointmentTitle" name="title" class="border rounded p-2" required>
                        <input type="datetime-local" id="editAppointmentDate" name="date" class="border rounded p-2" required>
                        <textarea id="editAppointmentDescription" name="description" class="border rounded p-2" rows="2"></textarea>
                        <input type="hidden" id="editAppointmentId" name="id">
                        <button type="submit" name="edit_appointment" class="bg-zinc-950 text-white rounded px-4 py-2">Update Appointment</button>
                    </form>
                </div>
            </div>



        </main>
    </div>
    <?php include("admin_footer.php"); ?>
    <script>
        // Prepare data for Chart.js
        const graphData = <?php echo json_encode($graphData); ?>;
        const labels = Object.keys(graphData);
        const data = Object.values(graphData);

        const ctx = document.getElementById('myChart').getContext('2d');
        const myChart = new Chart(ctx, {
            type: 'bar',
            data: {
                labels: labels,
                datasets: [{
                    label: 'Count',
                    data: data,
                    backgroundColor: [
                        'rgba(255, 99, 132, 0.2)',
                        'rgba(54, 162, 235, 0.2)',
                        'rgba(255, 206, 86, 0.2)',
                        'rgba(75, 192, 192, 0.2)',
                        'rgba(153, 102, 255, 0.2)',
                        'rgba(255, 159, 64, 0.2)'
                    ],
                    borderColor: [
                        'rgba(255, 99, 132, 1)',
                        'rgba(54, 162, 235, 1)',
                        'rgba(255, 206, 86, 1)',
                        'rgba(75, 192, 192, 1)',
                        'rgba(153, 102, 255, 1)',
                        'rgba(255, 159, 64, 1)'
                    ],
                    borderWidth: 1
                }]
            },
            options: {
                scales: {
                    y: {
                        beginAtZero: true
                    }
                }
            }
        });
    </script>
</body>

</html>