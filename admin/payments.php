<?php
// Include database connection file
include('connect.php');

if (!isset($_SESSION['uid'])) {
    echo "<script>window.location.href='../login.php';</script>";
    exit;
}

// Handle deletion of selected payments
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['selected_payments'])) {
    // Sanitize input
    $selected_payments = array_map('intval', $_POST['selected_payments']);

    // Prepare the SQL DELETE statement
    $placeholders = implode(',', array_fill(0, count($selected_payments), '?'));
    $sql = "DELETE FROM payments WHERE payment_id IN ($placeholders)";
    
    // Prepare the statement
    if ($stmt = $con->prepare($sql)) {
        // Bind the parameters
        $types = str_repeat('i', count($selected_payments));
        $stmt->bind_param($types, ...$selected_payments);
        
        // Execute the statement
        if ($stmt->execute()) {
            echo "<script>alert('Selected payments have been deleted.'); window.location.href='payments.php';</script>";
        } else {
            echo "Error: " . $stmt->error;
        }
        
        $stmt->close();
    } else {
        echo "Error: " . $con->error;
    }
}

// Fetch payment details
$sql = "SELECT payment_id, user_id, microid, quantity, card_number, cvv, expiration_date, amount, payment_status, created_at, address, phone_number
        FROM payments
        ORDER BY created_at DESC";
$result = $con->query($sql);

if ($result === false) {
    die("Error: " . $con->error);
}

// Fetch data into an associative array
$payments = $result->fetch_all(MYSQLI_ASSOC);

// Fetch the total quantity sold for each microgreen
$sales_sql = "SELECT microid, SUM(quantity) AS total_quantity
              FROM payments
              GROUP BY microid";
$sales_result = $con->query($sales_sql);

if ($sales_result === false) {
    die("Error: " . $con->error);
}

// Fetch data into an associative array
$sales_data = $sales_result->fetch_all(MYSQLI_ASSOC);

$con->close();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Payment Details</title>
    <style>
        body {
            font-family: 'Arial', sans-serif;
            background-color: #f0f0f0;
            color: #333;
            margin: 0;
            padding: 0;
        }

        h2 {
            color: #2d8f2d;
            font-size: 2.5em;
            margin: 20px 0;
            text-align: center;
        }

        form {
            width: 100%;
            margin: 20px auto;
            padding: 0 20px;
        }

        table {
            width: 97%;
            border-collapse: collapse;
            margin-bottom: 20px;
        }

        th, td {
            padding: 12px;
            border: 1px solid #ddd;
            text-align: left;
            background-color: lightgray;
        }

        th {
            background-color: #2d8f2d;
            color: white;
            text-transform: uppercase;
            letter-spacing: 0.05em;
        }

        tr:nth-child(even) {
            background-color: #f2f2f2;
        }

        tr:hover {
            background-color: #e9f5e9;
        }

        button {
            background-color: #dc3545;
            color: white;
            padding: 12px 25px;
            border: none;
            border-radius: 8px;
            cursor: pointer;
            font-size: 1.1em;
            transition: background-color 0.3s ease, transform 0.2s ease;
            display: block;
            margin: 0 auto;
        }

        button:hover {
            background-color: #c82333;
            transform: scale(1.05);
        }

        #chart-container {
            width: 80%;
            margin: 0 auto;
        }
    </style>
    <!-- Include Chart.js library -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
</head>
<body>
    <?php include('header.php'); ?>
    <h2>Payment Details</h2>
    
    <!-- Chart Container -->
    <div id="chart-container">
        <canvas id="salesChart"></canvas>
    </div>

    <?php if (!empty($payments)): ?>
        <form action="payments.php" method="post">
            <table>
                <thead>
                    <tr>
                        <th>Payment ID</th>
                        <th>User ID</th>
                        <th>Microgreen ID</th>
                        <th>Quantity</th>
                        <th>Card Number</th>
                        <th>CVV</th>
                        <th>Expiration Date</th>
                        <th>Amount</th>
                        <th>Address</th>
                        <th>Phone Number</th>
                        <th>Payment Status</th>
                        <th>Created At</th>
                        <th>Select</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($payments as $payment): ?>
                        <tr>
                            <td><?= htmlspecialchars($payment['payment_id']) ?></td>
                            <td><?= htmlspecialchars($payment['user_id']) ?></td>
                            <td><?= htmlspecialchars($payment['microid']) ?></td>
                            <td><?= htmlspecialchars($payment['quantity']) ?></td>
                            <td><?= htmlspecialchars($payment['card_number']) ?></td>
                            <td><?= htmlspecialchars($payment['cvv']) ?></td>
                            <td><?= htmlspecialchars($payment['expiration_date']) ?></td>
                            <td><?= htmlspecialchars($payment['amount']) ?></td>
                            <td><?= htmlspecialchars($payment['address']) ?></td>
                            <td><?= htmlspecialchars($payment['phone_number']) ?></td>
                            <td><?= htmlspecialchars($payment['payment_status']) ?></td>
                            <td><?= htmlspecialchars($payment['created_at']) ?></td>
                            <td><input type="checkbox" name="selected_payments[]" value="<?= htmlspecialchars($payment['payment_id']) ?>"></td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
            <button type="submit">Delete Selected</button>
        </form>
    <?php else: ?>
        <p style="text-align: center;">No payment details found.</p>
    <?php endif; ?>

    <script>
        // Prepare data for the chart
        const labels = <?= json_encode(array_column($sales_data, 'microid')) ?>;
        const data = <?= json_encode(array_column($sales_data, 'total_quantity')) ?>;

        // Create the bar chart
        const ctx = document.getElementById('salesChart').getContext('2d');
        new Chart(ctx, {
            type: 'bar',
            data: {
                labels: labels,
                datasets: [{
                    label: 'Total Quantity Sold',
                    data: data,
                    backgroundColor: 'rgba(0, 123, 255, 0.5)',
                    borderColor: 'rgba(0, 123, 255, 1)',
                    borderWidth: 1
                }]
            },
            options: {
                scales: {
                    y: {
                        beginAtZero: true
                    }
                },
                responsive: true,
                maintainAspectRatio: false
            }
        });
    </script>
</body>
</html>
