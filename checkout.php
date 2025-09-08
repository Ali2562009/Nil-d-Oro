<?php
session_start();
include("db.php"); // Your database connection

$cart = $_SESSION['cart'] ?? [];
$msg = "";

if (!$cart) { $msg = "Your cart is empty!"; }

if ($_SERVER["REQUEST_METHOD"] === "POST" && $cart) {
    $name = trim($_POST['name']);
    $email = trim($_POST['email']);
    $phone = trim($_POST['phone']);
    $whatsapp = trim($_POST['whatsapp']);

    $total = 0;
    $details = [];

    foreach($cart as $id => $item) {
        $subtotal = $item['price'] * $item['quantity'];
        $total += $subtotal;
        $details[] = $item['name'] . " x" . $item['quantity'] . " = " . $subtotal . " EGP";
    }

    $orderDetails = implode("\n", $details);

    // Insert order into database
    $stmt = $conn->prepare("INSERT INTO orders (customer_name, customer_email, customer_phone, whatsapp, total, order_details) VALUES (?, ?, ?, ?, ?, ?)");
    $stmt->execute([$name, $email, $phone, $whatsapp, $total, $orderDetails]);

    $_SESSION['cart'] = [];
    $msg = "✅ Your order has been placed successfully! Total: $total EGP";
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>Nil d’Oro - Checkout</title>

<!-- Victorian Handwriting Font -->
<link href="https://fonts.googleapis.com/css2?family=Great+Vibes&display=swap" rel="stylesheet">

<style>
body { 
    font-family: 'Times New Roman', serif; 
    background-color: #f9f5ec; 
    margin: 20px; 
    color: #333;
}
header { text-align: center; margin-bottom: 30px; }
.logo { 
    font-family: 'Great Vibes', cursive; 
    font-size: 52px; 
    color: #333; 
    letter-spacing: 1px;
}
h1, h3, p, li, label { font-family: 'Great Vibes', cursive; }
form { 
    max-width: 450px; 
    margin: auto; 
    display: flex; 
    flex-direction: column; 
    gap: 12px; 
    background: #fffdf6;
    padding: 20px; 
    border-radius: 10px; 
    box-shadow: 0 4px 10px rgba(0,0,0,0.1);
}
input, textarea { 
    padding: 10px; 
    width: 100%; 
    box-sizing: border-box; 
    border: 1px solid #ccc; 
    border-radius: 5px; 
    font-family: 'Times New Roman', serif;
}
button { 
    padding: 12px; 
    background: #333; 
    color: white; 
    border: none; 
    cursor: pointer; 
    border-radius: 8px; 
    font-family: 'Great Vibes', cursive;
}
button:hover { background: #555; }
ul { 
    padding-left: 20px; 
    background: #fff8e0; 
    border-radius: 5px; 
    padding: 10px; 
}
p.msg { color: green; font-weight: bold; text-align: center; }
</style>
</head>
<body>

<header>
    <div class="logo">Nil d’Oro</div>
    <h1>Checkout 🛒</h1>
</header>

<p class="msg"><?= $msg ?></p>

<?php if($cart): ?>
<form method="post">
    <label>Your Name</label>
    <input type="text" name="name" placeholder="Your Name" required>
    
    <label>Email</label>
    <input type="email" name="email" placeholder="Email" required>
    
    <label>Phone Number</label>
    <input type="text" name="phone" placeholder="Phone Number" required>
    
    <label>WhatsApp Number</label>
    <input type="text" name="whatsapp" placeholder="WhatsApp Number" required>

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
<p style="text-align:center;">Your cart is empty.</p>
<?php endif; ?>

</body>
</html>
