<?php
session_start();
include 'db.php';

// ✅ Only logged-in users
if (!isset($_SESSION['user'])) {
    header("Location: login.php");
    exit();
}

$user = $_SESSION['user'];
$message = "";

// ✅ Update profile
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = trim($_POST['email']);
    $whatsapp = trim($_POST['whatsapp']);
    $password = trim($_POST['password']);

    if (!empty($email) && !empty($whatsapp)) {
        if (!empty($password)) {
            // Update with password
            $hashed = password_hash($password, PASSWORD_DEFAULT);
            $stmt = $conn->prepare("UPDATE users SET email=?, whatsapp=?, password=? WHERE id=?");
            $stmt->bind_param("sssi", $email, $whatsapp, $hashed, $user['id']);
        } else {
            // Update without password
            $stmt = $conn->prepare("UPDATE users SET email=?, whatsapp=? WHERE id=?");
            $stmt->bind_param("ssi", $email, $whatsapp, $user['id']);
        }
        if ($stmt->execute()) {
            $message = "✅ Profile updated successfully!";
            $_SESSION['user']['email'] = $email;
            $_SESSION['user']['whatsapp'] = $whatsapp;
        } else {
            $message = "❌ Error updating profile.";
        }
        $stmt->close();
    } else {
        $message = "⚠️ Email and WhatsApp cannot be empty.";
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>Nil d’Oro - Profile</title>
<link href="https://fonts.googleapis.com/css2?family=Great+Vibes&display=swap" rel="stylesheet">
<style>
body { margin:0; font-family:'Times New Roman', serif; background:#f9f5ec; color:#333; }
.header { background:#2e2e2e; padding:15px; display:flex; justify-content:space-between; align-items:center; }
.logo { font-family:'Great Vibes', cursive; font-size:48px; color:#f5e6cc; }
.nav a { color:#f5e6cc; margin:0 12px; text-decoration:none; font-size:18px; }
.nav a:hover { text-decoration:underline; }
.container { max-width:600px; margin:40px auto; background:#fff; padding:30px; border-radius:12px; box-shadow:0 4px 12px rgba(0,0,0,0.1); }
.container h2 { font-family:'Great Vibes', cursive; font-size:42px; text-align:center; margin-bottom:20px; }
input { width:100%; padding:12px; margin:10px 0; border:1px solid #ccc; border-radius:8px; font-size:16px; }
button { background:#bfa76f; color:#fff; border:none; padding:12px 20px; border-radius:8px; font-size:18px; cursor:pointer; }
button:hover { background:#a18f59; }
.message { text-align:center; font-size:18px; margin:15px 0; color:#2e2e2e; }
.footer { background:#2e2e2e; text-align:center; padding:15px; color:#f5e6cc; margin-top:40px; }
</style>
</head>
<body>
  <div class="header">
    <div class="logo">Nil d’Oro</div>
    <div class="nav">
      <a href="index.php">Home</a>
      <a href="products.php">Products</a>
      <a href="cart.php">Cart</a>
      <a href="contact.php">Contact</a>
      <a href="profile.php">Profile</a>
      <a href="logout.php">Logout</a>
    </div>
  </div>

  <div class="container">
    <h2>Your Profile</h2>
    <?php if ($message): ?>
      <p class="message"><?= $message ?></p>
    <?php endif; ?>
    <form method="POST">
      <input type="email" name="email" value="<?= htmlspecialchars($user['email']) ?>" required>
      <input type="text" name="whatsapp" value="<?= htmlspecialchars($user['whatsapp']) ?>" required>
      <input type="password" name="password" placeholder="New Password (leave blank if unchanged)">
      <button type="submit">Update Profile</button>
    </form>
  </div>

  <div class="footer">
    <p>© <?= date('Y') ?> Nil d’Oro — All Rights Reserved</p>
  </div>
</body>
</html>
