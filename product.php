<?php
session_start();
include "db.php";

// Redirect if not logged in
if (!isset($_SESSION["user_id"]) && !isset($_COOKIE["user_id"])) {
    header("Location: login.php");
    exit;
}

$sql = "SELECT * FROM products";
$result = $conn->query($sql);
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Products</title>
  <link rel="stylesheet" href="style.css">
</head>
<body>
  <div class="form-container">
    <h2>Our Classic Products 🕰️✨</h2>
    <div class="products">
      <?php while($row = $result->fetch_assoc()): ?>
        <div class="product" onclick="showPopup('<?php echo $row['name']; ?>')">
          <?php echo $row['name']; ?> <br>
          💵 <?php echo $row['price']; ?> EGP
        </div>
      <?php endwhile; ?>
    </div>
    <a href="dashboard.php">⬅ Back to Dashboard</a>
  </div>

  <div class="popup" id="popup">
    <div class="popup-content">
      <span onclick="closePopup()">&times;</span>
      <h3 id="popup-title"></h3>
      <p>To order, contact us on:</p>
      <p>📞 +20 100 123 4567</p>
      <p>📲 WhatsApp: +20 100 765 4321</p>
      <p>💬 Telegram: @YourStoreName</p>
    </div>
  </div>

  <script>
    function showPopup(product) {
      document.getElementById("popup-title").innerText = product;
      document.getElementById("popup").style.display = "flex";
    }
    function closePopup() {
      document.getElementById("popup").style.display = "none";
    }
  </script>
</body>
</html>
