<?php
require_once '../Includes/dbConnect.php'; // Database connection

// Fetch all orders with user details
$stmt = $pdo->query("
    SELECT orders.id, users.name AS buyer_name, users.email AS buyer_email, 
           orders.artwork_id, orders.amount, orders.payment_status, 
           orders.created_at, orders.address, orders.contact_no
    FROM orders
    LEFT JOIN users ON orders.user_id = users.id
    ORDER BY orders.created_at DESC
");
$orders = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Panel - Orders</title>
    <link rel="stylesheet" href="../Assets/displaystyle.css">
    <style>
        .order-table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }
        .order-table th, .order-table td {
            border: 1px solid #ddd;
            padding: 8px;
            text-align: center;
        }
        .order-table th {
            background-color:rgb(0, 0, 0);
        }
        .status-success {
            color: green;
            font-weight: bold;
        }
        .status-failed {
            color: red;
            font-weight: bold;
        }
        .button-container {
        background-color: white;
        padding: 10px;
        border-radius: 5px;
        display: inline-block;
        box-shadow: 0 2px 5px rgba(0, 0, 0, 0.2);
    }

    .btn-back {
        text-decoration: none;
        color: black;
        font-weight: bold;
        padding: 8px 12px;
        display: block;
    }

    .btn-back:hover {
        color: #555;
    }
    </style>
</head>
<body>
    <h1 class="form-title">Admin Panel - View Orders</h1>

    <div class="button-container">
    <a href="admin-panel.php" class="btn-back">Back to Dashboard</a>
</div>

    <table class="order-table">
        <thead>
            <tr>
                <th>Order ID</th>
                <th>Buyer Name</th>
                <th>Email</th>
                <th>Artwork ID</th>
                <th>Amount</th>
                <th>Order Date</th>
                <th>Address</th>
                <th>Contact No</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($orders as $order): ?>
                <tr>
                    <td><?= htmlspecialchars($order['id']); ?></td>
                    <td><?= htmlspecialchars($order['buyer_name'] ?? 'N/A'); ?></td>
                    <td><?= htmlspecialchars($order['buyer_email'] ?? 'N/A'); ?></td>
                    <td><?= htmlspecialchars($order['artwork_id']); ?></td>
                    <td>NRs<?= number_format($order['amount'], 2); ?></td>
                    <td><?= htmlspecialchars($order['created_at']); ?></td>
                    <td><?= htmlspecialchars($order['address'] ?? 'N/A'); ?></td>
                    <td><?= htmlspecialchars($order['contact_no'] ?? 'N/A'); ?></td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</body>
</html>
