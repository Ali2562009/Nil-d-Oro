<?php
session_start();
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>Nil d’Oro - Home</title>
<link href="https://fonts.googleapis.com/css2?family=Great+Vibes&display=swap" rel="stylesheet">
<style>
body { margin:0; font-family:'Times New Roman', serif; background:#f9f5ec; color:#333; }
.header { background:#2e2e2e; padding:15px; display:flex; justify-content:space-between; align-items:center; }
.logo { font-family:'Great Vibes', cursive; font-size:48px; color:#f5e6cc; }
.nav a { color:#f5e6cc; margin:0 12px; text-decoration:none; font-size:18px; }
.nav a:hover { text-decoration:underline; }
.hero { background:url('images/hero-bg.jpg') center/cover no-repeat; height:80vh; display:flex; flex-direction:column; justify-content:center; align-items:center; text-align:center; color:#fff; }
.hero h1 { font-family:'Great Vibes', cursive; font-size:64px; margin:0; }
.hero p { font-size:22px; margin:15px 0; }
.hero a { background:#bfa76f; color:#fff; padding:14px 28px; border-radius:10px; font-size:20px; text-decoration:none; }
.hero a:hover { background:#a18f59; }
.container { max-width:1000px; margin:50px auto; padding:20px; text-align:center; }
.container h2 { font-family:'Great Vibes', cursive; font-size:42px; margin-bottom:20px; }
.container p { font-size:18px; line-height:1.6; }
.footer { background:#2e2e2e; text-align:center; padding:15px; color:#f5e6cc; margin-top:40px; }
</style>
</head>
<body>
  <div class="header">
    <div class="logo">Nil d’Oro</div>
    <div class="nav">
      <a href="index.php">Home</a>
      <a href="products.php">Products</a>
      <a href="cart.php">Cart</a>
      <?php if (isset($_SESSION['user'])): ?>
        <a href="logout.php">Logout</a>
      <?php else: ?>
        <a href="login.php">Login</a>
      <?php endif; ?>
    </div>
  </div>

  <div class="hero">
    <h1>Nil d’Oro</h1>
    <p>Timeless classics — from elegant watches to refined accessories</p>
    <a href="products.php">Shop Now</a>
  </div>

  <div class="container">
    <h2>Welcome to Nil d’Oro</h2>
    <p>
      Inspired by Egypt’s timeless elegance and the Victorian era’s artistry,  
      Nil d’Oro brings you a curated selection of accessories, watches,  
      and classic essentials that never go out of style.  
      Every piece reflects sophistication, heritage, and craftsmanship.
    </p>
  </div>

  <div class="footer">
    <p>© <?= date('Y') ?> Nil d’Oro — All Rights Reserved</p>
  </div>
</body>
</html>
