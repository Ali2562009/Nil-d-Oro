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

// Upload helper function
function uploadImage($file) {
    $targetDir = "uploads/";
    if (!file_exists($targetDir)) {
        mkdir($targetDir, 0777, true);
    }
    $fileName = time() . "_" . basename($file["name"]);
    $targetFile = $targetDir . $fileName;

    if (move_uploaded_file($file["tmp_name"], $targetFile)) {
        return $fileName;
    }
    return null;
}

// Handle Add Product
if (isset($_POST["add"])) {
    $name = $_POST["name"];
    $desc = $_POST["description"];
    $price = $_POST["price"];
    $image = null;

    if (!empty($_FILES["image"]["name"])) {
        $image = uploadImage($_FILES["image"]);
    }

    $sql = "INSERT INTO products (name, description, price, image) VALUES (?, ?, ?, ?)";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ssds", $name, $desc, $price, $image);
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

// Handle Update Product
if (isset($_POST["update"])) {
    $id = $_POST["id"];
    $name = $_POST["name"];
    $desc = $_POST["description"];
    $price = $_POST["price"];
    $image = null;

    if (!empty($_FILES["image"]["name"])) {
        $image = uploadImage($_FILES["image"]);
        $sql = "UPDATE products SET name=?, description=?, price=?, image=? WHERE id=?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("ssdsi", $name, $desc, $price, $image, $id);
    } else {
        $sql = "UPDATE products SET name=?, description=?, price=? WHERE id=?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("ssdi", $name, $desc, $price, $id);
    }
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
    img { width: 80px; height: auto; border-radius: 6px; }
    .btn { padding: 6px 10px; text-decoration: none; border-radius: 4px; }
    .btn-danger { background: red; color: white; }
    .btn-edit { background: orange; color: white; }
  </style>
</head>
<body>
  <div class="form-container">
    <h2>Admin Dashboard 🛠️</h2>

    <!-- Add Product Form -->
    <form method="post" enctype="multipart/form-data">
      <input type="text" name="name" placeholder="Product Name" required>
      <input type="text" name="description" placeholder="Description">
      <input type="number" step="0.01" name="price" placeholder="Price (EGP)" required>
      <input type="file" name="image" accept="image/*">
      <button type="submit" name="add">Add Product</button>
    </form>

    <!-- Product List -->
    <table>
      <tr>
        <th>ID</th>
        <th>Image</th>
        <th>Name</th>
        <th>Description</th>
        <th>Price (EGP)</th>
        <th>Actions</th>
      </tr>
      <?php while ($row = $result->fetch_assoc()): ?>
      <tr>
        <form method="post" enctype="multipart/form-data">
          <td><?= $row["id"] ?></td>
          <td>
            <?php if ($row["image"]): ?>
              <img src="uploads/<?= $row["image"] ?>" alt="Product">
            <?php else: ?>
              ❌ No Image
            <?php endif; ?>
            <input type="file" name="image" accept="image/*">
          </td>
          <td><input type="text" name="name" value="<?= $row['name'] ?>"></td>
          <td><input type="text" name="description" value="<?= $row['description'] ?>"></td>
          <td><input type="number" step="0.01" name="price" value="<?= $row['price'] ?>"></td>
          <td>
            <input type="hidden" name="id" value="<?= $row['id'] ?>">
            <button type="submit" name="update" class="btn btn-edit">Update</button>
            <a href="admin.php?delete=<?= $row['id'] ?>" class="btn btn-danger">Delete</a>
          </td>
        </form>
      </tr>
      <?php endwhile; ?>
    </table>

    <br>
    <a href="dashboard.php">⬅ Back to Dashboard</a>
  </div>
</body>
</html>
