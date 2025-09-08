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

    // Increment quantity if product already in cart
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
<title>Products</title>
<link rel="stylesheet" href="style.css">
<style>
body { font-family: Arial, sans-serif; margin: 20px; }
.products { display: flex; flex-wrap: wrap; gap: 15px; }
.product { border: 1px solid #ccc; padding: 10px; width: 200px; border-radius: 5px; position: relative; cursor: pointer; }
.product h3 { margin: 0 0 10px; font-size: 16px; }
.product p { margin: 0 0 10px; }
button { padding: 6px 10px; border-radius: 4px; cursor: pointer; border: none; background: #333; color: white; }
button:hover { background: #555; }

/* Popup styles */
.popup { position: fixed; top:0; left:0; width:100%; height:100%; background: rgba(0,0,0,0.5); display:none; justify-content:center; align-items:center; }
.popup-content { background: #fff; padding: 20px; border-radius: 5px; position: relative; max-width: 300px; text-align:center; }
.popup-content .close { position: absolute; top:5px; right:10px; cursor:pointer; font-weight:bold; }
</style>
</head>
<body>
<h1>Products 🛍️</h1>

<!-- Search form -->
<form method="get">
    <input type="text" name="search" placeholder="Search products..." value="<?= htmlspecialchars($search) ?>">
    <button type="submit">Search</button>
</form>

<div class="products">
<?php foreach($products as $p): ?>
<div class="product" onclick="showPopup('<?= htmlspecialchars($p['name']) ?>')">
    <h3><?= htmlspecialchars($p['name']) ?></h3>
    <p>Price: <?= $p['price'] ?> EGP</p>
    <form method="post" style="margin-top:5px;">
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
        <p>Contact us on:</p>
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

// Close popup when clicking outside
document.getElementById('popup').addEventListener('click', function(e){
    if(e.target === this) closePopup();
});
</script>

</body>
</html>
