<?php
include('connect.php');


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

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['payment_method'])) {
    $payment_method = $_POST['payment_method'];
    $user_id = $_SESSION['uid']; // User ID from the session

    // Common variables
    $address = isset($_POST['address']) ? $_POST['address'] : null;
    $phone_number = isset($_POST['phone_number']) ? $_POST['phone_number'] : null;

    if ($payment_method === 'online') {
        // Handle online payment
        $card_number = $_POST['card_number'];
        $cvv = $_POST['cvv'];
        $expiration_date = $_POST['expiration_date'];
        $error_message = "Please fill in all payment details.";
        if (empty($card_number) || empty($cvv) || empty($expiration_date)) {
            echo "<script type='text/javascript'>alert('$error_message');</script>";
            exit();
        }

        // Hash CVV
        $hashed_cvv = hash('sha256', $cvv);

        // Insert online payment information into the database

        $sql = "INSERT INTO payments (user_id, microid, quantity, card_number, cvv, expiration_date, amount, payment_status) 
                VALUES (?, ?, ?, ?, ?, ?, ?, 'Completed')";
        $stmt = $con->prepare($sql);
        $stmt->bind_param("iiisssd", $user_id, $microid, $quantity, $card_number, $hashed_cvv, $expiration_date, $total_amount);
        $stmt->execute();
    } elseif ($payment_method === 'cod') {
        // Handle Cash on Delivery
        if (empty($address) || empty($phone_number)) {
            echo "Please fill in all delivery details.";
            exit();
        }

        // Insert COD payment information into the database
        $sql = "INSERT INTO payments (user_id, microid, quantity, address, phone_number, amount, payment_status) 
                VALUES (?, ?, ?, ?, ?, ?, 'Pending')";
        $stmt = $con->prepare($sql);
        $stmt->bind_param("iiissd", $user_id, $microid, $quantity, $address, $phone_number, $total_amount);
        $stmt->execute();
    }

    // Redirect to confirmation page after successful form submission
    header("Location: confirmation.php");
    exit();
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
            font-family: 'Lato', sans-serif; /* Ensure the font family is applied */
    font-weight: 300; /* Regular weight */
    
    
    letter-spacing: 0.1rem; /* Slight letter spacing for clarity */
    text-transform: uppercase; /* Uppercase text for emphasis */
    text-align: center; /* Center the text */
    margin: 0; /* Remove default margin */
    font-size: 0.8rem;


            background-color: #f4f4f4;
            margin: 0;
            padding: 0;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
        }

        .container {
            background: color #32cd32;
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            max-width: 400px;
            width: 100%;
        }

        h2 {
            color: #333;
            text-align: center;
            margin-bottom: 20px;
        }

        p {
            color: #555;
        }

        .form-group {
            margin-bottom: 15px;
        }

        .form-group label {
            display: block;
            color: #333;
            font-weight: bold;
            margin-bottom: 5px;
        }

        .form-group input[type="text"],
        .form-group input[type="date"] {
            width: 100%;
            padding: 10px;
            border: 1px solid #ccc;
            border-radius: 5px;
            box-sizing: border-box;
            font-size: 16px;
            color: #333;
        }

        input[type="radio"] {
            margin-right: 10px;
        }

        .btn-primary {
    background-color: #0f2429;
    color: #fff;
    padding: 10px 15px;
    border: none;
    border-radius: 5px;
    cursor: pointer;
    font-size: 16px;
    width: 100%;
    text-align: center;
    transition: background-color 0.3s ease, color 0.3s ease, letter-spacing 0.3s ease, transform 0.3s ease, box-shadow 0.3s ease;
    box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1); /* Subtle shadow */
}

.btn-primary:hover {
    background-color: #0F3729;
    color: #32CD32;
    letter-spacing: 1px; /* Adjusted spacing for subtle effect */
    transform: scale(1.05); /* Slightly increased scale for emphasis */
    box-shadow: 0 6px 12px rgba(0, 0, 0, 0.2); /* More pronounced shadow on hover */
}


        .error {
            color: red;
            font-size: 14px;
            display: none;
        }

        #totalAmount {
            font-weight: bold;
            margin-bottom: 20px;
            text-align: center;
        }
    </style>
    <script>
        function togglePaymentFields() {
            const onlinePaymentFields = document.getElementById('onlinePaymentFields');
            const codFields = document.getElementById('codFields');
            const payOnline = document.getElementById('pay_online').checked;

            if (payOnline) {
                onlinePaymentFields.style.display = 'block';
                codFields.style.display = 'none';
            } else {
                onlinePaymentFields.style.display = 'none';
                codFields.style.display = 'block';
            }
        }

        // Initialize the form visibility on page load
        window.onload = togglePaymentFields;
    </script>
</head>

<body>
    <div class="container">
        <h2>Payment Details</h2>
        <p id="totalAmount">Your total amount is: <?= htmlspecialchars($quantity) ?> * <?= htmlspecialchars($price) ?> = <?= htmlspecialchars($total_amount) ?></p>

        <form action="payment.php" method="post">
            <input type="hidden" name="microid" value="<?= htmlspecialchars($microid) ?>">
            <input type="hidden" name="quantity" value="<?= htmlspecialchars($quantity) ?>">

            <p>Please select your payment method:</p>
            <div class="form-group">
                <input type="radio" id="pay_online" name="payment_method" value="online" onchange="togglePaymentFields()" checked>
                <label for="pay_online">Pay online</label>
            </div>
            <div class="form-group">
                <input type="radio" id="cod" name="payment_method" value="cod" onchange="togglePaymentFields()">
                <label for="cod">Cash on delivery</label>
            </div>

            <!-- Online Payment Fields -->
            <div id="onlinePaymentFields">
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
            </div>

            <!-- Cash on Delivery Fields -->
            <div id="codFields" style="display:none;">
                <div class="form-group">
                    <label for="address">Address:</label>
                    <input type="text" id="address" name="address" placeholder="123 Street, City, Country">
                </div>

                <div class="form-group">
                    <label for="phone_number">Phone Number:</label>
                    <input type="text" id="phone_number" name="phone_number" placeholder="123-456-7890"  maxlength="10" minlength="10">
                </div>
            </div>

            <button type="submit" class="btn-primary">Pay</button>
        </form>
    </div>

    
    <?php include('chat.php') ?>
    <?php include('social.php') ?>
</body>

</html>
