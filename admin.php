<?php
session_start();
include "db.php";

// Check if user is logged in
if (!isset($_SESSION["user_id"])) {
    header("Location: login.php");
    exit;
}

// Check if user is admin
$sql = "SELECT role FROM users WHERE id=?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $_SESSION["user_id"]);
$stmt->execute();
$result = $stmt->get_result();
$user = $result->fetch_assoc();

if ($user["role"] != "admin") {
    die("Access denied ❌ You are not an admin.");
}

// Handle Add Product
if (isset($_POST["add"])) {
    $name = $_POST["name"];
    $desc = $_POST["description"];
    $price = $_POST["price"];

    $sql = "INSERT INTO products (name, description, price) VALUES (?, ?, ?)";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ssd", $name, $desc, $price);
    $stmt->execute();
}

// Handle Delete Product
if (isset($_GET["delete"])) {
    $id = $_GET["delete"];
    $sql = "DELETE FROM products WHERE id=?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $id);
    $stmt->execute();
}

// Fetch Products
$result = $conn->query("SELECT * FROM products");
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
    .btn { padding: 6px 10px; text-decoration: none; border-radius: 4px; }
    .btn-danger { background: red; color: white; }
  </style>
</head>
<body>
  <div class="form-container">
    <h2>Admin Dashboard 🛠️</h2>

    <!-- Add Product Form -->
    <form method="post">
      <input type="text" name="name" placeholder="Product Name" required>
      <input type="text" name="description" placeholder="Description">
      <input type="number" step="0.01" name="price" placeholder="Price (EGP)" required>
      <button type="submit" name="add">Add Product</button>
    </form>

    <!-- Product List -->
    <table>
      <tr>
        <th>ID</th>
        <th>Name</th>
        <th>Description</th>
        <th>Price (EGP)</th>
        <th>Actions</th>
      </tr>
      <?php while ($row = $result->fetch_assoc()): ?>
      <tr>
        <td><?= $row["id"] ?></td>
        <td><?= $row["name"] ?></td>
        <td><?= $row["description"] ?></td>
        <td><?= $row["price"] ?></td>
        <td>
          <a href="admin.php?delete=<?= $row['id'] ?>" class="btn btn-danger">Delete</a>
        </td>
      </tr>
      <?php endwhile; ?>
    </table>

    <br>
    <a href="dashboard.php">⬅ Back to Dashboard</a>
  </div>
</body>
</html>
