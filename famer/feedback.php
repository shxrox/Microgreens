<?php

include('connect.php');

$message = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_SESSION['uid'])) {
        $user_id = $_SESSION['uid'];
        $feedback = $_POST['feedback'];

        // Prepare SQL statement
        $stmt = $con->prepare("INSERT INTO feedback (user_id, feedback) VALUES (?, ?)");
        $stmt->bind_param("is", $user_id, $feedback);

        if ($stmt->execute()) {
            $message = "Thank you for your feedback!";
        } else {
            $message = "Error: " . $stmt->error;
        }

        $stmt->close();
    } else {
        $message = "You need to be logged in to submit feedback.";
    }
}

$con->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Feedback</title>
</head>
<style>
    /* Feedback Container Styles */
.feedback-container {
    max-width: 600px;
    margin: 81px;
    margin-left: 450px;
    padding: 20px;
    background-color: #f9f9f9; /* Light background color for the container */
    border-radius: 8px;
    box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
}

.feedback-container h2 {
    color: #333; /* Dark text color for the header */
    margin-bottom: 20px;
    font-size: 24px;
    text-align: center;
}

.feedback-form {
    display: flex;
    flex-direction: column;
}

.feedback-form .form-group {
    margin-bottom: 15px;
}

.feedback-form label {
    display: block;
    font-weight: bold;
    margin-bottom: 5px;
    color: #333;
}

.feedback-form textarea {
    width: 100%;
    height: 100px;
    border: 1px solid #ccc;
    border-radius: 5px;
    font-size: 16px;
    resize: vertical;
}

.feedback-form .submit-btn {
    background-color: #0F2429; /* Base color */
    color: #fff;
    border: none;
    padding: 12px 40px; /* Adjusted padding to match button styles */
    font-size: 18px; /* Larger font size for prominence */
    border-radius: 4px; /* Rounded corners for consistency */
    cursor: pointer;
    transition: background-color 0.3s ease, transform 0.3s ease, box-shadow 0.3s ease; /* Consistent transitions */
    text-align: center;
    box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1); /* Subtle shadow */
}

/* Hover effect for feedback form submit button */
.feedback-form .submit-btn:hover {
    background-color: #1A3942; /* Darker background color on hover */
    color: #32CD32; /* Change text color on hover for contrast */
    letter-spacing: 1px; /* Adjusted spacing for hover effect */
    transform: scale(1.08); /* Slightly larger scale on hover */
    box-shadow: 0 6px 12px rgba(0, 0, 0, 0.2); /* More pronounced shadow on hover */
}


.feedback-message {
    margin-top: 20px;
    padding: 10px;
    border: 1px solid #ccc;
    border-radius: 5px;
    background-color: #e9f5e9; /* Light green background for the message */
    color: #333;
    text-align: center;
    font-size: 16px;
}
</style>
<body>
<?php include('header.php') ?>
<div class="feedback-container">
    <h2>Submit Feedback</h2>
    <form action="feedback.php" method="post" class="feedback-form">
        <div class="form-group">
            <label for="feedback">Your Feedback:</label>
            <textarea id="feedback" name="feedback" required></textarea>
        </div>
        <div class="form-group">
            <button type="submit" class="submit-btn">Submit Feedback</button>
        </div>
    </form>
    <div class="feedback-message">
        <?php echo htmlspecialchars($message); ?>
    </div>
</div>
<?php include('footer.php') ?>
<?php include('chat.php') ?>
<?php include('weather.php') ?>
<?php include('social.php'); ?>
</body>
</html>
