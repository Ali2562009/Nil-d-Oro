<?php
session_start();
session_unset();
session_destroy();
header("refresh:2; url=login.php");
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>Nil d’Oro - Logout</title>
<link href="https://fonts.googleapis.com/css2?family=Great+Vibes&display=swap" rel="stylesheet">
<style>
body { font-family:'Times New Roman', serif; background:#f9f5ec; text-align:center; margin:50px; color:#333; }
.logo { font-family:'Great Vibes', cursive; font-size:52px; color:#333; margin-bottom:20px; }
.message { font-size:22px; font-family:'Great Vibes', cursive; }
</style>
</head>
<body>
  <div class="logo">Nil d’Oro</div>
  <p class="message">👋 You have been logged out. Redirecting to login...</p>
</body>
</html>
