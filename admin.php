<?php
session_start();
if (!isset($_SESSION['admin'])) {
    header("Location: admin_login.php");
    exit();
}
?>
<?php if ($role === 'super') { ?>
   <li><a href="register_admin.php">Add New Admin</a></li>
<?php } ?>

<h1>Welcome, <?php echo $_SESSION['admin']; ?>
<?php
$stmt = $conn->prepare("SELECT role FROM admins WHERE username=?");
$stmt->bind_param("s", $_SESSION['admin']);
$stmt->execute();
$stmt->bind_result($role);
$stmt->fetch();
$stmt->close();
if ($role === 'super') { echo " <span style='color:gold;'>👑 Super Admin</span>"; }
?>
?>
<?php if ($role === 'super') { ?>
  <li><a href="logs.php">View Logs</a></li>
<?php } ?>
</h1>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Nil d’Oro - Admin Panel</title>
  <style>
    body {
      font-family: "Georgia", serif;
      background: #f9f6f1;
      color: #333;
      text-align: center;
      margin: 0;
      padding: 0;
    }

    header {
      background: #2c2c2c;
      color: gold;
      padding: 20px;
    }

    nav {
      background: #444;
      padding: 10px;
    }

    nav ul {
      list-style: none;
      margin: 0;
      padding: 0;
      display: flex;
      justify-content: center;
      gap: 20px;
    }

    nav ul li {
      display: inline;
    }

    nav ul li a {
      color: white;
      text-decoration: none;
      font-weight: bold;
      padding: 8px 12px;
      border-radius: 5px;
      transition: 0.3s;
    }

    nav ul li a:hover {
      background: gold;
      color: black;
    }

    .container {
      padding: 30px;
    }

    h1 {
      color: #2c2c2c;
    }

    footer {
      margin-top: 30px;
      background: #2c2c2c;
      color: white;
      padding: 10px;
    }
  </style>
</head>
<body>
  <header>
    <h1>Nil d’Oro Admin Panel</h1>
    <p>Welcome, <strong><?php echo $_SESSION['admin']; ?></strong></p>
  </header>

  <nav>
    <ul>
      <li><a href="view_orders.php">📦 View Orders</a></li>
      <li><a href="manage_products.php">🛍️ Manage Products</a></li>
      <li><a href="register_admin.php">👑 Register New Admin</a></li>
      <li><a href="list_admins.php">👥 View Admins</a></li>
      <li><a href="logout.php">🚪 Logout</a></li>
    </ul>
  </nav>

  <div class="container">
    <h2>📌 Quick Actions</h2>
    <p>Use the menu above to manage your store.</p>
  </div>

  <footer>
    <p>© 2025 Nil d’Oro | Admin Panel</p>
  </footer>
</body>
</html>
