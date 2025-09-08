<?php
session_start();
include 'db.php';

// If cart is empty
if (!isset($_SESSION['cart']) || empty($_SESSION['cart'])) {
    die("<p>Your cart is empty. <a href='products.php'>Go back to products</a></p>");
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = trim($_POST['name']);
    $email = trim($_POST['email']);
    $whatsapp = trim($_POST['whatsapp']);
    $address = trim($_POST['address']);
    $cart = $_SESSION['cart'];

    // Calculate total
    $total = 0;
    foreach ($cart as $item) {
        $total += $item['price'] * $item['quantity'];
    }

    // Save order to database
    $stmt = $conn->prepare("INSERT INTO orders (name, email, whatsapp, address, order_data, total) VALUES (?, ?, ?, ?, ?, ?)");
    $order_data = json_encode($cart);
    $stmt->bind_param("sssssd", $name, $email, $whatsapp, $address, $order_data, $total);

    if ($stmt->execute()) {
        $_SESSION['cart'] = []; // clear cart
        echo "<div class='success'>
                <h2>✅ Thank you, $name!</h2>
                <p>Your order has been placed successfully.</p>
                <p>We’ll contact you shortly on WhatsApp: <strong>$whatsapp</strong></p>
                <a href='products.php'>Continue Shopping</a>
              </div>";
        exit;
    } else {
        echo "<p>❌ Something went wrong. Please try again later.</p>";
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Nil d’Oro - Checkout</title>
  <link href="https://fonts.googleapis.com/css2?family=Great+Vibes&display=swap" rel="stylesheet">
  <style>
    body {
      font-family: "Great Vibes", cursive;
      background: #fdf6f0;
      color: #3a2e2e;
      text-align: center;
      padding: 20px;
    }
    h1 {
      font-size: 48px;
      margin-bottom: 20px;
    }
    form {
      width: 50%;
      margin: auto;
      background: #fff;
      padding: 20px;
      border-radius: 10px;
      box-shadow: 0px 4px 8px rgba(0,0,0,0.2);
    }
    input, textarea {
      width: 90%;
      padding: 10px;
      margin: 10px 0;
      border: 1px solid #ccc;
      border-radius: 8px;
      font-family: serif;
    }
    .btn {
      padding: 10px 20px;
      border: none;
      border-radius: 8px;
      background: #3a2e2e;
      color: #fff;
      cursor: pointer;
      font-size: 18px;
    }
    .btn:hover {
      background: #5c4747;
    }
    .success {
      font-family: serif;
      background: #fff;
      padding: 20px;
      margin: 50px auto;
      border-radius: 10px;
      width: 50%;
      box-shadow: 0px 4px 8px rgba(0,0,0,0.3);
    }
  </style>
</head>
<body>
  <h1>Nil d’Oro — Checkout</h1>
  <form method="POST">
    <input type="text" name="name" placeholder="Full Name" required><br>
    <input type="email" name="email" placeholder="Email" required><br>
    <input type="text" name="whatsapp" placeholder="WhatsApp Number" required><br>
    <textarea name="address" placeholder="Delivery Address" required></textarea><br>
    <button type="submit" class="btn">Place Order</button>
  </form>
</body>
</html>
