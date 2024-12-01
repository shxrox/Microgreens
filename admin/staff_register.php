<?php


// Include your database connection
include('../connect.php');

$message = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Get form data
    $name = $_POST['name'];
    $email = $_POST['email'];
    $password = $_POST['password'];

    // Prepare SQL statement
    $stmt = $con->prepare("INSERT INTO users (name, email, password, roteype) VALUES (?, ?, ?, ?)");
    $stmt->bind_param("sssi", $name, $email, $password, $roteype);

    // Set roetype to 3
    $roteype = 3;

    // Execute and check for errors
    if ($stmt->execute()) {
        $message = "Registration successful!";
    } else {
        $message = "Error: " . $stmt->error;
    }

    // Close the statement and connection
    $stmt->close();
    $con->close();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Staff Registration</title>
    <style>
        body {
            font-family: 'Arial', sans-serif;
            background-color: #f4f4f4;
            color: #333;
            margin: 0;
            padding: 0;
        }

        .staff-container {
            max-width: 600px;
            margin: 40px auto;
            padding: 20px;
            background-color: #ffffff;
            border-radius: 8px;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);
        }

        h2 {
            color: #2d8f2d;
            font-size: 2em;
            margin-bottom: 20px;
            text-align: center;
        }

        .form-group {
            margin-bottom: 20px;
        }

        label {
            display: block;
            font-weight: bold;
            margin-bottom: 8px;
            color: #555;
        }

        input[type="text"],
        input[type="email"],
        input[type="password"] {
            width: 97%;
            padding: 12px;
            border-radius: 8px;
            border: 1px solid #ccc;
            background-color: #f9f9f9;
            font-size: 1em;
            color: #333;
        }

        button[type="submit"] {
            background-color: #28a745;
            color: white;
            padding: 12px 20px;
            border: none;
            border-radius: 8px;
            cursor: pointer;
            font-size: 1.1em;
            transition: background-color 0.3s ease, transform 0.2s ease;
            width: 100%;
        }

        button[type="submit"]:hover {
            background-color: #218838;
            transform: scale(1.05);
        }

        .message {
            margin-top: 20px;
            padding: 10px;
            border-radius: 8px;
            background-color: #f0f0f0;
            color: #333;
            text-align: center;
            font-size: 1.1em;
        }
    </style>
</head>
<body>
<?php include('header.php'); ?>
    <div class="staff-container">
        <h2>Staff Registration Form</h2>
        <form action="staff_register.php" method="post">
            <div class="form-group">
                <label for="name">Name:</label>
                <input type="text" id="name" name="name" required>
            </div>
            <div class="form-group">
                <label for="email">Email:</label>
                <input type="email" id="email" name="email" required>
            </div>
            <div class="form-group">
                <label for="password">Password:</label>
                <input type="password" id="password" name="password" required>
            </div>
            <div class="form-group">
                <button type="submit">Register</button>
            </div>
        </form>
        <div class="message">
            <?php echo htmlspecialchars($message); ?>
        </div>
    </div>
</body>
</html>
