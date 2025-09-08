<?php
session_start();
include("db.php");

// Simple admin access check
if (!isset($_SESSION['admin'])) {
    $_SESSION['admin'] = true; // For now no login, always admin
}

// Add new product
if (isset($_POST['add_product'])) {
    $name = $_POST['name'];
    $price = $_POST['price'];

    $stmt = $conn->prepare("INSERT INTO products (name, price) VALUES (?, ?)");
    $stmt->execute([$name, $price]);
}

// Delete product
if (isset($_GET['delete'])) {
    $id = intval($_GET['delete']);
    $stmt = $conn->prepare("DELETE FROM products WHERE id=?");
    $stmt->execute([$id]);
}

// Fetch products
$stmt = $conn->prepare("SELECT * FROM products");
$stmt->execute();
$products = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Fetch clients
$stmt = $conn->prepare("SELECT email, whatsapp FROM users");
$stmt->execute();
$clients = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>Nil d’Oro - Admin Panel</title>
<link href="https://fonts.googleapis.com/css2?family=Great+Vibes&display=swap" rel="stylesheet">
<style>
body { font-family: 'Times New Roman', serif; background:#f9f5ec; margin:20px; color:#333; }
header { text-align:center; margin-bottom:30px; }
.logo { font-family:'Great Vibes', cursive; font-size:52px; color:#333; letter-spacing:1px; }
h2 { text-align:center; margin-top:40px; }
form { text-align:center; margin:20px; }
input[type="text"], input[type="number"] {
  padding:6px; border-radius:5px; border:1px solid #ccc; margin:5px;
}
button { padding:6px 12px; border-radius:8px; background:#333; color:white; border:none; cursor:pointer; font-family:'Great Vibes', cursive; }
button:hover { background:#555; }
table { width:80%; margin:auto; border-collapse:collapse; background:#fffdf6; box-shadow:0 4px 10px rgba(0,0,0,0.1); border-radius:10px; overflow:hidden; margin-top:20px; }
th, td { padding:12px; border:1px solid #ccc; text-align:center; }
th { background:#f2e9dc; }
a.delete { color:red; text-decoration:none; }
</style>
</head>
<body>
<header>
  <div class="logo">Nil d’Oro</div>
  <h1>Admin Panel ⚙️</h1>
</header>

<h2>Add New Product</h2>
<form method="post">
  <input type="text" name="name" placeholder="Product Name" required>
  <input type="number" step="0.01" name="price" placeholder="Price (EGP)" required>
  <button type="submit" name="add_product">Add Product</button>
</form>

<h2>Products List</h2>
<table>
<tr>
  <th>ID</th>
  <th>Name</th>
  <th>Price (EGP)</th>
  <th>Action</th>
</tr>
<?php foreach($products as $p): ?>
<tr>
  <td><?= $p['id'] ?></td>
  <td><?= htmlspecialchars($p['name']) ?></td>
  <td><?= $p['price'] ?></td>
  <td><a href="admin.php?delete=<?= $p['id'] ?>" class="delete">Delete</a></td>
</tr>
<?php endforeach; ?>
</table>

<h2>Clients List</h2>
<table>
<tr>
  <th>Email</th>
  <th>WhatsApp</th>
</tr>
<?php foreach($clients as $c): ?>
<tr>
  <td><?= htmlspecialchars($c['email']) ?></td>
  <td><?= htmlspecialchars($c['whatsapp']) ?></td>
</tr>
<?php endforeach; ?>
</table>
</body>
</html>
