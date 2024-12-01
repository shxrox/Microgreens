<?php 
    error_reporting(E_ALL);
    ini_set('display_errors', 1);

    // Include database connection
    include('connect.php');

    // Flag for showing notification
    $showNotification = false;

    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        // Retrieve form data
        $full_name = $conn->real_escape_string($_POST['f_name']);
        $email = $conn->real_escape_string($_POST['email']);
        $phone_number = $conn->real_escape_string($_POST['phone_number']);
        $business_name = $conn->real_escape_string($_POST['b_name']);
        $password = $conn->real_escape_string($_POST['password']);
        // Hash the password
        $hashed_password = password_hash($password, PASSWORD_BCRYPT);

        // Insert data into the pending_vendors table
        $sql = "INSERT INTO pending_vendors (full_name, email, phone_number, business_name, password)
                VALUES ('$full_name', '$email', '$phone_number', '$business_name', '$hashed_password')";

        if ($conn->query($sql) === TRUE) {
            // Set the flag to show notification
            $showNotification = true;
        } else {
            echo "Error: " . $sql . "<br>" . $conn->error;
        }
        
        // Close the database connection
        $conn->close();
    }
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="./vendor_registration.css">
    <title>Vendor Registration</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 20px;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            background-color: #f4f4f4;
        }
        .container {
            max-width: 600px;
            width: 100%;
        }
        .form {
            background: #fff;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        }
        .notification {
            display: none;
            position: fixed;
            bottom: 20px;
            right: 20px;
            padding: 15px;
            background-color: #333;
            color: #fff;
            border-radius: 5px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
            animation: fadeInOut 4s ease-in-out;
        }
        @keyframes fadeInOut {
            0% { opacity: 0; transform: translateY(20px); }
            20% { opacity: 1; transform: translateY(0); }
            80% { opacity: 1; transform: translateY(0); }
            100% { opacity: 0; transform: translateY(20px); }
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="form">
            <h1>Vendor Registration</h1>

            <form action="" method="POST">
                <label for="f_name">Enter Your Full Name</label>
                <input type="text" id="f_name" name="f_name" required>

                <label for="email">Enter Your Email</label>
                <input type="email" id="email" name="email" required>

                <label for="phone_number">Enter Your Phone Number</label>
                <input type="text" id="phone_number" name="phone_number" required>

                <label for="b_name">Enter Your Business Name</label>
                <input type="text" id="b_name" name="b_name" required>

                <label for="password">Enter Password</label>
                <input type="password" id="password" name="password" required>

                <label for="confirm_password">Confirm Your Password</label>
                <input type="password" id="confirm_password" name="confirm_password" required>

                <p>Please agree to <a href="#" onclick="togglePopup(); return false;">terms and conditions</a>
                <input type="checkbox" name="terms" class="tnc" required></p>

                <input type="submit" value="Register">
            </form>
        </div>
    </div>

    <!-- Popup -->
    <div id="popup-1" class="popup">
        <div class="overlay" onclick="togglePopup()"></div>
        <div class="content">
            <div class="close-btn" onclick="togglePopup()">&times;</div>
            <h1>Terms and Conditions</h1>

            <p>
                By becoming a vendor on Microgreens, you agree to the following:
                <ul class="cons">
                    <li><b>Eligibility:</b> You must be legally able to enter into a contract and provide accurate information.</li>
                    <li><b>Products:</b> List only microgreens-related products and ensure accuracy.</li>
                    <li><b>Orders:</b> Ship orders promptly and notify us if you can’t fulfill them.</li>
                    <li><b>Payments:</b> We handle payments and deduct any fees. You are responsible for taxes.</li>
                    <li><b>Responsibilities:</b> Provide good customer service and handle returns/refunds as per your policy.</li>
                    <li><b>Prohibited Items:</b> Do not sell illegal or counterfeit items.</li>
                    <li><b>Intellectual Property:</b> Ensure you have the right to sell the products and that they don’t infringe on others’ rights.</li>
                    <li><b>Termination:</b> We can close your account if you violate terms. You can end your account by notifying us but must complete pending orders.</li>
                    <li><b>Liability:</b> We are not liable for any losses or damages related to our platform.</li>
                    <li><b>Changes:</b> We may update these terms and will notify you of significant changes.</li>
                    <li><b>Law:</b> These terms are governed by the laws of [Your Jurisdiction].</li>
                </ul>
            </p>
        </div>
    </div>

    <!-- Notification element -->
    <div id="notification" class="notification">
        Admin will check your information and approve you within 24 hours.
    </div>

    <script>
        function togglePopup() {
            document.getElementById("popup-1").classList.toggle("active");
        }

        // Show the notification if the flag is set
        <?php if ($showNotification): ?>
            document.addEventListener('DOMContentLoaded', function() {
                var notification = document.getElementById('notification');
                notification.style.display = 'block';
                setTimeout(function() {
                    notification.style.display = 'none';
                }, 4000);
            });
        <?php endif; ?>
    </script>
</body>
</html>
