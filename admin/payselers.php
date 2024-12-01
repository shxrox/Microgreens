<?php
include('connect.php');


// Redirect if not logged in
if (!isset($_SESSION['uid'])) {
    header("Location: login.php");
    exit();
}

// Fetch users for the dropdown
$sql = "SELECT userid, name FROM framer_registration";
$result = $con->query($sql);
$users = [];
while ($row = $result->fetch_assoc()) {
    $users[] = $row;
}

$payment_successful = false;
$payment_details = []; // Store payment details for display

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['selected_user'], $_POST['card_number'], $_POST['cvv'], $_POST['expiration_date'], $_POST['address'], $_POST['phone_number'], $_POST['amount'])) {
    // Handle payment form submission
    $selected_user = $_POST['selected_user'];
    $card_number = $_POST['card_number'];
    $cvv = $_POST['cvv'];
    $expiration_date = $_POST['expiration_date'];
    $address = $_POST['address'];
    $phone_number = $_POST['phone_number'];
    $amount = floatval($_POST['amount']);
    $quantity = 1; // Assuming a default quantity of 1
    
    // Ensure all required fields are filled
    if (empty($selected_user) || empty($card_number) || empty($cvv) || empty($expiration_date) || empty($address) || empty($phone_number) || empty($amount)) {
        echo "<script>alert('Please fill in all payment details.');</script>";
    } else {
        // Hash CVV
        $hashed_cvv = hash('sha256', $cvv);
        
        // Insert payment information into the database
        $sql = "INSERT INTO farmers_payment (userid, quantity, card_number, cvv, expiration_date, address, phone_number, amount) 
                VALUES (?, ?, ?, ?, ?, ?, ?, ?)";
        $stmt = $con->prepare($sql);
        $stmt->bind_param("iisssssd", $selected_user, $quantity, $card_number, $hashed_cvv, $expiration_date, $address, $phone_number, $amount);
        $stmt->execute();
        $stmt->close();
        
        $payment_successful = true;
    }
}

// Fetch all payment records for display
$sql = "SELECT * FROM farmers_payment";
$result = $con->query($sql);
$all_payments = [];
while ($row = $result->fetch_assoc()) {
    $all_payments[] = $row;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Payment</title>
    <style>
        body {
    font-family: Arial, sans-serif;
    margin: 0;
    padding: 0;
    background-color: #f4f4f4;
}


h2 {
    color: green;
    border-bottom: 2px solid #2d8f2d;
    padding-bottom: 10px;
    margin-bottom: 20px;
    text-align: center;
}

form {
    display: grid;
    gap: 15px;
    max-width: 600px;
    margin: 0 auto;
    padding: 20px;
    background-color: white;
    border-radius: 8px;
    box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
}

.form-group {
    display: flex;
    flex-direction: column;
}

.form-group label {
    margin-bottom: 5px;
    font-weight: bold;
}

.form-group input,
.form-group select {
    padding: 10px;
    border: 1px solid #ddd;
    border-radius: 5px;
    font-size: 1em;
}

button {
    background-color: #2d8f2d;
    color: white;
    border: none;
    padding: 10px 20px;
    border-radius: 5px;
    cursor: pointer;
    font-size: 1em;
    font-weight: bold;
    margin-top: 10px;
}

button:hover {
    background-color: #1b6b1b;
}

.success-message {
    background-color: #d4edda;
    color: #155724;
    padding: 15px;
    border: 1px solid #c3e6cb;
    border-radius: 5px;
    margin-bottom: 20px;
    text-align: center;
}

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
    background-color: #2d8f2d;
    color: white;
    text-transform: uppercase;
    letter-spacing: 0.05em;
}

tr:nth-child(even) {
    background-color: #f9f9f9;
}

tr:hover {
    background-color: #f1f1f1;
}

td {
    font-size: 0.9em;
    color: #333;
}

    </style>
</head>
<body>
    <?php include('header.php'); ?>

    <h2>Payment Details</h2>

    <form action="payselers.php" method="post">
        <div class="form-group">
            <label for="selected_user">Select User:</label>
            <select id="selected_user" name="selected_user" required>
                <option value="">Select a user</option>
                <?php foreach ($users as $user): ?>
                    <option value="<?= htmlspecialchars($user['userid']) ?>"><?= htmlspecialchars($user['name']) ?></option>
                <?php endforeach; ?>
            </select>
        </div>

        <div class="form-group">
            <label for="amount">Payment Amount:</label>
            <input type="number" id="amount" name="amount" placeholder="Enter amount" step="0.01" required>
        </div>

        <div class="form-group">
            <label for="card_number">Enter your card number:</label>
            <input type="text" id="card_number" name="card_number" placeholder="1234 5678 9012 3456" maxlength="16" minlength="16" oninput="validateCVVInput(event)">
        </div>

        <div class="form-group">
            <label for="cvv">CVV:</label>
            <input type="text" id="cvv" name="cvv" placeholder="123" maxlength="3" minlength="3" oninput="validateCVVInput(event)">
        </div>

        <div class="form-group">
            <label for="expiration_date">Expiration Date:</label>
            <input type="date" id="expiration_date" name="expiration_date">
        </div>

        <div class="form-group">
            <label for="address">Address:</label>
            <input type="text" id="address" name="address" placeholder="123 Street, City, Country" required>
        </div>

        <div class="form-group">
            <label for="phone_number">Phone Number:</label>
            <input type="text" id="phone_number" name="phone_number" placeholder="123-456-7890" required maxlength="10" minlength="10">
        </div>

        <button type="submit">Pay</button>
    </form>

    <?php if ($payment_successful): ?>
        <div class="success-message">
            <h3>Payment Successful</h3>
            <p>Your payment was processed successfully.</p>
        </div>
    <?php endif; ?>

    <h3>All Payments</h3>
    <table>
        <thead>
            <tr>
                <th>Payment ID</th>
                <th>User ID</th>
                <th>Quantity</th>
                <th>Card Number</th>
                <th>CVV</th>
                <th>Expiration Date</th>
                <th>Address</th>
                <th>Phone Number</th>
                <th>Amount</th>
                <th>Date Created</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($all_payments as $payment): ?>
                <tr>
                    <td><?= htmlspecialchars($payment['payment_id']) ?></td>
                    <td><?= htmlspecialchars($payment['userid']) ?></td>
                    <td><?= htmlspecialchars($payment['quantity']) ?></td>
                    <td><?= htmlspecialchars($payment['card_number']) ?></td>
                    <td><?= htmlspecialchars($payment['cvv']) ?></td>
                    <td><?= htmlspecialchars($payment['expiration_date']) ?></td>
                    <td><?= htmlspecialchars($payment['address']) ?></td>
                    <td><?= htmlspecialchars($payment['phone_number']) ?></td>
                    <td><?= htmlspecialchars($payment['amount']) ?></td>
                    <td><?= htmlspecialchars($payment['created_at']) ?></td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</body>
</html>
