<div class="products">
  <?php while($row = $result->fetch_assoc()): ?>
    <div class="product" onclick="showPopup('<?php echo $row['name']; ?>')">
      <?php if ($row["image"]): ?>
        <img src="uploads/<?php echo $row['image']; ?>" alt="Product" style="width:120px; height:auto; margin-bottom:10px;">
      <?php endif; ?>
      <strong><?php echo $row['name']; ?></strong><br>
      💵 <?php echo $row['price']; ?> EGP
    </div>
  <?php endwhile; ?>
</div>
