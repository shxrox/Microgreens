<?php 
    error_reporting(E_ALL);
    ini_set('display_errors', 1);    
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="./vendor_registration.css">
    <title>Vendor Registration</title>
</head>
<body>
    <div class="container">
        <div class="form">
            <h1>Vendor Registration</h1>

            <form action="./ven_reg.php" method="POST">
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
        <script>
        function togglePopup() {
            document.getElementById("popup-1").classList.toggle("active");
        }
    </script>
    </div>
</body>
</html>

