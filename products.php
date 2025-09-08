<?php
session_start();
include("db.php");

// Initialize cart
if (!isset($_SESSION['cart'])) {
    $_SESSION['cart'] = [];
}

// Handle adding product to cart
if (isset($_POST['add_to_cart'])) {
    $id = intval($_POST['product_id']);
    $name = $_POST['product_name'];
    $price = floatval($_POST['product_price']);

    if (isset($_SESSION['cart'][$id])) {
        $_SESSION['cart'][$id]['quantity'] += 1;
    } else {
        $_SESSION['cart'][$id] = ['name'=>$name, 'price'=>$price, 'quantity'=>1];
    }
}

// Fetch products from DB
$search = $_GET['search'] ?? '';
$sql = "SELECT * FROM products WHERE name LIKE ?";
$stmt = $conn->prepare($sql);
$stmt->execute(["%$search%"]);
$products = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>Nil d’Oro - Products</title>
<link href="https://fonts.googleapis.com/css2?family=Great+Vibes&display=swap" rel="stylesheet">

<style>
body { font-family: 'Times New Roman', serif; background:#f9f5ec; margin:20px; color:#333; }
header { text-align:center; margin-bottom:30px; }
.logo { font-family:'Great Vibes', cursive; font-size:52px; color:#333; letter-spacing:1px; }
h1, h2, h3, p, li, label { font-family:'Great Vibes', cursive; }
.products { display:flex; flex-wrap:wrap; gap:15px; justify-content:center; }
.product { border:1px solid #ccc; padding:10px; width:200px; border-radius:8px; position:relative; cursor:pointer; background:#fffdf6; box-shadow:0 4px 8px rgba(0,0,0,0.1);}
.product h3 { margin:0 0 10px; font-size:16px; }
.product p { margin:0 0 10px; }
button { padding:6px 10px; border-radius:5px; cursor:pointer; border:none; background:#333; color:white; font-family:'Great Vibes', cursive; }
button:hover { background:#555; }

/* Popup styles */
.popup { position:fixed; top:0; left:0; width:100%; height:100%; background: rgba(0,0,0,0.5); display:none; justify-content:center; align-items:center; }
.popup-content { background:#fff; padding:20px; border-radius:10px; position:relative; max-width:300px; text-align:center; }
.popup-content .close { position:absolute; top:5px; right:10px; cursor:pointer; font-weight:bold; }
</style>
</head>
<body>

<header>
    <div class="logo">Nil d’Oro</div>
    <h1>Products 🛍️</h1>
</header>

<form method="get" style="text-align:center; margin-bottom:20px;">
    <input type="text" name="search" placeholder="Search products..." value="<?= htmlspecialchars($search) ?>" style="padding:6px; border-radius:5px; border:1px solid #ccc;">
    <button type="submit">Search</button>
</form>

<div class="products">
<?php foreach($products as $p): ?>
<div class="product" onclick="showPopup('<?= htmlspecialchars($p['name']) ?>')">
    <h3><?= htmlspecialchars($p['name']) ?></h3>
    <p>Price: <?= $p['price'] ?> EGP</p>
    <form method="post">
        <input type="hidden" name="product_id" value="<?= $p['id'] ?>">
        <input type="hidden" name="product_name" value="<?= htmlspecialchars($p['name']) ?>">
        <input type="hidden" name="product_price" value="<?= $p['price'] ?>">
        <button type="submit" name="add_to_cart">Add to Cart</button>
    </form>
</div>
<?php endforeach; ?>
</div>

<!-- Popup -->
<div class="popup" id="popup">
    <div class="popup-content">
        <span class="close" onclick="closePopup()">×</span>
        <p id="popupText"></p>
        <p>Contact us:</p>
        <p>WhatsApp: +201234567890</p>
        <p>Telegram: @YourTelegram</p>
        <p>Phone: +201234567890</p>
    </div>
</div>

<script>
function showPopup(name) {
    document.getElementById('popupText').innerText = name;
    document.getElementById('popup').style.display = 'flex';
}
function closePopup() {
    document.getElementById('popup').style.display = 'none';
}
document.getElementById('popup').addEventListener('click', function(e){
    if(e.target === this) closePopup();
});
</script>

</body>
</html>
