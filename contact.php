<?php session_start(); ?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Nil d’Oro | Contact</title>
  <link rel="stylesheet" href="style.css">
  <link href="https://fonts.googleapis.com/css2?family=Tangerine:wght@700&display=swap" rel="stylesheet">
  <style>
    body { font-family:Arial, sans-serif; }
    header { text-align:center; padding:20px; }
    h1 { font-family:'Tangerine', cursive; font-size:4rem; color:#2c2c2c; }
    nav a { margin:0 10px; text-decoration:none; color:#444; font-weight:bold; }
    .contact-container {
      max-width:600px; margin:50px auto; padding:20px;
      border:1px solid #ddd; border-radius:10px; background:#fdfdfd;
    }
    label { display:block; margin-top:15px; font-weight:bold; }
    input, textarea {
      width:100%; padding:10px; margin-top:5px;
      border:1px solid #ccc; border-radius:5px;
    }
    button {
      margin-top:20px; padding:12px 25px; border:none;
      background:#c19a6b; color:#fff; font-weight:bold;
      border-radius:25px; cursor:pointer; transition:0.3s;
    }
    button:hover { background:#a67842; }
    .social { margin-top:20px; text-align:center; }
    .social a { margin:0 10px; text-decoration:none; color:#c19a6b; font-weight:bold; }
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

  <div class="contact-container">
    <h2>Contact Us</h2>
    <form method="POST" action="send_message.php">
      <label for="name">Your Name</label>
      <input type="text" id="name" name="name" required>

      <label for="email">Your Email</label>
      <input type="email" id="email" name="email" required>

      <label for="whatsapp">WhatsApp Number</label>
      <input type="text" id="whatsapp" name="whatsapp" required>

      <label for="message">Message</label>
      <textarea id="message" name="message" rows="5" required></textarea>

      <button type="submit">Send Message</button>
    </form>

    <div class="social">
      <p>Or reach us directly:</p>
      <a href="mailto:support@nildoro.com">Email</a> |
      <a href="https://wa.me/201234567890" target="_blank">WhatsApp</a> |
      <a href="tel:+201234567890">Phone</a>
    </div>
  </div>
</body>
</html>
