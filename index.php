<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>ParKoto</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <style type="text/tailwindcss">
        @layer utilities{
            .primary-btn{
            background-color: #181C14;
            width: 12rem;
            height: 5rem;
            border-radius: 0.8rem;
            color: #FFFAFA;
            transition: 0.3s ease-in-out;
            font-size: 1.6rem;
            font-weight: bold;
            text-align: center;
        }
        
          .primary-btn:hover{
            background-color: rgb(185 28 28);
            color: #FFFAFA;
                      
          }
         
         }
    </style>
</head>

<body class="bg-grey-400 flex justify-center items-center">
    <div class="flex flex-col justify-center items-center mt-[12rem] text-white">
        <div>
            <h1 class="text-6xl tracking-wide text-[#181C14] font-bold drop-shadow-xl">Welcome to Par<span class="text-red-700 ">Koto</span></h1>
        </div>
        <div class="flex gap-12 mt-24">
            <button class="primary-btn shadow-2xl shadow-slate-950" onclick="location.href='login.php'">Log In</button>
            <button class="primary-btn shadow-2xl shadow-slate-950" onclick="location.href='signup.php'">Register<p class="text-xs">Don't have account ?</p></button>
        </div>
    </div>

</body>

</html>