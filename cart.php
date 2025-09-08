<?php
session_start();
include("db.php");

$cart = $_SESSION['cart'] ?? [];

if(isset($_POST['update_quantity'])){
    foreach($_POST['quantities'] as $id => $qty){
        if($qty <= 0){
            unset($_SESSION['cart'][$id]);
        } else {
            $_SESSION['cart'][$id]['quantity'] = intval($qty);
        }
    }
    header("Location: cart.php");
    exit();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>Nil d’Oro - Cart</title>
<link href="https://fonts.googleapis.com/css2?family=Great+Vibes&display=swap" rel="stylesheet">
<style>
body { font-family: 'Times New Roman', serif; background:#f9f5ec; margin:20px; color:#333; }
header { text-align:center; margin-bottom:30px; }
.logo { font-family:'Great Vibes', cursive; font-size:52px; color:#333; letter-spacing:1px; }
table { width:80%; margin:auto; border-collapse:collapse; background:#fffdf6; box-shadow:0 4px 10px rgba(0,0,0,0.1); border-radius:10px; overflow:hidden; }
th, td { padding:12px; border:1px solid #ccc; text-align:center; }
th { background:#f2e9dc; }
input[type="number"] { width:60px; padding:6px; border-radius:5px; border:1px solid #ccc; }
button { padding:8px 14px; background:#333; color:white; border:none; cursor:pointer; border-radius:8px; font-family:'Great Vibes', cursive; }
button:hover { background:#555; }
.actions { text-align:center; margin-top:20px; }
</style>
</head>
<body>
<header>
  <div class="logo">Nil d’Oro</div>
  <h1>Your Cart 🛒</h1>
</header>

<?php if($cart): ?>
<form method="post">
  <table>
    <tr>
      <th>Product</th>
      <th>Price (EGP)</th>
      <th>Quantity</th>
      <th>Subtotal</th>
    </tr>
    <?php $total=0; foreach($cart as $id => $item): 
        $subtotal = $item['price'] * $item['quantity'];
        $total += $subtotal;
    ?>
    <tr>
      <td><?= htmlspecialchars($item['name']) ?></td>
      <td><?= $item['price'] ?></td>
      <td><input type="number" name="quantities[<?= $id ?>]" value="<?= $item['quantity'] ?>"></td>
      <td><?= $subtotal ?></td>
    </tr>
    <?php endforeach; ?>
    <tr>
      <td colspan="3"><strong>Total</strong></td>
      <td><strong><?= $total ?> EGP</strong></td>
    </tr>
  </table>
  <div class="actions">
    <button type="submit" name="update_quantity">Update Cart</button>
    <a href="checkout.php"><button type="button">Proceed to Checkout</button></a>
  </div>
</form>
<?php else: ?>
  <p style="text-align:center;">Your cart is empty.</p>
<?php endif; ?>
</body>
</html>
