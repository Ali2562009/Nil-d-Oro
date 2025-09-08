<?php
include "db.php";

$errors = [];
$success = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = trim($_POST["username"]);
    $email = trim($_POST["email"]);
    $password = $_POST["password"];

    // --- Validation ---
    if (strlen($username) < 3 || !preg_match("/^[a-zA-Z0-9_]+$/", $username)) {
        $errors[] = "Username must be at least 3 characters and contain only letters, numbers, or underscores.";
    }

    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $errors[] = "Invalid email format.";
    } else {
        // Check if email already exists
        $check = $conn->prepare("SELECT id FROM users WHERE email = ?");
        $check->bind_param("s", $email);
        $check->execute();
        $check->store_result();
        if ($check->num_rows > 0) {
            $errors[] = "This email is already registered.";
        }
    }

    if (strlen($password) < 6 || !preg_match("/[A-Za-z]/", $password) || !preg_match("/[0-9]/", $password)) {
        $errors[] = "Password must be at least 6 characters and contain both letters and numbers.";
    }

    // --- Insert into DB if no errors ---
    if (empty($errors)) {
        $hashedPassword = password_hash($password, PASSWORD_DEFAULT);
        $stmt = $conn->prepare("INSERT INTO users (username, email, password) VALUES (?, ?, ?)");
        $stmt->bind_param("sss", $username, $email, $hashedPassword);

        if ($stmt->execute()) {
            $success = "✅ Signup successful! <a href='login.php'>Login here</a>";
        } else {
            $errors[] = "Database error: " . $stmt->error;
        }
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Sign Up</title>
  <link rel="stylesheet" href="style.css">
</head>
<body>
  <div class="form-container">
    <h2>Create Account</h2>

    <?php if (!empty($errors)): ?>
      <div class="error">
        <ul>
          <?php foreach ($errors as $error) echo "<li>$error</li>"; ?>
        </ul>
      </div>
    <?php endif; ?>

    <?php if (!empty($success)) echo "<p class='success'>$success</p>"; ?>

    <form method="post">
      <input type="text" name="username" placeholder="Username" value="<?= htmlspecialchars($username ?? '') ?>" required>
      <input type="email" name="email" placeholder="Email" value="<?= htmlspecialchars($email ?? '') ?>" required>
      <input type="password" name="password" placeholder="Password" required>
      <button type="submit">Sign Up</button>
    </form>
    <p>Already have an account? <a href="login.php">Login</a></p>
  </div>
</body>
</html>
