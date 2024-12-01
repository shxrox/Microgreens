<?php
include('connect.php');


// Redirect if not logged in
if (!isset($_SESSION['uid'])) {
    header("Location: login.php");
    exit();
}

// Get user ID
$user_id = $_SESSION['uid'];

// Fetch payment details for the current user
$sql = "SELECT * FROM payments WHERE user_id = ?";
$stmt = $con->prepare($sql);
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();

// Check if there are any payments
if ($result->num_rows > 0) {
    $payments = $result->fetch_all(MYSQLI_ASSOC);
} else {
    $payments = [];
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Payment Details</title>
    <style>
        body {
    font-family: 'Lato', sans-serif; /* Ensure the font family is applied */
    font-weight: 300; /* Regular weight */
    color: #333;
    background-color: #f4f4f4;
    margin: 0;
    padding: 0;
    text-align: center; /* Center the text */
}

.payment-container {
    width: 90%;
    max-width: 1000px; /* Adjusted max-width for a smaller container */
    margin: 60px auto; /* Center container horizontally */
    padding: 20px;
    background-color: #fff;
    border-radius: 8px;
    box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
}

h2 {
    color: #32CD32;
    margin-bottom: 20px;
    font-size: 20px; /* Slightly smaller font size for headings */
}

table {
    width: 100%;
    border-collapse: collapse;
    margin-bottom: 20px;
}

th, td {
    padding: 8px; /* Reduced padding for compactness */
    border: 1px solid #ddd;
    text-align: left;
    font-size: 14px; /* Smaller font size for table cells */
}

th {
    background-color: #0F2429;
    color: #fff;
    font-size: 14px; /* Smaller font size for table headers */
}

tr:nth-child(even) {
    background-color: #f9f9f9;
}

tr:hover {
    background-color: #f1f1f1;
}

p {
    font-size: 14px; /* Smaller font size for paragraphs */
    color: #555;
}

/* Responsive Design */
@media (max-width: 768px) {
    table, th, td {
        display: block;
        width: 100%;
    }

    th, td {
        box-sizing: border-box;
        padding: 8px;
        border: none;
    }

    th {
        background-color: #0F2429;
        color: #fff;
        text-align: center;
    }

    tr {
        margin-bottom: 10px;
        display: block;
        border-bottom: 2px solid #ddd;
    }

    td {
        text-align: right;
        position: relative;
        padding-left: 50%;
        font-size: 13px; /* Smaller font size for mobile */
    }

    td::before {
        content: attr(data-label);
        position: absolute;
        left: 0;
        width: 50%;
        padding-left: 10px;
        font-weight: bold;
        color: #32CD32;
        white-space: nowrap;
    }
}

    </style>
</head>
<body>
    <?php include('header.php') ?>

    <div class="payment-container">
        <h2>Payment Details</h2>

        <?php if (!empty($payments)): ?>
            <form action="update_payment_status.php" method="post">
                <table>
                    <thead>
                        <tr>
                            <th>Payment ID</th>
                            <th>Microgreen ID</th>
                            <th>Quantity</th>
                            <th>Card Number</th>
                            <th>CVV</th>
                            <th>Expiration Date</th>
                            <th>Amount</th>
                            <th>Address</th>
                            <th>Phone Number</th>
                            <th>Created At</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($payments as $payment): ?>
                            <tr>
                                <td><?= htmlspecialchars($payment['payment_id']) ?></td>
                                <td><?= htmlspecialchars($payment['microid']) ?></td>
                                <td><?= htmlspecialchars($payment['quantity']) ?></td>
                                <td><?= htmlspecialchars($payment['card_number']) ?></td>
                                <td><?= htmlspecialchars($payment['cvv']) ?></td>
                                <td><?= htmlspecialchars($payment['expiration_date']) ?></td>
                                <td><?= htmlspecialchars($payment['amount']) ?></td>
                                <td><?= htmlspecialchars($payment['address']) ?></td>
                                <td><?= htmlspecialchars($payment['phone_number']) ?></td>
                                <td><?= htmlspecialchars($payment['created_at']) ?></td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </form>
        <?php else: ?>
            <p>No payment details found.</p>
        <?php endif; ?>
    </div>

    
</body>
<?php include('footer.php') ?>
<?php include('chat.php') ?>
<?php include('social.php') ?>
</html>

