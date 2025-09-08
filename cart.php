<?php
session_start();

// Handle remove item
if (isset($_GET['remove'])) {
    $remove_id = $_GET['remove'];
    unset($_SESSION['cart'][$remove_id]);
}

// Handle update quantity
if (isset($_POST['update'])) {
    foreach ($_POST['quantities'] as $id => $qty) {
        if ($qty > 0) {
            $_SESSION['cart'][$id]['quantity'] = $qty;
        }
    }
}

$cart = isset($_SESSION['cart']) ? $_SESSION['cart'] : [];
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>Nil d’Oro - Cart</title>
<link href="https://fonts.googleapis.com/css2?family=Great+Vibes&display=swap" rel="stylesheet">
<style>
body { font-family:'Times New Roman', serif; background:#f9f5ec; margin:0; padding:0; color:#333; }
.header { background:#2e2e2e; padding:15px; text-align:center; }
.header h1 { font-family:'Great Vibes', cursive; font-size:48px; color:#f5e6cc; margin:0; }
.container { max-width:800px; margin:30px auto; background:#fff; padding:20px; border-radius:12px; box-shadow:0 4px 12px rgba(0,0,0,0.1); }
table { width:100%; border-collapse:collapse; margin-bottom:20px; }
th, td { padding:12px; text-align:center; border-bottom:1px solid #ddd; }
th { background:#f5e6cc; font-size:18px; }
td img { width:60px; }
button { background:#2e2e2e; color:#f5e6cc; border:none; padding:8px 14px; border-radius:6px; cursor:pointer; }
button:hover { background:#444; }
.total { font-size:20px; font-weight:bold; text-align:right; margin-top:20px; }
.checkout-btn { display:inline-block; margin-top:20px; background:#bfa76f; color:#fff; padding:12px 20px; font-size:18px; border-radius:8px; text-decoration:none; }
.checkout-btn:hover { background:#a18f59; }
</style>
</head>
<body>
<div class="header">
  <h1>Nil d’Oro</h1>
</div>
<div class="container">
  <h2>Your Cart</h2>
  <?php if (!empty($cart)) { ?>
  <form method="POST">
    <table>
      <tr>
        <th>Product</th>
        <th>Image</th>
        <th>Price</th>
        <th>Quantity</th>
        <th>Action</th>
      </tr>
      <?php 
      $total = 0;
      foreach ($cart as $id => $item) {
        $subtotal = $item['price'] * $item['quantity'];
        $total += $subtotal;
      ?>
      <tr>
        <td><?= htmlspecialchars($item['name']) ?></td>
        <td><img src="<?= htmlspecialchars($item['image']) ?>" alt=""></td>
        <td>$<?= number_format($item['price'], 2) ?></td>
        <td>
          <input type="number" name="quantities[<?= $id ?>]" value="<?= $item['quantity'] ?>" min="1">
        </td>
        <td>
          <a href="cart.php?remove=<?= $id ?>"><button type="button">Remove</button></a>
        </td>
      </tr>
      <?php } ?>
    </table>
    <button type="submit" name="update">Update Cart</button>
  </form>
  <div class="total">Total: $<?= number_format($total, 2) ?></div>
  <a href="checkout.php" class="checkout-btn">Proceed to Checkout</a>
  <?php } else { ?>
  <p>Your cart is empty.</p>
  <?php } ?>
</div>
</body>
</html>
