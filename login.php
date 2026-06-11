<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);
session_start();

include("connectdb.php");
?>

<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Log In | ParKoto</title>
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
      width: 28rem;
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

    input {
      width: 100%;
      padding: 0.7rem 1rem;
      margin-bottom: 1rem;
      border: 1px solid #d1d5db;
      border-radius: 0.5rem;
      background-color: #f9fafb;
      color: #18181B;
      font-size: 0.95rem;
      transition: border 0.2s, box-shadow 0.2s;
      box-sizing: border-box;
    }

    input:focus {
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
  </style>
</head>

<body class="flex justify-center items-center p-4">
  <div class="flex-col justify-center items-center">
    <form action="login.php" method="post" class="card">
      <h3 class="text-3xl font-bold mb-1">Par<span class="text-red-600">Koto</span></h3>
      <h1 class="text-2xl font-semibold my-5 text-gray-800">Welcome Back</h1>

      <?php
      if (isset($_POST['login_submit'])) {
        $user_name = mysqli_real_escape_string($conn, $_POST['user_name']);
        $user_password = $_POST['user_password'];

        // Check username or emial exists
        $sql = "SELECT * FROM user WHERE user_name = '$user_name' OR email = '$user_name'";
        $result = mysqli_query($conn, $sql);

        if ($result) {
          if (mysqli_num_rows($result) > 0) {
            $row = mysqli_fetch_assoc($result);

            // Verify password
            if (password_verify($user_password, $row['user_password'])) {
              // Store user infor
              $_SESSION['user_id'] = $row['user_id'];
              $_SESSION['user_role'] = $row['rol'];
              $_SESSION['user_name'] = $row['user_name'];

              // Debugging output
              // echo "Role: " . $row['rol']; 
              //exit; //  see the output

              // Redirect based on role
              if ($row['rol'] === 'admin') {
                header("Location: ./admin/admin_home.php");
              } else {
                header("Location: ./user/user_home.php");
              }
              exit;
            } else {
              $error_message = "Invalid username or password!";
            }
          } else {
            $error_message = "Invalid username or password!";
          }
        } else {
          $error_message = "Database query failed: " . mysqli_error($conn);
        }
      }

      ?>

      <label for="username">Username or Email</label>
      <input type="text" name="user_name" id="username" placeholder="Enter username or email" required>

      <label for="user_password">Password</label>
      <input type="password" name="user_password" id="user_password" placeholder="Enter password" required>

      <?php if (isset($error_message)): ?>
        <p class="text-red-600 text-sm mb-2"><?php echo $error_message; ?></p>
      <?php endif; ?>

      <div class="flex justify-end w-full mb-4">
        <a href="#" class="text-gray-600 text-sm link-hover">Forgot password?</a>
      </div>

      <button type="submit" class="primary-btn w-full" name="login_submit">Log In</button>

      <p class="text-gray-600 text-sm mt-5">
        Don't have an account?
        <a href="signup.php" class="text-red-600 font-semibold link-hover">Sign up</a>
      </p>
    </form>
  </div>
</body>

</html>