<?php
session_start();
include 'db.php';

// Simple admin protection (you can expand this later)
session_start();
include 'db.php';

// Check if admin is logged in
if (!isset($_SESSION['is_admin']) || $_SESSION['is_admin'] !== true) {
    header("Location: admin_login.php");
    exit;
}

$result = $conn->query("SELECT * FROM orders ORDER BY created_at DESC");
if (!$isAdmin) {
    die("Access denied.");
}

$result = $conn->query("SELECT * FROM orders ORDER BY created_at DESC");
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>Nil d’Oro - Admin Dashboard</title>
<link href="https://fonts.googleapis.com/css2?family=Great+Vibes&display=swap" rel="stylesheet">
<style>
body {
    font-family: "Great Vibes", cursive;
    background: #fdf6f0;
    color: #3a2e2e;
    padding: 20px;
    text-align: center;
}
h1 {
    font-size: 48px;
    margin-bottom: 20px;
}
table {
    width: 90%;
    margin: auto;
    border-collapse: collapse;
    background: #fff;
    border-radius: 10px;
    overflow: hidden;
    box-shadow: 0px 4px 8px rgba(0,0,0,0.2);
}
th, td {
    padding: 12px;
    border-bottom: 1px solid #ddd;
    font-family: serif;
}
th {
    background: #3a2e2e;
    color: #fff;
}
.order-products {
    font-size: 14px;
    text-align: left;
}
</style>
</head>
<body>
    <a href="logout.php" style="float:right; margin:10px; padding:8px 15px; background:#3a2e2e; color:#fff; border-radius:8px; text-decoration:none;">Logout</a>
    <h1>Nil d’Oro — Admin Dashboard</h1>

    <table>
        <tr>
            <th>ID</th>
            <th>Client</th>
            <th>Email</th>
            <th>WhatsApp</th>
            <th>Address</th>
            <th>Products</th>
            <th>Total (EGP)</th>
            <th>Date</th>
        </tr>
        <?php while ($row = $result->fetch_assoc()): ?>
        <tr>
            <td><?= $row['id'] ?></td>
            <td><?= htmlspecialchars($row['name']) ?></td>
            <td><?= htmlspecialchars($row['email']) ?></td>
            <td><?= htmlspecialchars($row['whatsapp']) ?></td>
            <td><?= htmlspecialchars($row['address']) ?></td>
            <td class="order-products">
                <?php
                $items = json_decode($row['order_data'], true);
                foreach ($items as $item => $details) {
                    echo htmlspecialchars($item) . " (" . $details['quantity'] . " × " . $details['price'] . " EGP)<br>";
                }
                ?>
            </td>
            <td><?= number_format($row['total'], 2) ?></td>
            <td><?= $row['created_at'] ?></td>
        </tr>
        <?php endwhile; ?>
    </table>
</body>
</html>
