<?php
include('connect.php');
// session_start();

// Redirect if not logged in
if (!isset($_SESSION['uid'])) {
    header("Location: login.php");
    exit();
}

// Initialize variables
$microid = isset($_POST['microid']) ? intval($_POST['microid']) : 0;
$quantity = isset($_POST['quantity']) ? intval($_POST['quantity']) : 1; // Default to 1 if not set
$price = 0;
$total_amount = 0;

// Fetch microgreen price from the database
if ($microid > 0) {
    $sql = "SELECT price FROM micro WHERE microid = ?";
    $stmt = $con->prepare($sql);
    $stmt->bind_param("i", $microid);
    $stmt->execute();
    $result = $stmt->get_result();
    
    if ($data = $result->fetch_assoc()) {
        $price = $data['price'];
        $total_amount = $quantity * $price;
    } else {
        echo "Microgreen not found.";
        exit();
    }
} else {
    echo "Invalid microgreen ID.";
    exit();
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['address'], $_POST['phone_number'])) {
    // Handle cash on delivery form submission
    $address = $_POST['address'];
    $phone_number = $_POST['phone_number'];

    if (empty($address) || empty($phone_number)) {
        echo "Please fill in all delivery details.";
        exit();
    }

    // Insert payment information into the database
    $user_id = $_SESSION['uid']; // User ID from the session
    $sql = "INSERT INTO payments (user_id, microid, quantity, address, phone_number, amount, payment_status) 
            VALUES (?, ?, ?, ?, ?, ?, 'Pending')";
    $stmt = $con->prepare($sql);
    $stmt->bind_param("iiissd", $user_id, $microid, $quantity, $address, $phone_number, $total_amount);
    $stmt->execute();

    // Confirm the order
    header("Location: confirmation.php"); // Redirect to confirmation page
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cash on Delivery</title>
</head>
<body>
    <div class="container">
        <h2>Cash on Delivery</h2>
        <p class="note">Delivery will be exactly in 2 days after paying.</p>
        <p id="totalAmount">Your total amount is: <?= htmlspecialchars($quantity) ?> * <?= htmlspecialchars($price) ?> = <?= htmlspecialchars($total_amount) ?></p>

        <form action="cod_payment.php" method="post">
            <input type="hidden" name="microid" value="<?= htmlspecialchars($microid) ?>">
            <input type="hidden" name="quantity" value="<?= htmlspecialchars($quantity) ?>">

            <div class="form-group">
                <label for="address">Address:</label>
                <input type="text" id="address" name="address" placeholder="123 Street, City, Country" required>
            </div>

            <div class="form-group">
                <label for="phone_number">Phone Number:</label>
                <input type="text" id="phone_number" name="phone_number" placeholder="123-456-7890" required>
            </div>

            <button type="submit" class="btn-primary">Confirm Order</button>
        </form>
    </div>
</body>
<?php include('footer.php') ?>
<?php include('chat.php') ?>
<?php include('social.php') ?>
</html>
