<?php
session_start();

// If you want products from database later, connect here
// include("db.php");

// Example products (for now hard-coded)
$products = [
    ["name" => "Classic Leather Watch", "price" => 750, "desc" => "Elegant brown leather strap.", "img" => "images/watch1.jpg"],
    ["name" => "Silver Bracelet", "price" => 250, "desc" => "Minimalist and timeless design.", "img" => "images/bracelet1.jpg"],
    ["name" => "Notebook (Vintage)", "price" => 80, "desc" => "Classic school supply with retro design.", "img" => "images/notebook1.jpg"],
    ["name" => "Fountain Pen", "price" => 150, "desc" => "Smooth writing, classic touch.", "img" => "images/pen1.jpg"],
];
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Products</title>
  <link rel="stylesheet" href="style.css">
  <style>
    body { font-family: Arial, sans-serif; margin: 20px; }
    .products { display: grid; grid-template-columns: repeat(auto-fill, minmax(220px, 1fr)); gap: 20px; }
    .product { border: 1px solid #ccc; padding: 15px; border-radius: 10px; text-align: center; background: #fafafa; }
    .product img { max-width: 100%; height: 150px; object-fit: cover; border-radius: 8px; }
    .product h3 { margin: 10px 0; font-size: 18px; }
    .product p { font-size: 14px; color: #555; }
    .product span { display: block; margin: 8px 0; font-weight: bold; }
    .add-to-cart { padding: 8px 12px; background: #333; color: white; border: none; border-radius: 5px; cursor: pointer; }
    .add-to-cart:hover { background: #555; }
  </style>
</head>
<body>
  <h1>Our Classic Collection</h1>
  
  <div class="products">
    <?php foreach ($products as $p): ?>
      <div class="product">
        <img src="<?php echo $p['img']; ?>" alt="<?php echo $p['name']; ?>">
        <h3><?php echo $p['name']; ?></h3>
        <p><?php echo $p['desc']; ?></p>
        <span>Price: <?php echo $p['price']; ?> EGP</span>
        <button class="add-to-cart">Add to Cart</button>
      </div>
    <?php endforeach; ?>
  </div>
</body>
</html>
