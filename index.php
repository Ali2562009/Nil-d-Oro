<?php session_start(); ?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Nil d’Oro | Home</title>
  <link rel="stylesheet" href="style.css">
  <link href="https://fonts.googleapis.com/css2?family=Tangerine:wght@700&display=swap" rel="stylesheet">
  <style>
    header { text-align:center; padding:20px; }
    h1 { font-family:'Tangerine', cursive; font-size:4rem; color:#2c2c2c; }
    nav a { margin:0 10px; text-decoration:none; color:#444; font-weight:bold; }
    .hero {
      text-align:center; padding:60px 20px;
      background: linear-gradient(to right, #f2e9e4, #fff);
    }
    .hero h2 { font-size:2.5rem; margin-bottom:10px; }
    .hero p { font-size:1.2rem; color:#555; }
    .cta-btn {
      display:inline-block; margin-top:20px; padding:12px 25px;
      background:#c19a6b; color:#fff; border-radius:30px;
      text-decoration:none; font-weight:bold; transition:0.3s;
    }
    .cta-btn:hover { background:#a67842; }
    footer { text-align:center; padding:20px; margin-top:40px; color:#777; font-size:0.9rem; }
  </style>
</head>
<body>
  <header>
    <h1>Nil d’Oro</h1>
    <nav>
      <a href="index.php">Home</a> |
      <a href="products.php">Products</a> |
      <a href="contact.php">Contact</a> |
      <a href="cart.php">🛒 Cart (<?php echo isset($_SESSION['cart']) ? count($_SESSION['cart']) : 0; ?>)</a>
    </nav>
  </header>

  <section class="hero">
    <h2>Welcome to Nil d’Oro</h2>
    <p>Classic accessories, timeless watches, and elegant school & office supplies.</p>
    <a href="products.php" class="cta-btn">Shop Now</a>
  </section>

  <footer>
    <p>© <?php echo date("Y"); ?> Nil d’Oro. All Rights Reserved.</p>
  </footer>
</body>
</html>
