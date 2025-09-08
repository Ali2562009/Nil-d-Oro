<?php
session_start();
include 'db.php';

// Only logged-in admins can create new admins
if (!isset($_SESSION['admin'])) {
    header("Location: admin_login.php");
    exit();
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $username = trim($_POST['username']);
    $password = trim($_POST['password']);
    $confirm_password = trim($_POST['confirm_password']);

    if ($password !== $confirm_password) {
        $error = "Passwords do not match.";
    } else {
        $hashed_password = password_hash($password, PASSWORD_DEFAULT);

        $stmt = $conn->prepare("INSERT INTO admins (username, password) VALUES (?, ?)");
        $stmt->bind_param("ss", $username, $hashed_password);

        if ($stmt->execute()) {
            $success = "New admin created successfully!";
        } else {
            $error = "Error: Username may already exist.";
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Nil d’Oro - Register Admin</title>
</head>
<body>
  <h2>Create New Admin</h2>
  <?php if (isset($error)) echo "<p style='color:red;'>$error</p>"; ?>
  <?php if (isset($success)) echo "<p style='color:green;'>$success</p>"; ?>
  
  <form method="POST">
    <label>Username: <input type="text" name="username" required></label><br>
    <label>Password: <input type="password" name="password" required></label><br>
    <label>Confirm Password: <input type="password" name="confirm_password" required></label><br>
    <button type="submit">Register Admin</button>
  </form>
  
  <p><a href="admin.php">⬅ Back to Admin Panel</a></p>
</body>
</html>
