<?php
session_start();
if (!isset($_SESSION['admin'])) {
    header("Location: admin_login.php");
    exit();
}

include 'db.php';
<?php
session_start();
include 'db.php';

// Check if logged in
if (!isset($_SESSION['admin'])) {
    header("Location: login.php");
    exit();
}

// Fetch user role
$stmt = $conn->prepare("SELECT role FROM admins WHERE username=?");
$stmt->bind_param("s", $_SESSION['admin']);
$stmt->execute();
$stmt->bind_result($role);
$stmt->fetch();
$stmt->close();

// Only allow super admin
if ($role !== 'super') {
    echo "<h2 style='color:red;text-align:center;'>🚫 Access Denied. Only Super Admin can add new admins.</h2>";
    exit();
}
?>

include 'log_action.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = $_POST['username'];
    $password = password_hash($_POST['password'], PASSWORD_DEFAULT);

    $stmt = $conn->prepare("INSERT INTO admins (username, password) VALUES (?, ?)");
    $stmt->bind_param("ss", $username, $password);

    if ($stmt->execute()) {
        // Log action
        logAction($conn, $_SESSION['admin'], "Add", $username);
        echo "✅ Admin registered successfully.";
    } else {
        echo "❌ Error: " . $stmt->error;
    }

    $stmt->close();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Register Admin - Nil d’Oro</title>
</head>
<body>
  <h1>👑 Register New Admin</h1>
  <form method="POST">
    <label>Username:</label><br>
    <input type="text" name="username" required><br><br>

    <label>Password:</label><br>
    <input type="password" name="password" required><br><br>

    <button type="submit">Register</button>
  </form>
</body>
</html>
