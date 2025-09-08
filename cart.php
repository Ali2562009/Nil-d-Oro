<?php
session_start();

// Handle add to cart
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['product'])) {
    $product = $_POST['product'];
    $price = $_POST['price'];

    if (!isset($_SESSION['cart'])) {
        $_SESSION['cart'] = [];
    }

    if (isset($_SESSION['cart'][$product])) {
        $_SESSION['cart'][$product]['quantity'] += 1;
    } else {
        $_SESSION['cart'][$product] = [
            'price' => $price,
            'quantity' => 1
        ];
    }
}

// Handle remove item
if (isset($_GET['remove'])) {
    $removeItem = $_GET['remove'];
    unset($_SESSION['cart'][$removeItem]);
}

// Handle update quantities
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['update_cart'])) {
    foreach ($_POST['quantity'] as $item => $qty) {
        if ($qty <= 0) {
            unset($_SESSION['cart'][$item]);
        } else {
            $_SESSION['cart'][$item]['quantity'] = $qty;
        }
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>Nil d’Oro - Cart</title>
<link href="https://fonts.googleapis.com/css2?family=Great+Vibes&display=swap" rel="stylesheet">
<style>
body {
    font-family: "Great Vibes", cursive;
    background: #fdf6f0;
    color: #3a2e2e;
    text-align: center;
    padding: 20px;
}
table {
    margin: auto;
    border-collapse: collapse;
    width: 80%;
    background: #fff;
    border-radius: 10px;
    overflow: hidden;
    box-shadow: 0px 4px 8px rgba(0,0,0,0.2);
}
th, td {
    padding: 15px;
    border-bottom: 1px solid #ddd;
}
th {
    background: #3a2e2e;
    color: #fff;
}
.actions a {
    color: red;
    text-decoration: none;
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
.total {
    margin: 20px;
    font-size: 22px;
    font-family: serif;
}
</style>
</head>
<body>
    <h1>Nil d’Oro — Your Cart</h1>

    <?php if (!empty($_SESSION['cart'])): ?>
    <form method="POST">
        <table>
            <tr>
                <th>Product</th>
                <th>Price (EGP)</th>
                <th>Quantity</th>
                <th>Subtotal</th>
                <th>Action</th>
            </tr>
            <?php
            $total = 0;
            foreach ($_SESSION['cart'] as $item => $details):
                $subtotal = $details['price'] * $details['quantity'];
                $total += $subtotal;
            ?>
            <tr>
                <td><?= htmlspecialchars($item) ?></td>
                <td><?= number_format($details['price'], 2) ?></td>
                <td>
                    <input type="number" name="quantity[<?= htmlspecialchars($item) ?>]" value="<?= $details['quantity'] ?>" min="0">
                </td>
                <td><?= number_format($subtotal, 2) ?></td>
                <td class="actions"><a href="?remove=<?= urlencode($item) ?>">❌ Remove</a></td>
            </tr>
            <?php endforeach; ?>
        </table>
        <div class="total">Total: <strong><?= number_format($total, 2) ?> EGP</strong></div>
        <button type="submit" name="update_cart" class="btn">Update Cart</button>
    </form>
    <br>
    <a href="checkout.php"><button class="btn">Proceed to Checkout</button></a>
    <?php else: ?>
        <p>Your cart is empty. <a href="products.php">Go shopping</a></p>
    <?php endif; ?>
</body>
</html>
