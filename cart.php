<?php
session_start();

// If cart is empty, create it
if (!isset($_SESSION['cart'])) {
    $_SESSION['cart'] = [];
}

// Remove item from cart
if (isset($_GET['remove'])) {
    $id = $_GET['remove'];
    unset($_SESSION['cart'][$id]);
}

// Update quantities
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['update_cart'])) {
    foreach ($_POST['quantity'] as $id => $qty) {
        if ($qty > 0) {
            $_SESSION['cart'][$id]['quantity'] = $qty;
        } else {
            unset($_SESSION['cart'][$id]);
        }
    }
}

// Get cart items
$cart = $_SESSION['cart'];
$total = 0;
foreach ($cart as $item) {
    $total += $item['price'] * $item['quantity'];
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Nil d’Oro - Cart</title>
  <style>
    body {
      font-family: "Great Vibes", cursive;
      background: #fdf6f0;
      color: #3a2e2e;
      text-align: center;
      padding: 20px;
    }
    table {
      width: 80%;
      margin: auto;
      border-collapse: collapse;
      margin-top: 20px;
    }
    th, td {
      border: 1px solid #ccc;
      padding: 10px;
    }
    th {
      background: #e4d3c3;
    }
    .btn {
      padding: 10px 20px;
      margin: 10px;
      border: none;
      border-radius: 8px;
      background: #3a2e2e;
      color: #fff;
      cursor: pointer;
    }
    .btn:hover {
      background: #5c4747;
    }
  </style>
  <link href="https://fonts.googleapis.com/css2?family=Great+Vibes&display=swap" rel="stylesheet">
</head>
<body>
  <h1>Nil d’Oro — Cart</h1>
  <?php if (empty($cart)): ?>
    <p>Your cart is empty.</p>
  <?php else: ?>
    <form method="POST">
      <table>
        <tr>
          <th>Product</th>
          <th>Price</th>
          <th>Quantity</th>
          <th>Subtotal</th>
          <th>Action</th>
        </tr>
        <?php foreach ($cart as $id => $item): ?>
          <tr>
            <td><?= htmlspecialchars($item['name']) ?></td>
            <td>$<?= number_format($item['price'], 2) ?></td>
            <td>
              <input type="number" name="quantity[<?= $id ?>]" value="<?= $item['quantity'] ?>" min="1">
            </td>
            <td>$<?= number_format($item['price'] * $item['quantity'], 2) ?></td>
            <td><a href="?remove=<?= $id ?>" class="btn">Remove</a></td>
          </tr>
        <?php endforeach; ?>
      </table>
      <p><strong>Total: $<?= number_format($total, 2) ?></strong></p>
      <button type="submit" name="update_cart" class="btn">Update Cart</button>
    </form>
    <br>
    <a href="checkout.php" class="btn">Proceed to Checkout</a>
  <?php endif; ?>
</body>
</html>
