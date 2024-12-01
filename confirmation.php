<?php
include('connect.php');


// Redirect if not logged in
if (!isset($_SESSION['uid'])) {
    header("Location: login.php");
    exit();
}

// Get the latest payment details for the current user
$user_id = $_SESSION['uid'];
$sql = "SELECT * FROM payments WHERE user_id = ? ORDER BY payment_id DESC LIMIT 1";
$stmt = $con->prepare($sql);
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();
$payment = $result->fetch_assoc();

if (!$payment) {
    echo "No payment found.";
    exit();
}

// Close the database connection
$stmt->close();
$con->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Payment Confirmation</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            line-height: 1.6;
        }
        .container {
            width: 80%;
            margin: auto;
            padding: 20px;
        }
        .confirmation-header {
            text-align: center;
            margin-bottom: 20px;
        }
        .confirmation-details table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
        }
        .confirmation-details th, .confirmation-details td {
            border: 1px solid #ddd;
            padding: 8px;
            text-align: left;
        }
        .confirmation-details th {
            background-color: #f4f4f4;
        }
        .print-btn {
            display: block;
            width: 200px;
            margin: 20px auto;
            padding: 10px;
            background-color: #007bff;
            color: #fff;
            border: none;
            cursor: pointer;
            text-align: center;
        }
        @media print {
            .print-btn {
                display: none;
            }
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="confirmation-header">
            <h2>Thank You for Your Payment!</h2>
            <p>Your payment has been successfully processed. Your product will be delivered in 2 days.</p>
            <p>If you have any issues, please contact our support center.</p>
        </div>

        <div class="confirmation-details">
            <table>
                <tr>
                    <th>Order ID</th>
                    <td><?= htmlspecialchars($payment['payment_id']) ?></td>
                </tr>
                <tr>
                    <th>Microgreen ID</th>
                    <td><?= htmlspecialchars($payment['microid']) ?></td>
                </tr>
                <tr>
                    <th>Quantity</th>
                    <td><?= htmlspecialchars($payment['quantity']) ?></td>
                </tr>
                <tr>
                    <th>Amount</th>
                    <td><?= htmlspecialchars($payment['amount']) ?></td>
                </tr>
                <tr>
                    <th>Address</th>
                    <td><?= htmlspecialchars($payment['address']) ?></td>
                </tr>
                <tr>
                    <th>Phone Number</th>
                    <td><?= htmlspecialchars($payment['phone_number']) ?></td>
                </tr>
                <tr>
                    <th>Payment Status</th>
                    <td>Success</td>
                </tr>
                <tr>
                    <th>Created At</th>
                    <td><?= htmlspecialchars($payment['created_at']) ?></td>
                </tr>
            </table>
        </div>

        <button class="print-btn" onclick="window.print()">Print Confirmation</button>
        <a href="index.php" class="btn-primary">Back to Home</a>
    </div>
</body>
</html>
