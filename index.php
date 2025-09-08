<?php
session_start();

// If logged in → redirect to dashboard
if (isset($_SESSION["user_id"]) || isset($_COOKIE["user_id"])) {
    header("Location: dashboard.php");
    exit;
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Welcome</title>
  <link rel="stylesheet" href="style.css">
</head>
<body>
  <div class="form-container">
    <h2>Welcome to Our Store ✨</h2>
    <p>Please choose an option below:</p>
    <a href="signup.php" class="btn">Sign Up</a>
    <a href="login.php" class="btn">Login</a>
  </div>
</body>
</html>
