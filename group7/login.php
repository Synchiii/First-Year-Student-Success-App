<?php
// Start session to track login status
session_start();

// Define hardcoded username and password
$valid_username = "admin";
$valid_password = "1234";

// Check if form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = $_POST["username"] ?? '';
    $password = $_POST["password"] ?? '';

    // Validate credentials
    if ($username === $valid_username && $password === $valid_password) {
        $_SESSION["loggedin"] = true;
        $_SESSION["username"] = $username;
        echo "<h2>Login successful! Welcome, $username.</h2>";
    } else {
        echo "<h2 style='color:red;'>Invalid username or password.</h2>";
    }
}
?>

<!-- HTML Login Form -->
<!DOCTYPE html>
<html>
<head>
    <title>Login Page</title>
</head>
<body>
    <h2>Login</h2>
    <form method="POST" action="">
        <label>Username:</label><br>
        <input type="text" name="username" required><br><br>
        
        <label>Password:</label><br>
        <input type="password" name="password" required><br><br>
        
        <input type="submit" value="Login">
    </form>
</body>
</html>
