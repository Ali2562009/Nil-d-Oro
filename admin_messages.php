<?php
session_start();
include 'db.php';

// ✅ Only admins can access
if (!isset($_SESSION['admin'])) {
    header("Location: admin_login.php");
    exit();
}

// ✅ Handle Delete
if (isset($_GET['delete'])) {
    $id = (int)$_GET['delete'];
    $stmt = $conn->prepare("DELETE FROM messages WHERE id=?");
    $stmt->bind_param("i", $id);
    $stmt->execute();
    header("Location: admin_messages.php");
    exit();
}

$result = $conn->query("SELECT * FROM messages ORDER BY created_at DESC");
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Admin | Messages</title>
  <style>
    body { font-family:Arial, sans-serif; margin:20px; }
    h1 { text-align:center; }
    table { width:100%; border-collapse:collapse; margin-top:20px; }
    th, td { border:1px solid #ddd; padding:10px; text-align:left; }
    th { background:#f4f4f4; }
    tr:nth-child(even) { background:#fafafa; }
    .whatsapp-link { color:green; font-weight:bold; }
    .email-link { color:blue; font-weight:bold; }
    .delete-btn {
      color:red; font-weight:bold; text-decoration:none;
    }
    .delete-btn:hover { text-decoration:underline; }
  </style>
</head>
<body>
  <h1>📨 Client Messages</h1>
  <table>
    <tr>
      <th>ID</th>
      <th>Name</th>
      <th>Email</th>
      <th>WhatsApp</th>
      <th>Message</th>
      <th>Received At</th>
      <th>Action</th>
    </tr>
    <?php while ($row = $result->fetch_assoc()) { ?>
      <tr>
        <td><?php echo $row['id']; ?></td>
        <td><?php echo htmlspecialchars($row['name']); ?></td>
        <td><a class="email-link" href="mailto:<?php echo $row['email']; ?>"><?php echo $row['email']; ?></a></td>
        <td><a class="whatsapp-link" href="https://wa.me/<?php echo $row['whatsapp']; ?>" target="_blank"><?php echo $row['whatsapp']; ?></a></td>
        <td><?php echo nl2br(htmlspecialchars($row['message'])); ?></td>
        <td><?php echo $row['created_at']; ?></td>
        <td><a class="delete-btn" href="admin_messages.php?delete=<?php echo $row['id']; ?>" onclick="return confirm('Are you sure you want to delete this message?');">Delete</a></td>
      </tr>
    <?php } ?>
  </table>
</body>
</html>
