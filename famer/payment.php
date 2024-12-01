<?php

include('connect.php');

// Redirect if not logged in
if (!isset($_SESSION['uid'])) {
    header("Location: login.php");
    exit();
}

// Fetch payment details for the logged-in user
$sql = "SELECT * FROM farmers_payment WHERE userid = ?";
$stmt = $con->prepare($sql);
$stmt->bind_param("i", $_SESSION['uid']);
$stmt->execute();
$result = $stmt->get_result();
$payments = [];
while ($row = $result->fetch_assoc()) {
    $payments[] = $row;
}
$stmt->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Payment Details</title>
    <style>
/* General Styling */
body {
    font-family: 'Lato', sans-serif;
    background-color: #f5f5f5;
    margin: 0;
    padding: 0;
}

.container {
    width: 90%;
    max-width: 1200px;
    margin: 0 auto;
    padding: 20px;
    background-color: #fff;
    border-radius: 10px;
    box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
}

h2 {
    font-family: 'Lato', sans-serif;
    font-weight: 700;
    color: #0F2429;
    font-size: 1.8rem;
    margin-bottom: 20px;
}

/* Table Styling */
table {
    width: 100%;
    border-collapse: collapse;
    margin-top: 20px;
}

th, td {
    padding: 12px;
    text-align: left;
    border-bottom: 1px solid #ddd;
}

th {
    background-color: #0F2429;
    color: white;
}

tbody tr:nth-child(even) {
    background-color: #f9f9f9;
}

tbody tr:hover {
    background-color: #f1f1f1;
}

.buttons {
    margin-top: 20px;
}

button {
    padding: 12px 20px;
    font-size: 16px;
    background-color: #0F2429;
    color: #fff;
    border: none;
    border-radius: 5px;
    cursor: pointer;
    transition: background-color 0.3s ease, color 0.3s ease, transform 0.3s ease, box-shadow 0.3s ease;
    text-align: center;
    box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
}

button:hover {
    background-color:#1A3942;
    color: #32CD32;
    letter-spacing: 1px;
    transform: scale(1.05);
    box-shadow: 0 6px 12px rgba(0, 0, 0, 0.2);
}

button:focus {
    outline: none;
}

/* No Data Styling */
.no-data {
    font-size: 1.2rem;
    color: #0F2429;
    text-align: center;
    margin-top: 20px;
}

/* Responsive Design */
@media screen and (max-width: 768px) {
    table {
        font-size: 14px;
    }

    button {
        font-size: 14px;
        padding: 10px 15px;
    }
}


    </style>
    <script>
        function printTable() {
            var printContents = document.getElementById('paymentTable').outerHTML;
            var originalContents = document.body.innerHTML;
            document.body.innerHTML = printContents;
            window.print();
            document.body.innerHTML = originalContents;
        }

        function downloadTable() {
            let tableText = document.getElementById('paymentTable').outerHTML;
            tableText = tableText.replace(/<thead>/g, '')
                                .replace(/<\/thead>/g, '')
                                .replace(/<tbody>/g, '')
                                .replace(/<\/tbody>/g, '')
                                .replace(/<tr>/g, '\n')
                                .replace(/<\/tr>/g, '')
                                .replace(/<th>/g, '')
                                .replace(/<\/th>/g, ',')
                                .replace(/<td>/g, '')
                                .replace(/<\/td>/g, ',')
                                .replace(/<[^>]+>/g, '');
            const blob = new Blob([tableText], { type: 'text/csv' });
            const url = window.URL.createObjectURL(blob);
            const a = document.createElement('a');
            a.style.display = 'none';
            a.href = url;
            a.download = 'payment_details.csv';
            document.body.appendChild(a);
            a.click();
            window.URL.revokeObjectURL(url);
        }

        function downloadRow(paymentId) {
            let row = document.getElementById('row-' + paymentId).outerHTML;
            row = row.replace(/<td>/g, '')
                     .replace(/<\/td>/g, ',')
                     .replace(/<[^>]+>/g, '');
            const blob = new Blob([row], { type: 'text/csv' });
            const url = window.URL.createObjectURL(blob);
            const a = document.createElement('a');
            a.style.display = 'none';
            a.href = url;
            a.download = 'payment_' + paymentId + '.csv';
            document.body.appendChild(a);
            a.click();
            window.URL.revokeObjectURL(url);
        }
    </script>
</head>
<body>
    <?php include('header.php'); ?>

    <div class="container">
        <h2>Payment Details</h2>

        <?php if (count($payments) > 0): ?>
            <table id="paymentTable">
                <thead>
                    <tr>
                        <th>Payment ID</th>
                        <th>Quantity</th>
                        <th>Card Number</th>
                       
                        <th>Address</th>
                        <th>Phone Number</th>
                        <th>Amount</th>
                        <th>Date Created</th>
                        <th>Download</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($payments as $payment): ?>
                        <tr id="row-<?= htmlspecialchars($payment['payment_id']) ?>">
                            <td><?= htmlspecialchars($payment['payment_id']) ?></td>
                            <td><?= htmlspecialchars($payment['quantity']) ?></td>
                            <td><?= htmlspecialchars($payment['card_number']) ?></td>
                            
                            
                            <td><?= htmlspecialchars($payment['address']) ?></td>
                            <td><?= htmlspecialchars($payment['phone_number']) ?></td>
                            <td><?= htmlspecialchars($payment['amount']) ?></td>
                            <td><?= htmlspecialchars($payment['created_at']) ?></td>
                            <td><button onclick="downloadRow(<?= htmlspecialchars($payment['payment_id']) ?>)">Download</button></td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
            <div class="buttons">
                <button onclick="printTable()">Print</button>
                <button onclick="downloadTable()">Download CSV</button>
            </div>
        <?php else: ?>
            <p class="no-data">No payment details available.</p>
        <?php endif; ?>
    </div>

    <?php include('footer.php') ?>
    <?php include('chat.php') ?>
<?php include('weather.php') ?>
<?php include('social.php'); ?>
</body>
</html>
