<?php
session_start();

// Destroy session
session_unset();
session_destroy();

// Delete cookie
setcookie("user_id", "", time() - 3600, "/");

// Redirect back to login
header("Location: login.php");
exit;
?>
