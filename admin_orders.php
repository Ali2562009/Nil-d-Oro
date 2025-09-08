<?php
session_start();
include("db.php");

// Only admin allowed
if (!isset($_SESSION["user"]) || $_SESSION["role"] != "admin") {
    die("Access denied ❌");
}

// Get search and date filters
$search = isset($_GET['search']) ? trim($_GET['search']) : "";
$startDate = isset($_GET['startDate']) ? $_GET['startDate'] : "";

// Pagination settings
$limit = 10; // Orders per page
$page = isset($_GET['page']) ? max(1, intval($_GET['page'])) : 1;
$offset = ($page - 1) * $limit;

// Mark order as completed
if (isset($_GET['complete'])) {
    $id = intval($_GET['complete']);
    $stmt = $conn->prepare("UPDATE orders SET completed=1 WHERE id=?");
    $stmt->execute([$id]);
    header("Location: admin_orders.php");
    exit();
}

// Export to CSV
if (isset($_GET['export'])) {
    header('Content-Type: text/csv; charset=utf-8');
    header('Content-Disposition: attachment; filename=orders.csv');
    $output = fopen('php://output', 'w');
    fputcsv($output, ['ID','Customer Name','Email','Phone','WhatsApp','Order Details','Total','Date','Completed']);

    $sqlExport = "SELECT * FROM orders WHERE (customer_name LIKE ? OR customer_email LIKE ? OR whatsapp LIKE ?)";
    $params = ["%$search%", "%$search%", "%$search%"];
    if($startDate){
        $sqlExport .= " AND created_at >= ?";
        $params[] = $startDate." 00:00:00";
    }
    $sqlExport .= " ORDER BY created_at DESC";
    $stmtExport = $conn->prepare($sqlExport);
    $stmtExport->execute($params);
    $allOrders = $stmtExport->fetchAll(PDO::FETCH_ASSOC);

    foreach($allOrders as $o){
        fputcsv($output, [
            $o['id'],
            $o['customer_name'],
            $o['customer_email'],
            $o['customer_phone'],
            $o['whatsapp'],
            $o['order_details'],
            $o['total'],
            $o['created_at'],
            $o['completed'] ?? 0
        ]);
    }
    exit();
}

// Count total orders for pagination
$countSQL = "SELECT COUNT(*) FROM orders WHERE (customer_name LIKE ? OR customer_email LIKE ? OR whatsapp LIKE ?)";
$paramsCount = ["%$search%", "%$search%", "%$search%"];
if($startDate){
    $countSQL .= " AND created_at >= ?";
    $paramsCount[] = $startDate." 00:00:00";
}
$stmtCount = $conn->prepare($countSQL);
$stmtCount->execute($paramsCount);
$totalOrders = $stmtCount->fetchColumn();
$totalPages = ceil($totalOrders / $limit);

// Fetch orders for current page
$sql = "SELECT * FROM orders WHERE (customer_name LIKE ? OR customer_email LIKE ? OR whatsapp LIKE ?)";
$params = ["%$search%", "%$search%", "%$search%"];
if($startDate){
    $sql .= " AND created_at >= ?";
    $params[] = $startDate." 00:00:00";
}
$sql .= " ORDER BY created_at DESC LIMIT $limit OFFSET $offset";
$stmt = $conn->prepare($sql);
$stmt->execute($params);
$orders = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>Admin Orders</title>
<link rel="stylesheet" href="style.css">
<style>
body { font-family: Arial, sans-serif; margin: 20px; }
form { margin-bottom: 15px; }
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

<!-- Search + Date Filter Form -->
<form method="get">
    <input type="text" name="search" placeholder="Search by Name, Email, WhatsApp" value="<?= htmlspecialchars($search) ?>">
    Start Date: <input type="date" name="startDate" value="<?= $startDate ?>">
    <button type="submit">Search</button>
    <a href="admin_orders.php?export=1<?= $search ? '&search='.urlencode($search) : '' ?><?= $startDate ? '&startDate='.$startDate : '' ?>" class="btn btn-export">Export CSV</a>
</form>

<?php if($orders): ?>
<table>
<tr>
    <th>ID</th>
    <th>Customer Name</th>
    <th>Email</th>
    <th>Phone</th>
    <th>WhatsApp</th>
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
    <td><?= htmlspecialchars($o['whatsapp']) ?></td>
    <td><pre><?= htmlspecialchars($o['order_details']) ?></pre></td>
    <td><?= $o['total'] ?> EGP</td>
    <td><?= $o['created_at'] ?></td>
    <td>
        <?= !empty($o['completed']) ? "Completed ✅" : "<a href='admin_orders.php?complete=".$o['id']."' class='btn btn-complete'>Mark Completed</a>" ?>
    </td>
</tr>
<?php endforeach; ?>
</table>

<!-- Pagination Links -->
<div style="margin-top: 15px;">
<?php if($totalPages > 1): ?>
    <?php for($i=1; $i<=$totalPages; $i++): ?>
        <a href="admin_orders.php?page=<?= $i ?>&search=<?= urlencode($search) ?><?= $startDate ? '&startDate='.$startDate : '' ?>" style="margin-right:5px;<?= $i==$page ? 'font-weight:bold;' : '' ?>"><?= $i ?></a>
    <?php endfor; ?>
<?php endif; ?>
</div>

<?php else: ?>
<p>No orders found.</p>
<?php endif; ?>

</body>
</html>
