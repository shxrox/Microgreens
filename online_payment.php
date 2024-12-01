<?php
// Include database connection
include('connect.php');

// Fetch provinces and cities
$provinces = [
    'Western' => ['Colombo', 'Gampaha', 'Kalutara'],
    'Central' => ['Kandy', 'Matale', 'Nuwara Eliya'],
    'Southern' => ['Galle', 'Matara', 'Hambantota'],
    'Northern' => ['Jaffna', 'Kilinochchi', 'Mannar'],
    'Eastern' => ['Trincomalee', 'Batticaloa', 'Ampara'],
    'North Western' => ['Kurunegala', 'Puttalam'],
    'North Central' => ['Anuradhapura', 'Polonnaruwa'],
    'Uva' => ['Badulla', 'Monaragala'],
    'Sabaragamuwa' => ['Ratnapura', 'Kegalle']
];

// Example item details
$microid = 23; // Ensure this microid exists in your `micro` table
$quantity = 2; // Example quantity
$price = 2000; // Example price per unit
$total_amount = $quantity * $price;

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $payment_method = $_POST['payment_method'];
    $address = $_POST['address'];
    $phone_number = $_POST['phone_number'];
    $province = $_POST['province'] ?? null;
    $city = $_POST['city'] ?? null;

    if ($payment_method === 'online') {
        $card_number = $_POST['card_number'];
        $cvv = $_POST['cvv'];
        $expiration_date = $_POST['expiration_date'];

        if (empty($card_number) || empty($cvv) || empty($expiration_date)) {
            echo "Please fill in all payment details.";
            exit();
        }

        $sql = "INSERT INTO payments (microid, quantity, card_number, cvv, expiration_date, address, phone_number, amount, payment_status, province, city) 
                VALUES (?, ?, ?, ?, ?, ?, ?, ?, 'Pending', ?, ?)";
        $stmt = $con->prepare($sql);
        $stmt->bind_param("iisssssdss", $microid, $quantity, $card_number, $cvv, $expiration_date, $address, $phone_number, $total_amount, $province, $city);
        $stmt->execute();
    } else if ($payment_method === 'cod') {
        if (empty($province) || empty($city)) {
            echo "Please select a province and city.";
            exit();
        }

        $sql = "INSERT INTO payments (microid, quantity, address, phone_number, amount, payment_status, province, city) 
                VALUES (?, ?, ?, ?, ?, 'Pending', ?, ?)";
        $stmt = $con->prepare($sql);
        $stmt->bind_param("iissdss", $microid, $quantity, $address, $phone_number, $total_amount, $province, $city);
        $stmt->execute();
    }

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
</head>
<body>
    <div class="container">
        <h2>Payment Details</h2>
        <p class="note">Delivery will be exactly in 2 days after paying.</p>

        <p id="totalAmount">Your total amount is: <?= htmlspecialchars($quantity) ?> * <?= htmlspecialchars($price) ?> = <?= htmlspecialchars($total_amount) ?></p>

        <form action="" method="post">
            <div class="form-group">
                <label for="payment_method">Select Payment Method:</label>
                <select id="payment_method" name="payment_method">
                    <option value="online">Online Payment</option>
                    <option value="cod">Cash on Delivery</option>
                </select>
            </div>

            <div id="online_payment_details" class="payment-details" style="display: none;">
                <div class="form-group">
                    <label for="card_number">Enter your card number:</label>
                    <input type="text" id="card_number" name="card_number" placeholder="1234 5678 9012 3456">
                </div>

                <div class="form-group">
                    <label for="cvv">CVV:</label>
                    <input type="text" id="cvv" name="cvv" placeholder="123">
                </div>

                <div class="form-group">
                    <label for="expiration_date">Expiration Date:</label>
                    <input type="text" id="expiration_date" name="expiration_date" placeholder="YYYY-MM-DD">
                </div>
            </div>

            <div id="cod_details" class="payment-details" style="display: none;">
                <div class="form-group">
                    <label for="province">Province:</label>
                    <select id="province" name="province" onchange="populateCities()">
                        <option value="">Select Province</option>
                        <?php foreach ($provinces as $province => $cities): ?>
                            <option value="<?= htmlspecialchars($province) ?>"><?= htmlspecialchars($province) ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>

                <div class="form-group">
                    <label for="city">City:</label>
                    <select id="city" name="city">
                        <option value="">Select City</option>
                    </select>
                </div>

                <div class="form-group">
                    <label for="address">Address:</label>
                    <input type="text" id="address" name="address" placeholder="123 Street, City, Country">
                </div>

                <div class="form-group">
                    <label for="phone_number">Phone Number:</label>
                    <input type="text" id="phone_number" name="phone_number" placeholder="123-456-7890">
                </div>
            </div>

            <button type="submit">Submit</button>
        </form>
    </div>

    <script>
        const provinces = <?= json_encode($provinces) ?>;

        document.getElementById('payment_method').addEventListener('change', function() {
            const selectedMethod = this.value;
            document.getElementById('online_payment_details').style.display = selectedMethod === 'online' ? 'block' : 'none';
            document.getElementById('cod_details').style.display = selectedMethod === 'cod' ? 'block' : 'none';
        });

        function populateCities() {
            const province = document.getElementById('province').value;
            const cities = provinces[province] || [];
            const citySelect = document.getElementById('city');
            citySelect.innerHTML = '<option value="">Select City</option>'; // Clear previous options

            cities.forEach(city => {
                const option = document.createElement('option');
                option.value = city;
                option.textContent = city;
                citySelect.appendChild(option);
            });
        }
    </script>
</body>
</html>
