<?php
// Include your database connection
include('connect.php');

// Start the session if it hasn't been started already
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

// Enable error reporting
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

$message = '';

// Check if the user is logged in
if (!isset($_SESSION['uid'])) {
    $message = 'You need to be logged in to add items to the cart.';
} else {
    // Process form submission
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        // Get data from form
        $user_id = $_SESSION['uid'];
        $microid = $_POST['microid'];
        $quantity = $_POST['quantity'];

        // Check if item already exists in cart
        $stmt = $con->prepare("SELECT quantity FROM cart WHERE user_id = ? AND microid = ?");
        $stmt->bind_param("ii", $user_id, $microid);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows > 0) {
            // Update quantity if item exists
            $stmt = $con->prepare("UPDATE cart SET quantity = quantity + ? WHERE user_id = ? AND microid = ?");
            $stmt->bind_param("iii", $quantity, $user_id, $microid);
        } else {
            // Insert new item into cart
            $stmt = $con->prepare("INSERT INTO cart (user_id, microid, quantity) VALUES (?, ?, ?)");
            $stmt->bind_param("iii", $user_id, $microid, $quantity);
        }

        if ($stmt->execute()) {
            $message = "Item added to cart!";
        } else {
            error_log("MySQL Error: " . $stmt->error);
            $message = "Error: " . $stmt->error;
        }

        $stmt->close();
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cart</title>
    <style>
        body { font-family: Arial, sans-serif; }
        .container { max-width: 800px; margin: 0 auto; padding: 20px; }
        .message { margin-top: 20px; text-align: center; color: red; }
        .cart-item { margin-bottom: 20px; border-bottom: 1px solid #ddd; padding-bottom: 10px; }
    </style>
</head>
<body>
<?php include('header.php'); ?>
<div class="container">
    <h2>Your Cart</h2>
    <?php if ($message): ?>
        <div class="message"><?php echo htmlspecialchars($message); ?></div>
    <?php endif; ?>

    <?php
    if (isset($_SESSION['uid'])) {
        $user_id = $_SESSION['uid'];
        
        // Query to display cart items
        $stmt = $con->prepare("SELECT micro.*, cart.quantity FROM cart INNER JOIN micro ON cart.microid = micro.microid WHERE cart.user_id = ?");
        $stmt->bind_param("i", $user_id);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows > 0) {
            while ($item = $result->fetch_assoc()) {
                echo '<div class="cart-item">';
                echo '<h3>' . htmlspecialchars($item['title']) . '</h3>';
                echo '<p>Price: $' . htmlspecialchars($item['price']) . '</p>';
                echo '<p>Quantity: ' . htmlspecialchars($item['quantity']) . '</p>';
                echo '<img src="./admin/uploads/images/' . htmlspecialchars($item['image']) . '" alt="' . htmlspecialchars($item['title']) . '" style="width: 150px;">';
                echo '</div>';
            }
        } else {
            echo '<p>Your cart is empty.</p>';
        }

        $stmt->close();
    } else {
        echo '<p>You need to be logged in to view your cart.</p>';
    }
    ?>
</div>
<?php include('footer.php'); ?>
<?php include('chat.php'); ?>
</body>
</html>

<?php
// Close the connection at the very end of the script
$con->close();
?>
