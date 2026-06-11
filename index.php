<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>ParKoto</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
        .primary-btn {
            background-color: #181C14;
            padding: 0.65rem 2rem;
            border-radius: 0.4rem;
            color: #FFFAFA;
            transition: all 0.25s ease-in-out;
            font-size: 1.1rem;
            font-weight: 600;
            text-align: center;
            cursor: pointer;
            border: none;
            min-width: 140px;
            box-shadow: 0 2px 6px rgba(0,0,0,0.15);
        }

        .primary-btn:hover {
            background-color: #dc2626;
            transform: translateY(-2px);
            box-shadow: 0 6px 12px rgba(0,0,0,0.15);
        }

        .primary-btn:active {
            transform: translateY(0);
        }

        @media (max-width: 640px) {
            .primary-btn {
                padding: 0.6rem 1.2rem;
                font-size: 1rem;
                min-width: 120px;
            }
            h1 {
                font-size: 2.2rem !important;
                text-align: center;
            }
            .flex.gap-12 {
                gap: 1.5rem;
            }
        }
    </style>
</head>

<body class="bg-gradient-to-br from-gray-50 to-gray-100 flex justify-center items-center min-h-screen p-5">
    <div class="flex flex-col justify-center items-center text-center">
        <div class="mb-8">
            <h1 class="text-5xl md:text-6xl tracking-tight text-[#181C14] font-bold">Welcome to Par<span class="text-red-600">Koto</span></h1>
        </div>
        <div class="flex flex-col sm:flex-row gap-5 sm:gap-8">
            <button class="primary-btn" onclick="location.href='login.php'">Log In</button>
            <button class="primary-btn" onclick="location.href='signup.php'">
                Register
                <span class="block text-xs font-normal opacity-80 mt-0.5">Don't have account?</span>
            </button>
        </div>
    </div>
</body>

</html>