<?php
session_start();
if (!isset($_SESSION['admin'])) {
    header("Location: admin_login.php");
    exit();
}

include 'db.php';
include 'log_action.php';

$id = $_GET['id'];
$result = $conn->query("SELECT * FROM admins WHERE id = $id");
$admin = $result->fetch_assoc();

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (isset($_POST['update_username'])) {
        $newUsername = $_POST['username'];
        $oldUsername = $admin['username'];

        $stmt = $conn->prepare("UPDATE admins SET username=? WHERE id=?");
        $stmt->bind_param("si", $newUsername, $id);

        if ($stmt->execute()) {
            logAction($conn, $_SESSION['admin'], "Edit Username", $oldUsername . " → " . $newUsername);
            echo "✅ Username updated.";
        }
        $stmt->close();
    }

    if (isset($_POST['reset_password'])) {
        $newPassword = password_hash($_POST['password'], PASSWORD_DEFAULT);

        $stmt = $conn->prepare("UPDATE admins SET password=? WHERE id=?");
        $stmt->bind_param("si", $newPassword, $id);

        if ($stmt->execute()) {
            logAction($conn, $_SESSION['admin'], "Reset Password", $admin['username']);
            echo "✅ Password reset.";
        }
        $stmt->close();
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Edit Admin - Nil d’Oro</title>
</head>
<body>
  <h1>✏️ Edit Admin</h1>

  <form method="POST">
    <label>New Username:</label><br>
    <input type="text" name="username" value="<?php echo $admin['username']; ?>" required><br><br>
    <button type="submit" name="update_username">Update Username</button>
  </form>
  <br>

  <form method="POST">
    <label>Reset Password:</label><br>
    <input type="password" name="password" required><br><br>
    <button type="submit" name="reset_password">Reset Password</button>
  </form>
</body>
</html>
