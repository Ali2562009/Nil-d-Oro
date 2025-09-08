<?php
session_start();
include 'db.php';

// Only logged-in admins can access
if (!isset($_SESSION['admin'])) {
    header("Location: admin_login.php");
    exit();
}

// Fetch all admins
$result = $conn->query("SELECT id, username FROM admins ORDER BY id ASC");
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Nil d’Oro - View Admins</title>
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

    table {
      margin: 30px auto;
      border-collapse: collapse;
      width: 60%;
      background: white;
      box-shadow: 0 2px 5px rgba(0,0,0,0.2);
    }

    table th, table td {
      border: 1px solid #ddd;
      padding: 12px;
    }

    table th {
      background: #444;
      color: white;
    }

    tr:nth-child(even) {
      background: #f2f2f2;
    }

    a {
      color: #2c2c2c;
      text-decoration: none;
      font-weight: bold;
    }

    a:hover {
      color: gold;
    }
  </style>
</head>
<body>
  <header>
    <h1>Nil d’Oro - Admins List</h1>
    <p>Welcome, <strong><?php echo $_SESSION['admin']; ?></strong></p>
  </header>

  <table>
    <tr>
      <th>ID</th>
      <th>Username</th>
    </tr>
    <?php while ($row = $result->fetch_assoc()): ?>
    <tr>
      <td><?php echo $row['id']; ?></td>
      <td><?php echo htmlspecialchars($row['username']); ?></td>
    </tr>
    <?php endwhile; ?>
  </table>

  <p><a href="admin.php">⬅ Back to Admin Panel</a></p>
</body>
</html>
