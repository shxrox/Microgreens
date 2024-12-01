<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Notification Example</title>
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

<!-- Notification element -->
<div id="notification" class="notification">
    Admin will check your information and approve you within 24 hours.
</div>

<script>
    // Show the notification
    function showNotification() {
        var notification = document.getElementById('notification');
        notification.style.display = 'block';

        // Hide the notification after a delay
        setTimeout(function() {
            notification.style.display = 'none';
        }, 4000);
    }

    // Call the function to show the notification
    showNotification();
</script>

</body>
</html>
