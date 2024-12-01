<!-- register_framer.php -->
<?php 
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Include database connection
include('connect.php');

// Handle form submission
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $name = $_POST['f_name'];
    $email = $_POST['email'];
    $phone_number = $_POST['phone_number'];
    $business_name = $_POST['b_name'];
    $password = $_POST['password'];
    $confirm_password = $_POST['confirm_password'];
    $terms = isset($_POST['terms']);

    // Basic validation
    if ($password !== $confirm_password) {
        echo "<script>alert('Passwords do not match.');</script>";
    } elseif (!$terms) {
        echo "<script>alert('You must agree to the terms and conditions.');</script>";
    } else {
        // Check if the email already exists in the database
        $email_check_query = "SELECT * FROM framer_registration WHERE email = ?";
        $stmt_check = $con->prepare($email_check_query);
        $stmt_check->bind_param("s", $email);
        $stmt_check->execute();
        $result_check = $stmt_check->get_result();

        if ($result_check->num_rows > 0) {
            // Email is already registered
            echo "<script>alert('This email is already registered. Please use a different email.');</script>";
        } else {
            // Insert user into database
            $stmt = $con->prepare("INSERT INTO framer_registration (name, email, phone_number, business_name, password, roteype) VALUES (?, ?, ?, ?, ?, ?)");
            $stmt->bind_param("sssssi", $name, $email, $phone_number, $business_name, $password, $roteype);

            // Set default roteype value
            $roteype = 4;

            if ($stmt->execute()) {
                echo "<script>alert('Registration successful.'); window.location.href='login.php';</script>";
            } else {
                echo "<script>alert('Registration failed. Please try again.');</script>";
            }

            $stmt->close();
        }

        $stmt_check->close();
    }
}

$con->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="./vendor_registration.css">
    <title>Framer Registration</title>
</head>
<body>
    <div class="container">
        <div class="form">
            <h1>Framer Registration</h1>

            <form action="register_framer.php" method="POST">
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

                <Button type="submit" value="Register">Register</Button>
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
                By becoming a framer on Microgreens, you agree to the following:
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
<style>

/* Button Styling */
button {
    background-color: #0F2429; /* Dark background color */
    color: white; /* White text color */
    padding: 10px; /* Padding around the text */
    border: none; /* No border */
    border-radius: 4px; /* Rounded corners */
    cursor: pointer; /* Pointer cursor to indicate it's clickable */
    width: 100%; /* Full width */
    font-size: 16px; /* Font size */
    font-weight: 600; /* Bold text */
    transition: background-color 0.3s ease, color 0.3s ease, letter-spacing 0.3s ease; /* Smooth transitions */
    text-align: center; /* Center text */
}

/* Hover state for button */
button:hover {
    background-color:#1A3942;/* Slightly lighter background color on hover */
    color: #32CD32; /* Light green text color on hover */
    letter-spacing: 1px; /* Slightly increased letter spacing on hover */
    text-decoration: underline; /* Underline text on hover */
}

    </style>