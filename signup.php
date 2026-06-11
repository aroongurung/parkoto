<?php
session_start();
?>

<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Sign Up | ParKoto</title>
  <script src="https://cdn.tailwindcss.com"></script>
  <style>
    .primary-btn {
      background-color: #181C14;
      padding: 0.6rem 1.8rem;
      border-radius: 0.5rem;
      color: #FFFAFA;
      transition: all 0.25s ease-in-out;
      font-size: 1rem;
      font-weight: 600;
      text-align: center;
      cursor: pointer;
      border: none;
      width: 100%;
      box-shadow: 0 1px 3px rgba(0, 0, 0, 0.1);
    }

    .primary-btn:hover {
      background-color: #dc2626;
      transform: translateY(-1px);
      box-shadow: 0 4px 8px rgba(0, 0, 0, 0.12);
    }

    .primary-btn:active {
      transform: translateY(0);
    }

    .card {
      background-color: #FFFFFF;
      border: 1px solid #E2E8F0;
      padding: 2rem 2rem;
      width: 32rem;
      max-width: 90vw;
      border-radius: 1rem;
      box-shadow: 0 10px 25px -5px rgba(0, 0, 0, 0.1), 0 8px 10px -6px rgba(0, 0, 0, 0.02);
      display: flex;
      flex-direction: column;
      justify-content: center;
      align-items: center;
    }

    label {
      font-size: 0.9rem;
      font-weight: 600;
      margin-bottom: 0.25rem;
      align-self: flex-start;
      color: #1f2937;
    }

    input,
    select {
      width: 100%;
      padding: 0.7rem 1rem;
      border: 1px solid #d1d5db;
      border-radius: 0.5rem;
      background-color: #f9fafb;
      color: #18181B;
      font-size: 0.95rem;
      transition: border 0.2s, box-shadow 0.2s;
      box-sizing: border-box;
    }

    .input-wrapper {
      width: 100%;
      padding: 0 0.5rem;
      margin-bottom: 0.5rem;
    }

    input:focus,
    select:focus {
      outline: none;
      border-color: #dc2626;
      box-shadow: 0 0 0 3px rgba(220, 38, 38, 0.1);
    }

    input::placeholder {
      color: #9ca3af;
      font-size: 0.85rem;
      padding-left: 0.2rem;
    }

    body {
      background: linear-gradient(135deg, #f5f7fa 0%, #e9edf2 100%);
      min-height: 100vh;
    }

    .link-hover {
      transition: color 0.2s ease;
    }

    .link-hover:hover {
      color: #dc2626;
    }

    .checkbox-container {
      display: flex;
      align-items: center;
      gap: 0.5rem;
      width: 100%;
      margin-bottom: 1.2rem;
    }

    .checkbox-container input {
      width: auto;
      margin-bottom: 0;
      accent-color: #dc2626;
    }

    .checkbox-container label {
      margin-bottom: 0;
      font-size: 0.85rem;
      font-weight: normal;
    }

    select {
      cursor: pointer;
      width: 100%;
      padding: 0.7rem 1rem;
      margin-bottom: 1.2rem;
      border: 1px solid #d1d5db;
      border-radius: 0.5rem;
      background-color: #f9fafb;
      color: #18181B;
      font-size: 0.75rem;
    }
  </style>
  <script>
    function redirectToLogin() {
      setTimeout(function() {
        window.location.href = 'login.php';
      }, 2000); //Redirects after 2secs
    }
  </script>
</head>

<body class="flex justify-center items-center p-4">
  <div class="flex-col justify-center items-center">
    <form action="signup_connect.php" method="post" class="card">
      <h3 class="text-3xl font-bold mb-1">Par<span class="text-red-600">Koto</span></h3>
      <h1 class="text-2xl font-semibold my-2 text-gray-800">Create Account</h1>
      <p class="text-sm text-gray-500 mb-5">Please fill in your details</p>

      <div class="input-wrapper">
        <input type="text" name="person_name" placeholder="Full Name" required>
      </div>
      <div class="input-wrapper">
        <input type="text" name="user_name" placeholder="Username" required>
      </div>
      <div class="input-wrapper">
        <input type="email" name="email" placeholder="Email Address" required>
      </div>
      <div class="input-wrapper">
        <input type="password" id="user_password" name="user_password" placeholder="Password" required>
      </div>
      <div class="input-wrapper">
        <input type="password" id="user_re_password" name="user_re_password" placeholder="Re-enter Password" required>
      </div>
      <div class="input-wrapper">
        <input type="text" name="person_address" placeholder="Address" required>
      </div>
      <div class="input-wrapper">
        <input type="text" name="phone_number" placeholder="Phone Number" required>
      </div>

      <div class="input-wrapper">
        <select name="rol" required>
          <option value="user">User</option>
          <option value="admin">Admin</option>
        </select>
      </div>

      <div class="checkbox-container">
        <input type="checkbox" name="agree_read" value="yes" required>
        <label>I've read and agree to the <a href="#" class="text-red-600 link-hover">terms of service</a></label>
      </div>

      <div class="input-wrapper">
        <button type="submit" class="primary-btn" name="signup_submit">Register</button>
      </div>

      <p class="text-gray-600 text-sm mt-4">
        Already have an account?
        <a href="login.php" class="text-red-600 font-semibold link-hover">Log In</a>
      </p>
    </form>
  </div>

  <?php if (isset($_SESSION['success_message'])): ?>
    <div class="fixed top-4 right-4 bg-green-500 text-white px-6 py-3 rounded-lg shadow-lg z-50">
      <h2 class="text-sm font-semibold"><?php echo $_SESSION['success_message']; ?></h2>
      <script>
        redirectToLogin(); // Redirects after displaying the message
      </script>
      <?php unset($_SESSION['success_message']); // Clear message after displaying 
      ?>
    </div>
  <?php endif; ?>



</body>

</html>