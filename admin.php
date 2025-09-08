<?php
session_start();
include("db.php");

// Only admin can access
if (!isset($_SESSION["user"]) || $_SESSION["role"] != "admin") {
    die("Access denied ❌");
}

// Handle Add Product
if (isset($_POST["add"])) {
    $name = trim($_POST["name"]);
    $desc = trim($_POST["description"]);
    $price = floatval($_POST["price"]);
    $image = trim($_POST["image"]);

    if ($name && $price && $image) {
        $stmt = $conn->prepare("INSERT INTO products (name, description, price, image) VALUES (?, ?, ?, ?)");
        $stmt->execute([$name, $desc, $price, $image]);
    }
}

// Handle Delete Product
if (isset($_GET["delete"])) {
    $id = intval($_GET["delete"]);
    $stmt = $conn->prepare("DELETE FROM products WHERE id=?");
    $stmt->execute([$id]);
}

// Handle Update Product
if (isset($_POST["update"])) {
    $id = intval($_POST["id"]);
    $name = trim($_POST["name"]);
    $desc = trim($_POST["description"]);
    $price = floatval($_POST["price"]);
    $image = trim($_POST["image"]);

    $stmt = $conn->prepare("UPDATE products SET name=?, description=?, price=?, image=? WHERE id=?");
    $stmt->execute([$name, $desc, $price, $image, $id]);
}

// Fetch all products
$products = $conn->query("SELECT * FROM products ORDER BY id DESC")->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Admin Panel</title>
  <link rel="stylesheet" href="style.css">
  <style>
    table { width: 100%; border-collapse: collapse; margin-top: 20px; }
    table, th, td { border: 1px solid #ccc; padding: 10px; text-align: center; }
    th { background: #f4f4f4; }
    input { width: 100%; padding: 5px; box-sizing: border-box; }
    .btn { padding: 6px 12px; border-radius: 4px; border: none; cursor: pointer; }
    .btn-delete { background: red; color: white; }
    .btn-update { background: orange; color: white; }
  </style>
</head>
<body>
  <h1>Admin Dashboard 🛠️</h1>

  <h2>Add Product</h2>
  <form method="post">
    <input type="text" name="name" placeholder="Product Name" required><br><br>
    <textarea name="description" placeholder="Product Description"></textarea><br><br>
    <input type="number" step="0.01" name="price" placeholder="Price" required><br><br>
    <input type="text" name="image" placeholder="Image URL (e.g. images/watch1.jpg)" required><br><br>
    <button type="submit" name="add">Add Product</button>
  </form>

  <h2>Manage Products</h2>
  <table>
    <tr>
      <th>ID</th>
      <th>Image</th>
      <th>Name</th>
      <th>Description</th>
      <th>Price</th>
      <th>Actions</th>
    </tr>
    <?php foreach ($products as $p): ?>
    <tr>
      <form method="post">
        <td><?= $p['id'] ?></td>
        <td><input type="text" name="image" value="<?= $p['image'] ?>"></td>
        <td><input type="text" name="name" value="<?= $p['name'] ?>"></td>
        <td><input type="text" name="description" value="<?= $p['description'] ?>"></td>
        <td><input type="number" step="0.01" name="price" value="<?= $p['price'] ?>"></td>
        <td>
          <input type="hidden" name="id" value="<?= $p['id'] ?>">
          <button type="submit" name="update" class="btn btn-update">Update</button>
          <a href="admin.php?delete=<?= $p['id'] ?>" class="btn btn-delete">Delete</a>
        </td>
      </form>
    </tr>
    <?php endforeach; ?>
  </table>
</body>
</html>
