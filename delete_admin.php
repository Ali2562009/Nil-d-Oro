<?php
session_start();
if (!isset($_SESSION['admin'])) {
    header("Location: admin_login.php");
    exit();
}

include 'db.php';
include 'log_action.php';

if (isset($_GET['id'])) {
    $id = $_GET['id'];

    // Get username before deleting
    $stmt = $conn->prepare("SELECT username FROM admins WHERE id=?");
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $stmt->bind_result($username);
    $stmt->fetch();
    $stmt->close();

    // Prevent deleting yourself
    if ($username === $_SESSION['admin']) {
        echo "❌ You cannot delete your own account!";
        exit();
    }

    // Delete admin
    $stmt = $conn->prepare("DELETE FROM admins WHERE id=?");
    $stmt->bind_param("i", $id);

    if ($stmt->execute()) {
        logAction($conn, $_SESSION['admin'], "Delete", $username);
        echo "✅ Admin deleted successfully.";
    } else {
        echo "❌ Error deleting admin.";
    }

    $stmt->close();
    header("Location: manage_admins.php");
    exit();
}
?>
