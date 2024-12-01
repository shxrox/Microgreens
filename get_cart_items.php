<?php
// Include your database connection
include('connect.php');

if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

if (isset($_SESSION['uid'])) {
    $user_id = $_SESSION['uid'];

    $stmt = $con->prepare("SELECT micro.*, cart.quantity FROM cart INNER JOIN micro ON cart.microid = micro.microid WHERE cart.user_id = ?");
    $stmt->bind_param("i", $user_id);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        echo '<h3>Your Cart</h3>';
        while ($item = $result->fetch_assoc()) {
            echo '<div class="cart-item">';
            echo '<img src="./admin/uploads/images/' . htmlspecialchars($item['image']) . '" alt="' . htmlspecialchars($item['title']) . '">';
            echo '<div>';
            echo '<p>' . htmlspecialchars($item['title']) . '</p>';
            echo '<p>Quantity: ' . htmlspecialchars($item['quantity']) . '</p>';
            echo '<p>Price: $' . htmlspecialchars($item['price']) . '</p>';
            echo '</div>';
            echo '<form action="payment.php" method="post">';
            echo '<input type="hidden" name="microid" value="' . htmlspecialchars($item['microid']) . '">';
            echo '<input type="hidden" name="quantity" value="' . htmlspecialchars($item['quantity']) . '">';
            echo '<input type="submit" name="buy" value="Buy" class="buy-btn">';
            echo '</form>';
            echo '</div>';

            
        }
    } else {
        echo '<p>Your cart is empty.</p>';
    }

    $stmt->close();
} else {
    echo '<p>You need to be logged in to view your cart.</p>';
}

$con->close();
?>
