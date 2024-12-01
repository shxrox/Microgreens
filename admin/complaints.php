<?php
include('../connect.php');

// Check if session is already started
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// // Check if user is admin
// if (!isset($_SESSION['uid']) || $_SESSION['role_type'] != 1) {
//     echo "<script> window.location.href='../login.php'; </script>";
//     exit();
// }

// Handle deleting a complaint
if (isset($_GET['deleteid'])) {
    $deleteid = mysqli_real_escape_string($con, $_GET['deleteid']);
    $sql = "DELETE FROM problems WHERE problem_id = '$deleteid'";
    mysqli_query($con, $sql);
    header("Location: complaints.php");
    exit();
}

// Fetch complaints
 $sql = "SELECT * FROM `problems`";
$res = mysqli_query($con, $sql);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>View Complaints</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
            background-color: #f4f4f4;
        }

        h1 {
            color: green;
            border-bottom: 2px solid #2d8f2d;
            padding-bottom: 10px;
            margin: 20px;
            text-align: center;
        }

        .card-container {
            display: flex;
            flex-wrap: wrap;
            justify-content: center;
            gap: 20px;
            padding: 0 20px;
        }

        .card {
            background-color: white;
            border-radius: 8px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            padding: 20px;
            width: 300px;
            margin: 10px;
            display: flex;
            flex-direction: column;
            gap: 10px;
        }

        .card h2 {
            margin-top: 0;
            color: #2d8f2d;
            font-size: 1.2em;
        }

        .card p {
            margin: 5px 0;
        }

        .card .actions {
            margin-top: auto;
            display: flex;
            gap: 10px;
        }

        .card a {
            text-decoration: none;
            color: #007bff;
            font-size: 0.9em;
        }

        .card a:hover {
            text-decoration: underline;
        }

        .card a.delete {
            color: #dc3545;
        }

        .no-complaints {
            text-align: center;
            padding: 20px;
            font-size: 1.2em;
            color: #777;
        }
    </style>
</head>
<body>
    <?php include('header.php'); ?>

    <h1>View Complaints</h1>

    <div class="card-container">
        <?php if (mysqli_num_rows($res) > 0): ?>
            <?php while ($row = mysqli_fetch_assoc($res)): ?>
                <div class="card">
                    <h2>Problem ID: <?= htmlspecialchars($row['problem_id']) ?></h2>
                  
                    <p><strong>Description:</strong> <?= htmlspecialchars($row['problem_text']) ?></p>
                    <p><strong>Additional Option:</strong> <?= htmlspecialchars($row['additional_option']) ?></p>
                    <p><strong>Date Submitted:</strong> <?= htmlspecialchars($row['created_at']) ?></p>
                    <div class="actions">
                        <a href="complaints.php?deleteid=<?= htmlspecialchars($row['problem_id']) ?>" class="delete" onclick="return confirm('Are you sure you want to delete this complaint?')">Delete</a>
                    </div>
                </div>
            <?php endwhile; ?>
        <?php else: ?>
            <div class="no-complaints">No complaints found</div>
        <?php endif; ?>
    </div>


</body>
</html>
