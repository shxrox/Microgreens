<?php

include('connect.php');

if (!isset($_GET['order_id']) || !isset($_SESSION['uid'])) {
    header("Location: index.php");
    exit();
}

$order_id = mysqli_real_escape_string($con, $_GET['order_id']);
$sql = "SELECT orders.*, micro.title, micro.price, users.name AS username 
        FROM orders 
        JOIN micro ON orders.microid = micro.microid 
        JOIN users ON orders.user_id = users.userid 
        WHERE orders.order_id = '$order_id' AND orders.user_id = '{$_SESSION['uid']}'";
        
$res = mysqli_query($con, $sql);

if (mysqli_num_rows($res) > 0) {
    $order = mysqli_fetch_assoc($res);
} else {
    echo "Order not found.";
    exit();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Bill</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <h2>Order Receipt</h2>
    <div class="bill">
        <p><strong>Order ID:</strong> <?= htmlspecialchars($order['order_id']) ?></p>
        <p><strong>Customer Name:</strong> <?= htmlspecialchars($order['username']) ?></p>
        <p><strong>Microgreen:</strong> <?= htmlspecialchars($order['title']) ?></p>
        <p><strong>Quantity:</strong> <?= htmlspecialchars($order['quantity']) ?></p>
        <p><strong>Price:</strong> $<?= htmlspecialchars($order['price']) ?></p>
        <p><strong>Total:</strong> $<?= htmlspecialchars($order['price'] * $order['quantity']) ?></p>
        <p><strong>Status:</strong> <?= htmlspecialchars($order['status']) ?></p>
        <p><strong>Date:</strong> <?= htmlspecialchars($order['created_at']) ?></p>
    </div>
    <button onclick="window.print()">Print Bill</button>
</body>
</html>