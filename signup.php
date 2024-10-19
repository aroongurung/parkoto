<?php
session_start(); // Start the session 
?>

<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Sign Up</title>
  <script src="https://cdn.tailwindcss.com"></script>
  <style type="text/tailwindcss">
    @layer utilities {
      .primary-btn {
        background-color: #c53030;
        width: 8rem;
        height: 2.6rem;
        border-radius: 0.4rem;
        color: #18181B;
        transition: 0.2s ease-in;
        font-size: 1.6rem;
        text-align: center;
      }

      .primary-btn:hover {
        background-color: #181C14;
        color: #FFFAFA;
      }

      .card {
        background-color: #F8FAFC;
        border: 0.2rem solid #E2E8F0;
        padding: 0.2rem 0.2rem;
        min-height: 38rem;
        min-width: 30rem;
        border-radius: 0.6rem;
        box-shadow: 0.8rem 0.8rem 1.2rem rgb(26, 20, 20);
        display: flex;
        flex-direction: column;
        justify-content: start;
        align-items: center;
        font-size: 1.2rem;
      }

      label {
        font-size: 1.6rem;
        font-weight: 600;
      }

      input {
        text-align: left;
        padding: 0.2rem 0.6rem;
        border-radius: 0.4rem;
        margin-bottom: 0.8rem;
        border: 0.1rem solid #181C14;
        background-color: #F8FAFC;
        color: #18181B;
      }

      body {
        color: #18181B;
      }
    }
  </style>
  </style>
  <script>
    function redirectToLogin() {
      setTimeout(function() {
        window.location.href = 'login.php';
      }, 2000); // Redirect after 2 seconds
    }
  </script>
</head>

<body class="flex justify-center items-center">
  <div class="flex-col justify-center items-center gap-12 mt-[2rem] py-[2rem]">
    <form action="signup_connect.php" method="post" class="card">
      <h3 class="text-4xl font-bold mt-[1.2rem] underline">Par<span class="text-red-700">Koto</span></h3>
      <h1 class="text-2xl font-bold my-[1rem]">Registration Form</h1>
      <p class="text-sm mb-[1rem]">Please provide following details</p>

      <input type="text" name="person_name" placeholder="Enter Full Name" required>
      <input type="text" name="user_name" placeholder="Enter Username" required>
      <input type="email" name="email" placeholder="Enter email" required>
      <input type="password" id="user_password" name="user_password" placeholder="Enter Password" required>
      <input type="password" id="user_re_password" name="user_re_password" placeholder="Re-enter Password" required>
      <input type="text" name="person_address" placeholder="Enter Address" required>
      <input type="text" name="phone_number" placeholder="Enter Phone Number" required>

      <a href="login.php" class="my-[1rem] text-xs font-bold text-zinc-950 hover:text-red-700">Already have an Account? <span>Log In</span></a>

      <select name="rol" class="border-2 border-zinc-950 rounded-lg w-32 text-center">
        <option value="user">User</option>
        <option value="admin" >Admin</option>
      </select>
      <input type="checkbox" name="agree_read" value="yes" class="mt-4 accent-zinc-950">
      <label for="agree_read" class="text-sm -mt-3">I've read and agree to the <a href="#">terms of service</a></label>
      <button type="submit" class="primary-btn my-[1rem]" name="signup_submit">Register</button>
    </form>
  </div>

  <?php if (isset($_SESSION['success_message'])): ?>
    <div class="message">
        <h2><?php echo $_SESSION['success_message']; ?></h2>
        <script>
          redirectToLogin(); // Call the redirect function after displaying the message
        </script>
        <?php unset($_SESSION['success_message']); // Clear the message after displaying ?>
    </div>
  <?php endif; ?>

</body>

</html>
