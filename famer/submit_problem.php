<?php
include('../connect.php');

// Check if session is already started
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Initialize variables
$submission_status = "";

// Check if the form is submitted
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $user_id = $_SESSION['uid']; // Assuming user ID is stored in session
    $problem_text = mysqli_real_escape_string($con, $_POST['problem_text']);
    $additional_option = mysqli_real_escape_string($con, $_POST['additional_option']);

    // Handle image upload
    $image = null;
    if (!empty($_FILES['image']['name'])) {
        $targetDir = "uploads/problems/";
        $imageName = time() . "_" . basename($_FILES['image']['name']);
        $targetFilePath = $targetDir . $imageName;

        // Ensure the target directory exists
        if (!is_dir($targetDir)) {
            mkdir($targetDir, 0755, true);
        }

        // Move uploaded file to the target directory
        if (move_uploaded_file($_FILES['image']['tmp_name'], $targetFilePath)) {
            $image = $imageName;
        } else {
            $submission_status = "Error uploading the image.";
        }
    }

    // Insert problem details into the database
    $sql = "INSERT INTO problems (user_id, problem_text, image, additional_option) VALUES ('$user_id', '$problem_text', '$image', '$additional_option')";
    if (mysqli_query($con, $sql)) {
        $submission_status = "Problem submitted successfully.";
    } else {
        $submission_status = "Error: " . mysqli_error($con);
    }

    // Close the connection
    mysqli_close($con);
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Submit Problem</title>
    <style>
        body {
            font-family: 'Lato', sans-serif; /* Ensure the font family is applied */
    font-weight: 700; /* Regular weight */
    font-size: 1rem; /* Adjust the size to your preference */
   
    letter-spacing: 0.1rem; /* Slight letter spacing for clarity */
            background-color: #f4f4f4;
            margin: 0;
            padding: 0;
        }

        .container {
            max-width: 600px;
            margin: 50px auto;
            padding: 20px;
            background-color: #ffffff;
            border-radius: 8px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        }

        h1 {
            text-align: center;
            color:#0f2429;
            margin-bottom: 20px;
        }

        label {
            display: block;
            margin-bottom: 8px;
            font-weight: bold;
            color: #333;
        }

        textarea, input[type="text"], input[type="file"] {
            width: 100%;
            padding: 10px;
            margin-bottom: 20px;
            border: 1px solid #ccc;
            border-radius: 4px;
            font-size: 16px;
            box-sizing: border-box;
        }

        button {
    width: 100%; /* Full-width button */
    padding: 12px 40px; /* Consistent padding with other buttons */
    background-color: #0F2429; /* Base background color */
    color: #fff;
    border: none;
    border-radius: 4px; /* Rounded corners */
    font-size: 18px; /* Larger font size for prominence */
    cursor: pointer;
    transition: background-color 0.3s ease, transform 0.3s ease, box-shadow 0.3s ease; /* Smooth transition effects */
    box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1); /* Subtle shadow */
}

/* Hover effect for button */
button:hover {
    background-color: #1A3942; /* Darker background color on hover */
    color: #32CD32; /* Change text color on hover */
    letter-spacing: 1px; /* Adjusted spacing on hover */
    transform: scale(1.08); /* Slightly enlarged scale on hover */
    box-shadow: 0 6px 12px rgba(0, 0, 0, 0.2); /* More pronounced shadow on hover */
}


        .alert {
            text-align: center;
            padding: 10px;
            background-color: #f8d7da;
            color: #721c24;
            border: 1px solid #f5c6cb;
            border-radius: 4px;
            margin-bottom: 20px;
        }
    </style>
    <script>
        function showAlert(message) {
            alert(message);
            window.location.href = 'submit_problem.php'; // Redirect to refresh the page after showing the alert
        }
    </script>
</head>
<body>
    <?php include('header.php'); ?>

    <div class="container">
        <h1>Submit Your Problem</h1>

        <?php if ($submission_status): ?>
            <div class="alert">
                <script>
                    showAlert("<?php echo htmlspecialchars($submission_status, ENT_QUOTES, 'UTF-8'); ?>");
                </script>
            </div>
        <?php endif; ?>

        <form action="submit_problem.php" method="post" enctype="multipart/form-data">
            <label for="problem_text">Problem Description:</label>
            <textarea id="problem_text" name="problem_text" rows="5" required></textarea>

            <label for="image">Upload Image (optional):</label>
            <input type="file" id="image" name="image" accept="image/*">

            <label for="additional_option">Additional Option:</label>
            <input type="text" id="additional_option" name="additional_option">

            <button type="submit">Submit Problem</button>
        </form>
    </div>

    <?php include('footer.php') ?>
    <?php include('chat.php') ?>
<?php include('weather.php') ?>
<?php include('social.php'); ?>
</body>
</html>