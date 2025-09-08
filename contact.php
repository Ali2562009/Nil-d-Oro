<?php
session_start();
include 'db.php'; // database connection

$message = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $name = trim($_POST['name']);
    $email = trim($_POST['email']);
    $whatsapp = trim($_POST['whatsapp']);
    $msg = trim($_POST['message']);

    if (!empty($name) && !empty($email) && !empty($whatsapp) && !empty($msg)) {
        $stmt = $conn->prepare("INSERT INTO contacts (name, email, whatsapp, message) VALUES (?, ?, ?, ?)");
        $stmt->bind_param("ssss", $name, $email, $whatsapp, $msg);
        if ($stmt->execute()) {
            $message = "✅ Thank you! Your message has been sent.";
        } else {
            $message = "❌ Error: Could not send message.";
        }
        $stmt->close();
    } else {
        $message = "⚠️ Please fill in all fields.";
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>Nil d’Oro - Contact</title>
<link href="https://fonts.googleapis.com/css2?family=Great+Vibes&display=swap" rel="stylesheet">
<style>
body { margin:0; font-family:'Times New Roman', serif; background:#f9f5ec; color:#333; }
.header { background:#2e2e2e; padding:15px; display:flex; justify-content:space-between; align-items:center; }
.logo { font-family:'Great Vibes', cursive; font-size:48px; color:#f5e6cc; }
.nav a { color:#f5e6cc; margin:0 12px; text-decoration:none; font-size:18px; }
.nav a:hover { text-decoration:underline; }
.container { max-width:700px; margin:40px auto; background:#fff; padding:30px; border-radius:12px; box-shadow:0 4px 12px rgba(0,0,0,0.1); }
.container h2 { font-family:'Great Vibes', cursive; font-size:42px; text-align:center; margin-bottom:20px; }
input, textarea { width:100%; padding:12px; margin:10px 0; border:1px solid #ccc; border-radius:8px; font-size:16px; }
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
      <?php if (isset($_SESSION['user'])): ?>
        <a href="logout.php">Logout</a>
      <?php else: ?>
        <a href="login.php">Login</a>
      <?php endif; ?>
    </div>
  </div>

  <div class="container">
    <h2>Contact Nil d’Oro</h2>
    <?php if ($message): ?>
      <p class="message"><?= $message ?></p>
    <?php endif; ?>
    <form method="POST">
      <input type="text" name="name" placeholder="Your Name" required>
      <input type="email" name="email" placeholder="Your Email" required>
      <input type="text" name="whatsapp" placeholder="Your WhatsApp Number" required>
      <textarea name="message" rows="5" placeholder="Your Message" required></textarea>
      <button type="submit">Send Message</button>
    </form>
  </div>

  <div class="footer">
    <p>© <?= date('Y') ?> Nil d’Oro — All Rights Reserved</p>
  </div>
</body>
</html>
