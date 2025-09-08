<?php
session_start();

// Redirect if not logged in
if (!isset($_SESSION["user_id"]) && !isset($_COOKIE["user_id"])) {
    header("Location: login.php");
    exit;
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Products</title>
  <link rel="stylesheet" href="style.css">
  <style>
    .products {
      display: grid;
      grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
      gap: 20px;
      margin: 20px;
    }
    .product {
      background: #fff;
      padding: 15px;
      border-radius: 8px;
      box-shadow: 0 0 10px rgba(0,0,0,0.1);
      cursor: pointer;
      text-align: center;
    }
    .popup {
      display: none;
      position: fixed;
      top: 0; left: 0; right: 0; bottom: 0;
      background: rgba(0,0,0,0.6);
      justify-content: center;
      align-items: center;
    }
    .popup-content {
      background: #fff;
      padding: 20px;
      border-radius: 10px;
      text-align: center;
      position: relative;
    }
    .popup-content span {
      position: absolute;
      top: 10px;
      right: 15px;
      font-size: 20px;
      cursor: pointer;
    }
  </style>
</head>
<body>
  <div class="form-container">
    <h2>Our Classic Products 🕰️✨</h2>
    <div class="products">
      <div class="product" onclick="showPopup('Classic Watch')">⌚ Classic Watch</div>
      <div class="product" onclick="showPopup('Leather Bracelet')">📿 Leather Bracelet</div>
      <div class="product" onclick="showPopup('Gold Necklace')">💎 Gold Necklace</div>
      <div class="product" onclick="showPopup('Notebook')">📓 Classic Notebook</div>
      <div class="product" onclick="showPopup('Pen')">🖊️ Classic Pen</div>
    </div>
    <a href="dashboard.php">⬅ Back to Dashboard</a>
  </div>

  <!-- Popup -->
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
    window.onclick = function(event) {
      if (event.target == document.getElementById("popup")) {
        closePopup();
      }
    }
  </script>
</body>
</html>
