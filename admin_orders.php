<?php
session_start();
include("db.php");

// Only admin allowed
if (!isset($_SESSION["user"]) || $_SESSION["role"] != "admin") {
    die("Access denied ❌");
}

// Fetch all orders
$orders = $conn->query("SELECT * FROM orders ORDER BY created_at DESC")->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>Admin Orders</title>
<link rel="stylesheet" href="style.css">
<style>
table { width: 100%; border-collapse: collapse; margin-top: 20px; }
table, th, td { border: 1px solid #ccc; padding: 10px; text-align: left; vertical-align: top; }
th { background: #f4f4f4; }
pre { background: #fafafa; padding: 10px; border-radius: 5px; }
</style>
</head>
<body>
<h1>Admin Orders 📝</h1>

<?php if($orders): ?>
<table>
<tr>
    <th>ID</th>
    <th>Customer Name</th>
    <th>Email</th>
    <th>Phone</th>
    <th>Order Details</th>
    <th>Total</th>
    <th>Order Date</th>
</tr>
<?php foreach($orders as $o): ?>
<tr>
    <td><?= $o['id'] ?></td>
    <td><?= htmlspecialchars($o['customer_name']) ?></td>
    <td><?= htmlspecialchars($o['customer_email']) ?></td>
    <td><?= htmlspecialchars($o['customer_phone']) ?></td>
    <td><pre><?= htmlspecialchars($o['order_details']) ?></pre></td>
    <td><?= $o['total'] ?> EGP</td>
    <td><?= $o['created_at'] ?></td>
</tr>
<?php endforeach; ?>
</table>
<?php else: ?>
<p>No orders yet.</p>
<?php endif; ?>
</body>
</html>
