<?php
ob_start(); // Start output buffering
session_start();
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Main Parking Page</title>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/js/all.min.js"></script>
    <script src="https://cdn.tailwindcss.com"></script>
    <style type="text/tailwindcss">
        @layer utilities {
            .primary-btn {
                background-color: #c53030;
                width: 8rem;
                height: 2.6rem;
                border-radius: 0.4rem;
                color: #FFFAFA;
                transition: 0.2s ease-in;
                font-size: 1.6rem;
                text-align: center;
            }

            .toggle-btn {
                background-color: #c53030;
                width: 12rem;
                height: 2.6rem;
                border-radius: 0.4rem;
                color: #18181B;
                transition: 0.2s ease-in;
                font-size: 1.6rem;
                text-align: center;
            }

            .card {
                background-color: #F8FAFC;
                border: 0.2rem solid #E2E8F0;
                padding: 0.2rem 0.2rem;
                min-height: 30rem;
                min-width: 24rem;
                border-radius: 0.6rem;
                box-shadow: 0.8rem 0.8rem 1.2rem rgb(26, 20, 20);
                display: flex;
                flex-direction: column;
                padding: 0 0.6rem;
                align-items: center;
                justify-content: center;
                display: none; /* Hide sections by default */
            }

            .card.active {
                display: flex; /* Show active section */
            }

            label {
                font-size: 1.6rem;
                font-weight: 600;
            }

            input {
                text-align: left;
                padding: 0.2rem 0.6rem;
                border-radius: 0.4rem;
                margin-bottom: 0.2rem;
                border: 0.1rem solid #181C14;
                background-color: #F8FAFC;
                color: #18181B;
            }

            body {
                color: #18181B;
            }

            h1 {
                color: #181C14;
            }
        }
    </style>
    <script>
        function toggleSection(section) {
            const sections = {
                person: document.getElementById('person-card'),
                car: document.getElementById('car-card')
            };

            // Toggle the clicked section
            const selectedSection = sections[section];
            if (selectedSection.classList.contains('active')) {
                selectedSection.classList.remove('active'); // Hide if already active
            } else {
                // Hide all sections
                Object.values(sections).forEach(s => s.classList.remove('active'));
                // Show the selected section
                selectedSection.classList.add('active');
            }
        }
    </script>
</head>

