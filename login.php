<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);
session_start(); // Start the session

include("connectdb.php"); //connects to your database
?>

<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Log In</title>
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
        margin-bottom: 0.2rem;
        border: 0.1rem solid #181C14;
        background-color: #F8FAFC;
        color: #18181B;
      }
      ::placeholder{
        text-align: center;
      }

      body {
        color: #18181B;
      }
    }
  </style>
</head>

<body class="flex justify-center items-center">
  <div class="flex-col justify-center items-center gap-12 mt-[2rem] py-[2rem]">
    <form action="login.php" method="post" class="card">
      <h3 class="text-4xl font-bold mt-[1.2rem] underline">Par<span class="text-red-700">Koto</span></h3>
      <h1 class="text-4xl font-bold my-6">Log In</h1>

      <?php
      if (isset($_POST['login_submit'])) {
        $user_name = mysqli_real_escape_string($conn, $_POST['user_name']);
        $user_password = $_POST['user_password'];
    
        // Check if username or email exists
        $sql = "SELECT * FROM user WHERE user_name = '$user_name' OR email = '$user_name'";
        $result = mysqli_query($conn, $sql);
    
        if ($result) {
            if (mysqli_num_rows($result) > 0) {
                $row = mysqli_fetch_assoc($result);
    
                // Verify password
                if (password_verify($user_password, $row['user_password'])) {
                    // Store user information
                    $_SESSION['user_id'] = $row['user_id']; // Updated to use 'user_id'
                    $_SESSION['user_role'] = $row['rol'];
                    $_SESSION['user_name'] = $row['user_name'];
    
                    // For Debugging output
                   // echo "Role: " . $row['rol']; 
                    //exit; // Temporarily stop script to see the output
    
                    // Redirect based on role
                    if ($row['rol'] === 'admin') {
                        header("Location: ./admin/admin_home.php");
                    } else {
                        header("Location: hero.php");
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

      <label for="username">Username/Email</label>
      <input type="text" name="user_name" placeholder="Enter Username/Email" required>
      <br>
      <label for="user_password">Password</label>
      <input type="password" name="user_password" placeholder="Enter Password" required>
      <br>
      <?php if (isset($error_message)): ?>
        <p class="text-red-600"><?php echo $error_message; ?></p>
      <?php endif; ?>
      <a href="#" class=" text-zinc-950 text-sm hover:text-red-700">Forgot your Password?</a>
      <br>
      <a href="signup.php" class=" text-zinc-950 hover:text-red-700">Don't have an account?</a>
      <br>
      <button type="submit" class="primary-btn" name="login_submit">Enter</button>
    </form>
  </div>
</body>

</html>
