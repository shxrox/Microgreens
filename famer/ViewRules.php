<?php
include('../connect.php');

// Check if session is already started
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Initialize variables
$data = [];

// Retrieve data from the database
$sql = "SELECT * FROM `rules&regulations` ORDER BY `update_date` DESC"; // Fetch all rules, ordered by update_date
$result = mysqli_query($con, $sql);

if ($result) {
    while ($row = mysqli_fetch_assoc($result)) {
        $data[] = $row; // Collect all rows in an array
    }
} else {
    echo "Error: " . mysqli_error($con);
}

// Close the connection
mysqli_close($con);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>View Rules</title>
    <style>
body {
    font-family: 'Lato', sans-serif;
    background-color: #f4f4f4;
    margin: 0;
    padding: 0;
}

.container {
    max-width: 800px;
    margin: 50px auto;
    padding: 20px;
    background-color: #ffffff;
    border-radius: 8px;
    box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
}

h1 {
    text-align: center;
    color: #0F2429;
    margin-bottom: 20px;
}

.card {
    background-color: #ffffff;
    border-radius: 8px;
    box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
    margin-bottom: 20px;
    padding: 20px;
}

.card h2 {
    color: #2d8f2d;
    margin: 0 0 10px;
}

.card p {
    margin: 0 0 10px;
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
</head>
<body>
    <?php include('header.php'); ?>

    <div class="container">
        <h1>Rules & Regulations</h1>

        <?php if (empty($data)): ?>
            <div class="alert">
                No rules found.
            </div>
        <?php else: ?>
            <?php foreach ($data as $row): ?>
                <div class="card">
            
                    <p> <?php echo htmlspecialchars($row['rule'], ENT_QUOTES, 'UTF-8'); ?></p>
                    <p> <?php echo htmlspecialchars($row['update_date'], ENT_QUOTES, 'UTF-8'); ?></p>
                </div>
            <?php endforeach; ?>
        <?php endif; ?>
    </div>

    <?php include('footer.php') ?>
</body>
</html>