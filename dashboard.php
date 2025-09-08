<?php
session_start();

// Redirect if not logged in
if (!isset($_SESSION["user_id"]) && !isset($_COOKIE["user_id"])) {
    header("Location: login.php");
    exit;
}

$username = $_SESSION["username"] ?? "User";
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Dashboard</title>
  <link rel="stylesheet" href="style.css">
</head>
<body>
  <div class="form-container">
    <h2>Welcome, <?= htmlspecialchars($username) ?> 🎉</h2>
    <p>You are now logged in to your account.</p>
    <a href="logout.php" class="btn">Logout</a>
  </div>
</body>
</html>
