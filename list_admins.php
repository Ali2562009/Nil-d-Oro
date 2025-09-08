<?php
session_start();
include 'db.php';

// Only logged-in admins can access
if (!isset($_SESSION['admin'])) {
    header("Location: admin_login.php");
    exit();
}

// Prevent deleting yourself
$current_admin = $_SESSION['admin'];

// Handle delete action
if (isset($_GET['delete'])) {
    $id = intval($_GET['delete']);
    $stmt = $conn->prepare("SELECT username FROM admins WHERE id = ?");
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $result = $stmt->get_result();
    $admin = $result->fetch_assoc();

    if ($admin && $admin['username'] !== $current_admin) {
        $del = $conn->prepare("DELETE FROM admins WHERE id = ?");
        $del->bind_param("i", $id);
        $del->execute();
        $message = "✅ Admin deleted successfully.";
    } else {
        $message = "❌ You cannot delete yourself!";
    }
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
      width: 70%;
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

    .message {
      margin: 20px;
      font-weight: bold;
      color: green;
    }
  </style>
</head>
<body>
  <header>
    <h1>Nil d’Oro - Admins List</h1>
    <p>Welcome, <strong><?php echo $current_admin; ?></strong></p>
  </header>

  <?php if (isset($message)) echo "<p class='message'>$message</p>"; ?>

  <table>
    <tr>
      <th>ID</th>
      <th>Username</th>
      <th>Action</th>
    </tr>
    <?php while ($row = $result->fetch_assoc()): ?>
    <tr>
      <td><?php echo $row['id']; ?></td>
      <td><?php echo htmlspecialchars($row['username']); ?></td>
      <td>
        <?php if ($row['username'] !== $current_admin): ?>
          <a href="list_admins.php?delete=<?php echo $row['id']; ?>" onclick="return confirm('Are you sure you want to delete this admin?');">❌ Delete</a>
        <?php else: ?>
          (You)
        <?php endif; ?>
      </td>
    </tr>
    <?php endwhile; ?>
  </table>

  <p><a href="admin.php">⬅ Back to Admin Panel</a></p>
</body>
</html>
