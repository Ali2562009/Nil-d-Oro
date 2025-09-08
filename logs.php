<?php
session_start();
if (!isset($_SESSION['admin'])) {
    header("Location: admin_login.php");
    exit();
}
include 'db.php';
?>
<td>
  <?php 
    echo $row['actor']; 
    $check = $conn->prepare("SELECT role FROM admins WHERE username=?");
    $check->bind_param("s", $row['actor']);
    $check->execute();
    $check->bind_result($role);
    $check->fetch();
    $check->close();
    if ($role === 'super') { echo " <span style='color:gold;'>👑</span>"; }
  ?>
</td>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Admin Logs - Nil d’Oro</title>
  <style>
    body { font-family: Arial, sans-serif; margin: 20px; }
    table { border-collapse: collapse; width: 100%; }
    th, td { padding: 10px; border: 1px solid #ddd; text-align: left; }
    th { background: #3a2e2e; color: #fff; }
  </style>
</head>
<body>
  <h1>📜 Admin Activity Log</h1>
  <table>
    <tr>
      <th>ID</th>
      <th>Actor</th>
      <th>Action</th>
      <th>Target</th>
      <th>Timestamp</th>
    </tr>
    <?php
    $result = $conn->query("SELECT * FROM admin_logs ORDER BY timestamp DESC");
    while ($row = $result->fetch_assoc()) {
        echo "<tr>
                <td>{$row['id']}</td>
                <td>{$row['actor']}</td>
                <td>{$row['action']}</td>
                <td>{$row['target']}</td>
                <td>{$row['timestamp']}</td>
              </tr>";
    }
    ?>
  </table>
</body>
</html>