<body class="mx-32 my-2">
    <?php
    include("./user/navbar.php");
    // Assuming user role is stored in session
    $userRole = isset($_SESSION['role']) ? $_SESSION['role'] : 'default_role';
    $userName = isset($_SESSION['username']) ? $_SESSION['username'] : 'Guest';
    $userSSN = isset($_SESSION['ssn']) ? $_SESSION['ssn'] : '';
    ?>

    <div class="flex flex-col items-center justify-center gap-2 mt-[1rem] border-1 border-neutral-300">
        <div class="flex gap-5">
            <button class="toggle-btn" onclick="toggleSection('person')">Person</button>
            <button class="toggle-btn" onclick="toggleSection('car')">Car</button>
        </div>

        <!-- Person Section -->
        <form action="./user/person/person_query.php" method="post" class="flex gap-[6rem]">
            <div id="person-card" class="card active">
                <h1 class="mt-[1rem] mb-[1rem] text-5xl font-bold underline decoration-red-700">Person</h1>
                <label for="person_name">Name</label>
                <input type="text" id="person_name" name="person_name" placeholder="Enter your name" required>
                <label for="ssn">SSN</label>
                <input type="text" name="ssn" placeholder="Enter your SSN" required>
                <div class="my-[1.8rem]">
                    <button class="primary-btn" type="submit" name="person_search">Search</button>
                </div>
            </div>
        </form>


        <!-- Car Section -->
        <form action="./user/car/car_query.php" method="post" class="flex gap-[6rem]">
            <div id="car-card" class="card">
                <h1 class="mt-[1rem] mb-[2rem] text-5xl font-bold underline decoration-red-700">Car</h1>
                <label for="register">Register</label>
                <input type="text" name="register" placeholder="Register No.">

                <label for="owner_id">Owner ID (SSN)</label>
                <input type="text" name="owner_id" placeholder="Owner ID (SSN)">

                <div class="my-[1.8rem]">
                    <button class="primary-btn" type="submit" name="car_search">Search</button>
                </div>
            </div>
        </form>
        <div class="container mx-auto mt-10">
            <h1 class="text-4xl font-bold text-center mb-5">Pictures are uploaded every day.</h1>
            <div class="columns-1 sm:columns-2 md:columns-3 lg:columns-4 gap-4">

                <?php
                // Array of image URLs (you can replace these with your own image URLs)
                $images = [
                    "https://images.pexels.com/photos/919073/pexels-photo-919073.jpeg?auto=compress&cs=tinysrgb&w=1260&h=750&dpr=1",
                    "https://images.pexels.com/photos/2526128/pexels-photo-2526128.jpeg?auto=compress&cs=tinysrgb&w=600",
                    "https://images.pexels.com/photos/3422964/pexels-photo-3422964.jpeg?auto=compress&cs=tinysrgb&w=600",
                    "https://images.pexels.com/photos/1649761/pexels-photo-1649761.jpeg?auto=compress&cs=tinysrgb&w=600",
                    "https://images.pexels.com/photos/315938/pexels-photo-315938.jpeg?auto=compress&cs=tinysrgb&w=600",
                    "https://images.pexels.com/photos/191842/pexels-photo-191842.jpeg?auto=compress&cs=tinysrgb&w=600",
                    "https://images.pexels.com/photos/5086489/pexels-photo-5086489.jpeg?auto=compress&cs=tinysrgb&w=600",
                    "https://images.pexels.com/photos/498703/pexels-photo-498703.jpeg?auto=compress&cs=tinysrgb&w=600",
                    "https://images.pexels.com/photos/226460/pexels-photo-226460.jpeg?auto=compress&cs=tinysrgb&w=600",
                    "https://images.pexels.com/photos/2539244/pexels-photo-2539244.jpeg?auto=compress&cs=tinysrgb&w=600",
                    "https://images.pexels.com/photos/12266858/pexels-photo-12266858.jpeg?auto=compress&cs=tinysrgb&w=600&lazy=load",
                    "https://images.pexels.com/photos/27902293/pexels-photo-27902293/free-photo-of-cup-of-brewed-coffee-on-table.jpeg?auto=compress&cs=tinysrgb&w=600&lazy=load",
                    "https://images.pexels.com/photos/28492538/pexels-photo-28492538/free-photo-of-close-up-of-a-purple-aster-in-autumn-bloom.jpeg?auto=compress&cs=tinysrgb&w=600&lazy=load",
                    "https://images.pexels.com/photos/3243242/pexels-photo-3243242.jpeg?auto=compress&cs=tinysrgb&w=600",
                    "https://images.pexels.com/photos/6262891/pexels-photo-6262891.jpeg?auto=compress&cs=tinysrgb&w=600",
                    "https://images.pexels.com/photos/28488786/pexels-photo-28488786/free-photo-of-sailboat-on-st-lawrence-river-in-quebec-city.jpeg?auto=compress&cs=tinysrgb&w=600&lazy=load",
                    "https://images.pexels.com/photos/8743102/pexels-photo-8743102.jpeg?auto=compress&cs=tinysrgb&w=600&lazy=load",
                    "https://images.pexels.com/photos/28807159/pexels-photo-28807159/free-photo-of-futuristic-yellow-subway-tunnel-architecture.jpeg?auto=compress&cs=tinysrgb&w=600&lazy=load",
                    "https://images.pexels.com/photos/27498575/pexels-photo-27498575/free-photo-of-sea-coast-of-new-york-with-brooklyn-bridge-at-sunset.jpeg?auto=compress&cs=tinysrgb&w=600&lazy=load",
                    "https://images.pexels.com/photos/28235675/pexels-photo-28235675/free-photo-of-a-city-at-night-with-a-bridge-and-buildings.jpeg?auto=compress&cs=tinysrgb&w=600&lazy=load",
                    "https://images.pexels.com/photos/27977077/pexels-photo-27977077/free-photo-of-black-white-horse.jpeg?auto=compress&cs=tinysrgb&w=600&lazy=load",
                    "https://images.pexels.com/photos/27860596/pexels-photo-27860596/free-photo-of-on-the-foggy-day.jpeg?auto=compress&cs=tinysrgb&w=600&lazy=load",
                    "https://images.pexels.com/photos/19710731/pexels-photo-19710731/free-photo-of-river-and-silhouettes-of-mosques-in-a-morning-light.jpeg?auto=compress&cs=tinysrgb&w=600&lazy=load",
                    "https://images.pexels.com/photos/28617352/pexels-photo-28617352/free-photo-of-high-speed-train-at-modern-railway-station.jpeg?auto=compress&cs=tinysrgb&w=600&lazy=load",

                ];

                /*foreach ($images as $image) {
                    echo "
                    <div class='relative mb-4 overflow-hidden rounded-lg'>
                        <img src='$image' alt='Random Image' class='w-full h-auto transition-transform duration-300 transform hover:scale-110 hover:rotate-2 hover:blur-sm'>
                        <div class='absolute inset-0 bg-black bg-opacity-30 flex items-center justify-center opacity-0 hover:opacity-100 transition-opacity duration-300'>
                            <p class='text-white font-semibold text-lg'>Image Description</p>
                        </div>
                    </div>
                    ";
                } */
                foreach ($images as $image) {
                    echo "
                    <div class='relative mb-4 overflow-hidden rounded-lg '>
                        <img src='$image' alt='Random Image' class='border-4 border-zinc-950 w-full h-auto transition-transform duration-300 transform hover:scale-110 hover:shadow-2xl'>
                    </div>
                    ";
                }

                ?>
            </div>
        </div>



        <!-- Fine Section Access Denied -->
        <div id="fine-card" class="card flex flex-col items-center justify-center">
            <h1 class="text-4xl font-bold py-[1rem] px-[1rem] w-[12rem] h-[12rem] rounded-[2rem] bg-red-700 mt-[10%] text-center">Access Denied<p class="text-center text-xl mt-[1.2rem]">Only Special Users</p>
            </h1>
        </div>

    </div>

    <?php include("./user/footer.php"); ?>
</body>

</html>
<?php
ob_end_flush();
?>