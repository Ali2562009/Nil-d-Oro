<?php
session_start();

// Hardcoded admin credentials (replace with database if you want later)
$admin_user = "admin";
$admin_pass = "12345"; // change this to something strong!

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = $_POST['username'];
    $password = $_POST['password'];

    if ($username === $admin_user && $password === $admin_pass) {
        $_SESSION['is_admin'] = true;
        header("Location: admin.php");
        exit;
    } else {
        $error = "❌ Invalid username or password.";
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>Nil d’Oro - Admin Login</title>
<link href="https://fonts.googleapis.com/css2?family=Great+Vibes&display=swap" rel="stylesheet">
<style>
body {
    font-family: "Great Vibes", cursive;
    background: #fdf6f0;
    color: #3a2e2e;
    text-align: center;
    padding: 50px;
}
form {
    width: 300px;
    margin: auto;
    background: #fff;
    padding: 20px;
    border-radius: 10px;
    box-shadow: 0px 4px 8px rgba(0,0,0,0.2);
}
input {
    width: 90%;
    padding: 10px;
    margin: 10px 0;
    border: 1px solid #ccc;
    border-radius: 8px;
}
.btn {
    padding: 10px 20px;
    border: none;
    border-radius: 8px;
    background: #3a2e2e;
    color: #fff;
    cursor: pointer;
    font-size: 18px;
}
.btn:hover {
    background: #5c4747;
}
.error {
    color: red;
    font-family: serif;
}
</style>
</head>
<body>
  <h1>Nil d’Oro — Admin Login</h1>
  <?php if (isset($error)) echo "<p class='error'>$error</p>"; ?>
  <form method="POST">
    <input type="text" name="username" placeholder="Admin Username" required><br>
    <input type="password" name="password" placeholder="Password" required><br>
    <button type="submit" class="btn">Login</button>
  </form>
</body>
</html>
