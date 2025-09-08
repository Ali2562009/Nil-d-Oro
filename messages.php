<?php
session_start();
include 'db.php';

// ✅ Only admin can access
if (!isset($_SESSION['user']) || $_SESSION['user']['role'] !== 'admin') {
    header("Location: login.php");
    exit();
}

$result = $conn->query("SELECT * FROM contacts ORDER BY created_at DESC");
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>Nil d’Oro - Messages</title>
<link href="https://fonts.googleapis.com/css2?family=Great+Vibes&display=swap" rel="stylesheet">
<style>
body { margin:0; font-family:'Times New Roman', serif; background:#f9f5ec; color:#333; }
.header { background:#2e2e2e; padding:15px; display:flex; justify-content:space-between; align-items:center; }
.logo { font-family:'Great Vibes', cursive; font-size:48px; color:#f5e6cc; }
.nav a { color:#f5e6cc; margin:0 12px; text-decoration:none; font-size:18px; }
.nav a:hover { text-decoration:underline; }
.container { max-width:1000px; margin:40px auto; background:#fff; padding:20px; border-radius:12px; box-shadow:0 4px 12px rgba(0,0,0,0.1); }
.container h2 { font-family:'Great Vibes', cursive; font-size:42px; text-align:center; margin-bottom:20px; }
table { width:100%; border-collapse:collapse; }
th, td { padding:12px; border:1px solid #ddd; text-align:center; }
th { background:#f5e6cc; font-size:18px; }
tr:nth-child(even) { background:#fafafa; }
.footer { background:#2e2e2e; text-align:center; padding:15px; color:#f5e6cc; margin-top:40px; }
</style>
</head>
<body>
  <div class="header">
    <div class="logo">Nil d’Oro</div>
    <div class="nav">
      <a href="admin.php">Dashboard</a>
      <a href="products.php">Products</a>
      <a href="messages.php">Messages</a>
      <a href="logout.php">Logout</a>
    </div>
  </div>

  <div class="container">
    <h2>Client Messages</h2>
    <table>
      <tr>
        <th>Name</th>
        <th>Email</th>
        <th>WhatsApp</th>
        <th>Message</th>
        <th>Date</th>
      </tr>
      <?php while($row = $result->fetch_assoc()) { ?>
      <tr>
        <td><?= htmlspecialchars($row['name']) ?></td>
        <td><?= htmlspecialchars($row['email']) ?></td>
        <td><?= htmlspecialchars($row['whatsapp']) ?></td>
        <td><?= nl2br(htmlspecialchars($row['message'])) ?></td>
        <td><?= $row['created_at'] ?></td>
      </tr>
      <?php } ?>
    </table>
  </div>

  <div class="footer">
    <p>© <?= date('Y') ?> Nil d’Oro — Admin Panel</p>
  </div>
</body>
</html>
