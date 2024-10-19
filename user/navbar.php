<?php
if (session_status() == PHP_SESSION_NONE) {
    session_start(); // Start the session only if it's not already started
}

// Include the database connection file using an absolute path
include($_SERVER['DOCUMENT_ROOT'] . '/parkoto/connectdb.php');

// Check if the user is logged in
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}

// Assuming you have stored the user's name in the session
$user_name = isset($_SESSION['user_name']) ? $_SESSION['user_name'] : '';

// Initialize variables for search results
$searchResults = [];
$showResults = false; // Initialize a variable to control the display of results

if (isset($_POST['search_box']) && !empty($_POST['search_box'])) {
    if (isset($conn)) {
        $query = mysqli_real_escape_string($conn, $_POST['search_box']);
        $sql = "SELECT * FROM (
                    SELECT 'Car' AS type, register AS name FROM car WHERE register LIKE '%$query%'
                    UNION
                    SELECT 'Person' AS type, person_name AS name FROM person WHERE person_name LIKE '%$query%'
                    UNION
                    SELECT 'Fine' AS type, reason AS name FROM fine WHERE reason LIKE '%$query%'
                ) AS results";
        
        $result = mysqli_query($conn, $sql);
        if ($result) {
            $searchResults = mysqli_fetch_all($result, MYSQLI_ASSOC);
            $showResults = true; // Set to true if results exist
        } else {
            echo "Error: " . mysqli_error($conn);
        }
    } else {
        echo "Database connection not established.";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Welcome to ParKoto</title>
    <style>
        .marquee {
            overflow: hidden;
            white-space: nowrap;
            box-sizing: border-box;
            animation: scroll 20s linear infinite;
        }

        @keyframes scroll {
            0% { transform: translateX(90%); }
            100% { transform: translateX(-0%); }
        }
    </style>
</head>
<body>
    
    <!-- Nav Bar -->
    <nav class="flex justify-between items-center bg-zinc-950 py-[0.8rem] px-[2rem] rounded-md text-white">
        <a href="../hero.php" class="logo"><h1 class="text-4xl font-bold tracking-[0.1rem] text-white">Par<span class="text-red-700">Koto</span></h1></a>
        <div class="flex gap-16 text-2xl tracking-[0.1rem]">
            <!--<a href="../hero.php" class=" text-red-700 hover:text-white text-3xl">Home</a> -->
        </div>
        <form method="POST" class="flex gap-6 items-center mx-auto">
            <input type="text" name="search_box" placeholder="Search..." class="pl-4 text-zinc-950 border rounded sm">
            <button type="submit" class="bg-red-700 text-white px-4 py-1 rounded">Search</button>
        </form>
        <div class="flex gap-6 items-center">
            <p class="text-center block pt-[0.6rem]">Welcome <?php echo htmlspecialchars(ucfirst($user_name)); ?></p>
            <a href="logout.php" class="primary-btn hover:bg-zinc-100 hover:text-zinc-950">Log Out</a>
        </div>
    </nav>
    <div class="p-2 mt-2 rounded shadow sm: w-auto">
        <div class="marquee">
            <p class="text-zinc-950 text-xl font-semibold">New Events On the Way !!!</p>
        </div>
    </div>

    <!-- Search Results -->
    <?php if ($showResults): ?>
        <div class="p-4">
            <h2 class="text-2xl font-bold">Search Results</h2>
            <?php if (!empty($searchResults)): ?>
                <ul class="mt-4">
                    <?php foreach ($searchResults as $result): ?>
                        <li class="border-b py-2">
                            <strong><?php echo htmlspecialchars($result['type']); ?>:</strong> <?php echo htmlspecialchars($result['name']); ?>
                        </li>
                    <?php endforeach; ?>
                </ul>
            <?php else: ?>
                <p class="mt-2">No results found.</p>
            <?php endif; ?>
        </div>
    <?php endif; ?>
</body>
</html>
