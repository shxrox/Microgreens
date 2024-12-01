<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Social Media Contact</title>
    <style>
        body {
            margin: 0;
            padding: 0;
            font-family: Arial, sans-serif;
        }

        .contact-container {
            position: fixed;
            bottom: 20px;
            left: 20px; 
            display: flex;
            flex-direction: column;
            align-items: flex-start; 
        }

        .contact-icon {
            cursor: pointer;
            position: relative; 
            z-index: 10; 
            transition: transform 0.3s ease; 
        }

        .contact-icon img {
            width: 42px; 
            border-radius: 30%;
            background: #4CAF50; /* Green color */
            padding: 12px;
            box-shadow: 0 6px 12px rgba(0, 0, 0, 0.3);
            transition: 0.3s;
        }

        .contact-icon img:hover {
            width: 45px;
        }

        .social-media-icons {
            display: none;
            flex-direction: column;
            align-items: flex-start; 
            position: absolute; 
            left: 0;
            bottom: 60px; 
            margin-bottom: 20px;
            transition: opacity 0.3s ease, transform 0.3s ease; 
            opacity: 0; 
        }

        .social-media-icons.show {
            display: flex;
            opacity: 1; 
            transform: translateY(0); 
        }

        .social-media-icons a {
            margin: 8px 0;
            transition: transform 0.3s ease; 
        }

        .social-media-icons img {
            width: 45px; 
            border-radius: 50%;
            background: #4CAF50; /* Green color */
            padding: 8px;
            box-shadow: 0 6px 12px rgba(0, 0, 0, 0.3);
            transition: transform 0.3s ease, background 0.3s ease; 
        }

        .social-media-icons img:hover {
            transform: scale(1.1); 
            background: #45a049; /* Darker green for hover */
        }
    </style>
</head>
<body>

<div class="contact-container" id="contact">
    <div class="contact-icon" onclick="toggleSocialMediaIcons()">
        <img src="../images/content.png" alt="Contact Icon">
    </div>
    <div class="social-media-icons" id="socialMediaIcons">
        <a href="https://mail.google.com"><img src="../images/email.png" alt="Email"></a>
        <a href="https://www.whatsapp.com/"><img src="../images/telephone.png" alt="Phone"></a>
        <a href="https://www.instagram.com/accounts/login/"><img src="../images/instagram.png" alt="Instagram"></a>
        <a href="https://web.facebook.com/login"><img src="../images/facebook.png" alt="Facebook"></a>

        
    </div>
</div>

<script>
    function toggleSocialMediaIcons() {
        const socialMediaIcons = document.getElementById('socialMediaIcons');
        socialMediaIcons.classList.toggle('show');
    }
</script>

</body>
</html>
