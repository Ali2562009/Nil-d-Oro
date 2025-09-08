<?php
session_start();
include("db.php");

$cart = $_SESSION['cart'] ?? [];
$msg = "";

if (!$cart) {
    $msg = "Your cart is empty!";
}

if ($_SERVER["REQUEST_METHOD"] === "POST" && $cart) {
    $name = trim($_POST['name']);
    $email = trim($_POST['email']);
    $phone = trim($_POST['phone']);
    $whatsapp = trim($_POST['whatsapp']); // New field

    $total = 0;
    $details = [];

    foreach($cart as $id => $item) {
        $subtotal = $item['price'] * $item['quantity'];
        $total += $subtotal;
        $details[] = $item['name'] . " x" . $item['quantity'] . " = " . $subtotal . " EGP";
    }

    $orderDetails = implode("\n", $details);

    $stmt = $conn->prepare("INSERT INTO orders (customer_name, customer_email, customer_phone, whatsapp, total, order_details) VALUES (?, ?, ?, ?, ?, ?)");
    $stmt->execute([$name, $email, $phone, $whatsapp, $total, $orderDetails]);

    $_SESSION['cart'] = []; // Clear cart
    $msg = "✅ Your order has been placed successfully! Total: $total EGP";
}
?>

<form method="post">
    <input type="text" name="name" placeholder="Your Name" required>
    <input type="email" name="email" placeholder="Email" required>
    <input type="text" name="phone" placeholder="Phone Number" required>
    <input type="text" name="whatsapp" placeholder="WhatsApp Number" required> <!-- New -->
    ...
</form>
<?php
session_start();
include("db.php");

$cart = $_SESSION['cart'] ?? [];
$msg = "";

if (!$cart) {
    $msg = "Your cart is empty!";
}

if ($_SERVER["REQUEST_METHOD"] === "POST" && $cart) {
    $name = trim($_POST['name']);
    $email = trim($_POST['email']);
    $phone = trim($_POST['phone']);

    $total = 0;
    $details = [];

    foreach($cart as $id => $item) {
        $subtotal = $item['price'] * $item['quantity'];
        $total += $subtotal;
        $details[] = $item['name'] . " x" . $item['quantity'] . " = " . $subtotal . " EGP";
    }

    $orderDetails = implode("\n", $details);

    $stmt = $conn->prepare("INSERT INTO orders (customer_name, customer_email, customer_phone, total, order_details) VALUES (?, ?, ?, ?, ?)");
    $stmt->execute([$name, $email, $phone, $total, $orderDetails]);

    $_SESSION['cart'] = []; // Clear cart
    $msg = "✅ Your order has been placed successfully! Total: $total EGP";
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>Checkout</title>
<link rel="stylesheet" href="style.css">
<style>
body { font-family: Arial, sans-serif; margin: 20px; }
form { max-width: 400px; margin: auto; display: flex; flex-direction: column; gap: 10px; }
input, textarea { padding: 8px; width: 100%; box-sizing: border-box; }
button { padding: 10px; background: #333; color: white; border: none; cursor: pointer; border-radius: 5px; }
button:hover { background: #555; }
</style>
</head>
<body>
<h1>Checkout 🛒</h1>
<p style="color: green;"><?= $msg ?></p>

<?php if($cart): ?>
<form method="post">
    <input type="text" name="name" placeholder="Your Name" required>
    <input type="email" name="email" placeholder="Email" required>
    <input type="text" name="phone" placeholder="Phone Number" required>

    <h3>Order Summary:</h3>
    <ul>
        <?php $total = 0; ?>
        <?php foreach($cart as $item): ?>
            <li><?= $item['name'] ?> x <?= $item['quantity'] ?> = <?= $item['price'] * $item['quantity'] ?> EGP</li>
            <?php $total += $item['price'] * $item['quantity']; ?>
        <?php endforeach; ?>
    </ul>
    <p><strong>Total: <?= $total ?> EGP</strong></p>

    <button type="submit">Place Order</button>
</form>
<?php else: ?>
<p>Your cart is empty.</p>
<?php endif; ?>
</body>
</html>
