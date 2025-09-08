<?php
session_start();
include("db.php");

// Only admin allowed (you can extend this later with roles)
if (!isset($_SESSION["user"])) {
    header("Location: login.php");
    exit();
}

$msg = "";

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $name = trim($_POST["name"]);
    $desc = trim($_POST["description"]);
    $price = floatval($_POST["price"]);
    $image = trim($_POST["image"]); // for now just a URL

    if ($name && $price && $image) {
        $stmt = $conn->prepare("INSERT INTO products (name, description, price, image) VALUES (?, ?, ?, ?)");
        $stmt->execute([$name, $desc, $price, $image]);
        $msg = "✅ Product added successfully!";
    } else {
        $msg = "⚠️ All fields are required!";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Add Product</title>
  <link rel="stylesheet" href="style.css">
</head>
<body>
  <h1>Add a New Product</h1>
  <p style="color: green;"><?php echo $msg; ?></p>

  <form method="post">
    <input type="text" name="name" placeholder="Product Name" required><br><br>
    <textarea name="description" placeholder="Product Description"></textarea><br><br>
    <input type="number" step="0.01" name="price" placeholder="Price" required><br><br>
    <input type="text" name="image" placeholder="Image URL (e.g. images/watch1.jpg)" required><br><br>
    <button type="submit">Add Product</button>
  </form>
</body>
</html>
