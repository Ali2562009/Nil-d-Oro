<?php
session_start();
$cart = $_SESSION['cart'] ?? [];

// Handle Remove from Cart
if(isset($_GET['remove'])) {
    $id = intval($_GET['remove']);
    unset($_SESSION['cart'][$id]);
    header("Location: cart.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>Shopping Cart</title>
<link rel="stylesheet" href="style.css">
<style>
table { width: 100%; border-collapse: collapse; margin-top: 20px; }
table, th, td { border: 1px solid #ccc; padding: 10px; text-align: center; }
th { background: #f4f4f4; }
img { width: 80px; height: auto; }
.btn { padding: 6px 12px; border-radius: 4px; cursor: pointer; }
.btn-remove { background: red; color: white; border: none; }
</style>
</head>
<body>
<h1>Your Cart 🛒</h1>

<?php if($cart): ?>
<table>
<tr>
    <th>Image</th>
    <th>Name</th>
    <th>Price</th>
    <th>Quantity</th>
    <th>Subtotal</th>
    <th>Action</th>
</tr>
<?php $total = 0; ?>
<?php foreach($cart as $id => $item): ?>
<tr>
    <td><img src="<?= $item['image'] ?>" alt="<?= $item['name'] ?>"></td>
    <td><?= $item['name'] ?></td>
    <td><?= $item['price'] ?> EGP</td>
    <td><?= $item['quantity'] ?></td>
    <td><?= $item['price'] * $item['quantity'] ?> EGP</td>
    <td>
        <a href="cart.php?remove=<?= $id ?>" class="btn btn-remove">Remove</a>
    </td>
</tr>
<?php $total += $item['price'] * $item['quantity']; ?>
<?php endforeach; ?>
<tr>
    <td colspan="4"><strong>Total</strong></td>
    <td colspan="2"><strong><?= $total ?> EGP</strong></td>
</tr>
</table>

<a href="checkout.php">Proceed to Checkout</a>

<?php else: ?>
<p>Your cart is empty.</p>
<?php endif; ?>
</body>
</html>
