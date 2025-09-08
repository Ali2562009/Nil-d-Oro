<?php
session_start();
include("db.php");

// Only admin allowed
if (!isset($_SESSION["user"]) || $_SESSION["role"] != "admin") {
    die("Access denied ❌");
}

// Mark order as completed
if (isset($_GET['complete'])) {
    $id = intval($_GET['complete']);
    $stmt = $conn->prepare("UPDATE orders SET completed=1 WHERE id=?");
    $stmt->execute([$id]);
    header("Location: admin_orders.php");
    exit();
}

// Export orders to CSV
if (isset($_GET['export'])) {
    header('Content-Type: text/csv; charset=utf-8');
    header('Content-Disposition: attachment; filename=orders.csv');
    $output = fopen('php://output', 'w');
    fputcsv($output, ['ID', 'Customer Name', 'Email', 'Phone', 'Order Details', 'Total', 'Date', 'Completed']);
    
    $ordersCSV = $conn->query("SELECT * FROM orders ORDER BY created_at DESC")->fetchAll(PDO::FETCH_ASSOC);
    foreach ($ordersCSV as $o) {
        fputcsv($output, [
            $o['id'],
            $o['customer_name'],
            $o['customer_email'],
            $o['customer_phone'],
            $o['order_details'],
            $o['total'],
            $o['created_at'],
            $o['completed'] ?? 0
        ]);
    }
    exit();
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
.btn { padding: 6px 10px; border-radius: 4px; cursor: pointer; margin-right: 5px; }
.btn-complete { background: green; color: white; border: none; }
.btn-export { background: blue; color: white; border: none; padding: 8px 12px; margin-bottom: 10px; }
.completed { background: #e0ffe0; }
</style>
</head>
<body>
<h1>Admin Orders 📝</h1>
<a href="admin_orders.php?export=1" class="btn btn-export">Export CSV</a>

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
    <th>Status</th>
</tr>
<?php foreach($orders as $o): ?>
<tr class="<?= !empty($o['completed']) ? 'completed' : '' ?>">
    <td><?= $o['id'] ?></td>
    <td><?= htmlspecialchars($o['customer_name']) ?></td>
    <td><?= htmlspecialchars($o['customer_email']) ?></td>
    <td><?= htmlspecialchars($o['customer_phone']) ?></td>
    <td><pre><?= htmlspecialchars($o['order_details']) ?></pre></td>
    <td><?= $o['total'] ?> EGP</td>
    <td><?= $o['created_at'] ?></td>
    <td>
        <?= !empty($o['completed']) ? "Completed ✅" : "<a href='admin_orders.php?complete=".$o['id']."' class='btn btn-complete'>Mark Completed</a>" ?>
    </td>
</tr>
<?php endforeach; ?>
</table>
<?php else: ?>
<p>No orders yet.</p>
<?php endif; ?>
</body>
</html>
