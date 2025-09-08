<?php
session_start();
include 'db.php';

// Only logged-in admins can access
if (!isset($_SESSION['admin'])) {
    header("Location: admin_login.php");
    exit();
}

$current_admin = $_SESSION['admin'];
$message = "";

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

// Handle password reset
if (isset($_POST['reset_id']) && !empty($_POST['new_password'])) {
    $id = intval($_POST['reset_id']);
    $new_password = password_hash($_POST['new_password'], PASSWORD_DEFAULT);

    $stmt = $conn->prepare("UPDATE admins SET password = ? WHERE id = ?");
    $stmt->bind_param("si", $new_password, $id);

    if ($stmt->execute()) {
        $message = "✅ Password updated successfully.";
    } else {
        $message = "❌ Error updating password.";
    }
}

// Handle username edit
if (isset($_POST['edit_id']) && !empty($_POST['new_username'])) {
    $id = intval($_POST['edit_id']);
    $new_username = trim($_POST['new_username']);

    // Prevent duplicate usernames
    $check = $conn->prepare("SELECT id FROM admins WHERE username = ? AND id != ?");
    $check->bind_param("si", $new_username, $id);
    $check->execute();
    $check_result = $check->get_result();

    if ($check_result->num_rows > 0) {
        $message = "❌ Username already taken.";
    } else {
        $stmt = $conn->prepare("UPDATE admins SET username = ? WHERE id = ?");
        $stmt->bind_param("si", $new_username, $id);

        if ($stmt->execute()) {
            // Update session if the current admin changed their own username
            if ($id == $conn->insert_id || $new_username == $current_admin) {
                $_SESSION['admin'] = $new_username;
            }
            $message = "✅ Username updated successfully.";
        } else {
            $message = "❌ Error updating username.";
        }
    }
}

// Fetch all admins
$result = $conn->query("SELECT id, username FROM admins ORDER BY id ASC");
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Nil d’Oro - Manage Admins</title>
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
      width: 90%;
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
      color: #c00;
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

    input[type="password"], input[type="text"] {
      padding: 5px;
      width: 150px;
    }

    button {
      padding: 5px 10px;
      border: none;
      background: #2c2c2c;
      color: white;
      border-radius: 4px;
      cursor: pointer;
    }

    button:hover {
      background: gold;
      color: black;
    }
  </style>
</head>
<body>
  <header>
    <h1>Nil d’Oro - Manage Admins</h1>
    <p>Welcome, <strong><?php echo $current_admin; ?></strong></p>
  </header>

  <?php if ($message) echo "<p class='message'>$message</p>"; ?>

  <table>
    <tr>
      <th>ID</th>
      <th>Username</th>
      <th>Edit Username</th>
      <th>Reset Password</th>
      <th>Delete</th>
    </tr>
    <?php while ($row = $result->fetch_assoc()): ?>
    <tr>
      <td><?php echo $row['id']; ?></td>
      <td><?php echo htmlspecialchars($row['username']); ?></td>
      <td>
        <form method="POST" style="display:inline;">
          <input type="hidden" name="edit_id" value="<?php echo $row['id']; ?>">
          <input type="text" name="new_username" placeholder="New Username" required>
          <button type="submit">Update</button>
        </form>
      </td>
      <td>
        <form method="POST" style="display:inline;">
          <input type="hidden" name="reset_id" value="<?php echo $row['id']; ?>">
          <input type="password" name="new_password" placeholder="New Password" required>
          <button type="submit">Update</button>
        </form>
      </td>
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
