<?php
session_start();
if (!isset($_SESSION['admin'])) {
    header("Location: admin_login.php");
    exit();
}
if ($row['role'] === 'super') {
    echo "<span style='color:gold; font-weight:bold;'>👑 Super Admin</span>";
} else {
    echo "<a href='edit_admin.php?id={$row['id']}' class='btn edit'>✏️ Edit</a>";
    if ($row['username'] !== $_SESSION['admin']) {
        echo "<a href='delete_admin.php?id={$row['id']}' class='btn delete' onclick=\"return confirm('Are you sure?');\">🗑️ Delete</a>";
    }
}

include 'db.php';
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Manage Admins - Nil d’Oro</title>
  <style>
    body { font-family: Arial, sans-serif; margin: 20px; }
    h1 { color: #3a2e2e; }
    table { border-collapse: collapse; width: 100%; margin-top: 20px; }
    th, td { border: 1px solid #ddd; padding: 10px; text-align: left; }
    th { background-color: #3a2e2e; color: white; }
    a.btn {
        display: inline-block;
        padding: 6px 12px;
        border-radius: 5px;
        text-decoration: none;
        color: white;
        margin-right: 5px;
    }
    a.edit { background-color: #007bff; }
    a.delete { background-color: #dc3545; }
    a.logs { background-color: #28a745; }
  </style>
</head>
<body>
  <h1>👑 Manage Admins</h1>
  <a href="register_admin.php" class="btn edit">➕ Register New Admin</a>
  <a href="logs.php" class="btn logs">📜 View Logs</a>
  <br><br>

  <table>
    <tr>
      <th>ID</th>
      <th>Username</th>
      <th>Actions</th>
    </tr>
    <?php
    $result = $conn->query("SELECT * FROM admins");
    while ($row = $result->fetch_assoc()) {
        echo "<tr>
                <td>{$row['id']}</td>
                <td>{$row['username']}</td>
                <td>
                  <a href='edit_admin.php?id={$row['id']}' class='btn edit'>✏️ Edit</a>";
        
        // Prevent deleting self
        if ($row['username'] !== $_SESSION['admin']) {
            echo "<a href='delete_admin.php?id={$row['id']}' class='btn delete' onclick=\"return confirm('Are you sure you want to delete this admin?');\">🗑️ Delete</a>";
        } else {
            echo "<span style='color: gray;'>❌ Cannot delete yourself</span>";
        }

        echo "</td>
              </tr>";
    }
    ?>
  </table>
</body>
</html>
