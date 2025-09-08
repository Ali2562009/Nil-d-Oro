<?php
session_start();
include 'db.php';

// Handle search & filter
$search = isset($_GET['search']) ? trim($_GET['search']) : '';
$category = isset($_GET['category']) ? trim($_GET['category']) : '';
$minPrice = isset($_GET['minPrice']) ? (int)$_GET['minPrice'] : 0;
$maxPrice = isset($_GET['maxPrice']) ? (int)$_GET['maxPrice'] : 999999;

// Fetch categories for filter dropdown
$categories = [];
$result = $conn->query("SELECT DISTINCT category FROM products");
while ($row = $result->fetch_assoc()) {
    $categories[] = $row['category'];
}

// Build query
$sql = "SELECT * FROM products WHERE active=1 AND price BETWEEN ? AND ? AND name LIKE ?";
$params = [$minPrice, $maxPrice, "%$search%"];
$types = "iis";

if ($category !== '') {
    $sql .= " AND category=?";
    $params[] = $category;
    $types .= "s";
}

$stmt = $conn->prepare($sql);
$stmt->bind_param($types, ...$params);
$stmt->execute();
$result = $stmt->get_result();
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Nil d’Oro | Products</title>
  <link rel="stylesheet" href="style.css">
</head>
<body>
  <header>
    <h1 style="font-family: 'Tangerine', cursive; font-size: 3rem;">Nil d’Oro</h1>
    <nav>
      <a href="index.php">Home</a> |
      <a href="cart.php">🛒 Cart (<?php echo isset($_SESSION['cart']) ? count($_SESSION['cart']) : 0; ?>)</a>
    </nav>
  </header>

  <main>
    <h2>Our Products</h2>

    <!-- 🔎 Search & Filter Form -->
    <form method="GET" style="margin-bottom:20px;">
      <input type="text" name="search" placeholder="Search products..." value="<?php echo htmlspecialchars($search); ?>">
      
      <select name="category">
        <option value="">All Categories</option>
        <?php foreach ($categories as $cat) { ?>
          <option value="<?php echo htmlspecialchars($cat); ?>" <?php if ($cat==$category) echo "selected"; ?>>
            <?php echo htmlspecialchars($cat); ?>
          </option>
        <?php } ?>
      </select>

      <input type="number" name="minPrice" placeholder="Min Price" value="<?php echo $minPrice; ?>">
      <input type="number" name="maxPrice" placeholder="Max Price" value="<?php echo $maxPrice; ?>">

      <button type="submit">Filter</button>
    </form>

    <!-- 🛍️ Product Grid -->
    <div class="product-grid">
      <?php if ($result->num_rows > 0) { 
        while ($row = $result->fetch_assoc()) { ?>
          <div class="product-card">
            <img src="<?php echo htmlspecialchars($row['image']); ?>" alt="<?php echo htmlspecialchars($row['name']); ?>">
            <h3><?php echo htmlspecialchars($row['name']); ?></h3>
            <p><?php echo htmlspecialchars($row['description']); ?></p>
            <p><strong>$<?php echo number_format($row['price'], 2); ?></strong></p>
            <form method="POST" action="add_to_cart.php">
              <input type="hidden" name="product_id" value="<?php echo $row['id']; ?>">
              <button type="submit">Add to Cart</button>
            </form>
          </div>
      <?php } } else { ?>
          <p>No products found matching your search.</p>
      <?php } ?>
    </div>
  </main>
</body>
</html>
