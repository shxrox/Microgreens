<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Footer Example</title>
    <style>
        /* Import Google Font */
       

        footer {font-family: 'Lato', sans-serif; /* Ensure the font family is applied */
    font-weight: 100; /* Regular weight */
    font-size: 1rem; /* Adjust the size to your preference */
    
    letter-spacing: 0.1rem; /* Slight letter spacing for clarity */
    text-transform: uppercase; /* Uppercase text for emphasis */
    text-align: center; /* Center the text */
    margin: 0; /* Remove default margin */


            font-family: 'Lato', sans-serif;
            margin: 0;
            padding: 0;
        }

        #footer {
            background-color: #0F2429;
            color: #ecf0f1;
            padding: 30px 0;
            text-align: center;
        }

        .footer-details {
            display: flex;
            justify-content: space-around;
            flex-wrap: wrap;
        }

        .footer-left,
        .footer-middle,
        .footer-right {
            flex: 1;
            margin: 0 20px;
        }

        .footer-left h4,
        .footer-middle h4,
        .footer-right h4 {
            margin-bottom: 10px;
            font-size: 16px;
            font-weight: 600;
        }

        .footer-left p,
        .footer-middle ul li,
        .footer-right p {
            font-size: 14px;
        }

        .footer-middle ul {
            list-style: none;
            padding: 0;
        }

        .footer-middle ul li {
            margin: 5px 0;
        }

        .footer-middle ul li a {
            color: #ecf0f1;
            text-decoration: none;
            transition: color 0.3s;
        }

        .footer-middle ul li a:hover {
            color:  #32cd32;
        }

        .payment-icon {
            width: 40px;
            margin: 0 10px;
        }

        .social-icon img {
            width: 25px;
            margin: 0 10px;
            vertical-align: middle;
        }

        .social-icon {
            color: #ecf0f1;
            text-decoration: none;
            transition: color 0.3s;
        }

        .social-icon:hover {
            color: #32cd32;
        }

        .copyright {
            margin-top: 20px;
            font-size: 14px;
            color: #bdc3c7;
        }
    </style>
</head>
<body>
    <!-- Your main content here -->

    <footer id="footer">
        <div class="container">
            <div class="footer-details">
                <div class="footer-left">
                    <h4>ADDRESS:</h4>
                    <p>123 microgreen,Colombo, Sri lankan</p>
                    <h4>PHONE:</h4>
                    <p>(123) 456-7890</p>
                    <h4>EMAIL:</h4>
                    <p>Microgreen.com</p>
                    <h4>WORKING DAYS/HOURS:</h4>
                    <p>Mon - Sun / 8:00AM - 6:00PM</p>
                </div>

                <div class="footer-middle">
                    <h4>FAQ</h4>
                    <ul>
                        <li><a href="./about.php">About Us</a></li>
                        <li><a href="./footerpages/contact_us.php">Contact Us</a></li>
                        <li><a href="./viewprofile.php">My Account</a></li>
                        <li><a href="./viewprofile.php">Order History</a></li>
                        <li><a href="./footerpages/faq.php">FAQ</a></li>
                    </ul>
                </div>

                <div class="footer-right">
                    <h4>PAYMENTS</h4>
                    <p>
                        <img src="./images/visa1.png" alt="Visa" class="payment-icon">
                        <img src="./images/card1.png" alt="MasterCard" class="payment-icon">
                        <!-- Add more payment icons as needed -->
                    </p>
                    <h4>SOCIAL MEDIA</h4>
                    <p>
                        <a href="#" class="social-icon"><img src="./images/facebook1.png" alt="Facebook"></a>
                        <a href="#" class="social-icon"><img src="./images/twitter1.png" alt="Twitter"></a>
                        <a href="#" class="social-icon"><img src="./images/ig2.png" alt="Instagram"></a>
                        <!-- Add more social media icons as needed -->
                    </p>
                </div>
            </div>

            <div class="copyright">
                <?php $year = date('Y'); ?>
                © Copyright <?= $year ?>. All rights reserved. <strong><span>Microgreens</span></strong>
            </div>
        </div>
    </footer>
</body>
</html>
