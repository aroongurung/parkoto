<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Oops! Something went wrong</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
        body {
            height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
        }
    </style>
</head>

<body class="bg-gray-50">
    <div class="max-w-md w-full text-center px-6">
        <!-- Error Icon -->
        <div class="text-8xl mb-6">⚠️</div>

        <h1 class="text-6xl font-bold text-red-600 mb-2">Oops!</h1>
        <h2 class="text-2xl font-semibold text-gray-800 mb-4">Something went wrong</h2>

        <p class="text-gray-600 mb-8">
            We're sorry, but the page you requested couldn't be processed.
        </p>

        <?php if (isset($_GET['msg']) && !empty($_GET['msg'])): ?>
            <div class="bg-red-100 border border-red-300 text-red-700 px-4 py-3 rounded-lg mb-8 text-left">
                <strong class="block mb-1">Error details:</strong>
                <?= htmlspecialchars($_GET['msg']) ?>
            </div>
        <?php endif; ?>

        <div class="space-y-3">
            <a href="/"
                class="block w-full bg-blue-500 hover:bg-blue-400 text-white font-medium py-3 px-6 rounded-lg transition">
                ← Back to Home
            </a>

            <button onclick="history.back()"
                class="block w-full bg-gray-200 hover:bg-gray-300 text-gray-800 font-medium py-3 px-6 rounded-lg transition">
                Go Back
            </button>
        </div>
    </div>

</body>

</html>