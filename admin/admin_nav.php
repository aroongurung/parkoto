<?php
// Define the base path for includes
define('BASE_PATH', dirname(__FILE__) . '/../');

// Include the database connection
include(BASE_PATH . 'connectdb.php');

$searchResults = [];
$showResults = false; // Initialize a variable to control the display of results

if (isset($_POST['search_box']) && !empty($_POST['search_box'])) {
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
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <script src="https://cdn.tailwindcss.com"></script>
    <title>Welcome to ParKoto</title>
    <style>
        .marquee {
            overflow: hidden;
            white-space: nowrap;
            box-sizing: border-box;
            animation: scroll 10s linear infinite;
        }

        @keyframes scroll {
            0% { transform: translateX(70%); }
            100% { transform: translateX(-10%); }
        }
    </style>
</head>
<body>
    <!-- Nav Bar -->
    <nav class="flex justify-between items-center bg-zinc-950 py-[0.8rem] px-[2rem] rounded-md text-[#FFFAFA]">
        <a href="../hero.php" class="logo">
            <h1 class="text-4xl font-bold tracking-[0.1rem] text-[#FFFAFA]">Par<span class="text-red-700">Koto</span></h1>
        </a>
        <div class="flex gap-16 text-2xl tracking-[0.1rem]">
           <!-- <a href="../hero.php" class="text-red-700 hover:text-white text-3xl">Home</a> -->
            <div class="marquee">
                <p class="text-[#FFFAFA]">New Events coming Soon...</p>
            </div>
        </div>
        <form method="POST" class="flex gap-6 items-center">
            <input type="text" name="search_box" placeholder="Search..." class="pl-4 text-zinc-950 border rounded">
            <button type="submit" class="bg-red-700 text-white px-4 py-1 rounded">Search</button>
        </form>
    </nav>

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
