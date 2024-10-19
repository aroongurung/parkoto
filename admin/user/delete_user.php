<?php
session_start();
include("../../connectdb.php");

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $user_id = $_POST['user_id'];

    // Delete the user
    $sql = "DELETE FROM user WHERE user_id = '$user_id'";
    
    if (mysqli_query($conn, $sql)) {
        // Redirect back to user dashboard
        header("Location: user_dashboard.php");
        exit;
    } else {
        // Check for specific MySQL error codes
        if (mysqli_errno($conn) == 1451) { // Error code for foreign key constraint fails
            $userFriendlyMessage = "This user cannot be deleted because they have related records in the database.";
        } else {
            $userFriendlyMessage = "An error occurred while trying to delete the user. Please try again.";
        }
        
        // Log the detailed error for debugging
        error_log("MySQL Error: " . mysqli_error($conn)); // Log to the server's error log
        error_log("SQL Query: " . $sql); // Log the failed SQL query

        // Display the user-friendly message
        echo "<p style='color: red;'>$userFriendlyMessage</p>";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Delete User</title>
    <script>
        function confirmDelete() {
            return confirm("Are you sure you want to delete this user? This action cannot be undone.");
        }
    </script>
</head>
<body>
    <form action="delete_user.php" method="post" onsubmit="return confirmDelete();">
        <input type="hidden" name="user_id" value="<?php echo htmlspecialchars($user_id); ?>">
        <button type="submit">Delete User</button>
    </form>
</body>
</html>
