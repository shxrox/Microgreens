<?php 
include('../connect.php');

// Check if session is already started
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

if (!isset($_SESSION['uid'])) {
    echo "<script> window.location.href='../login.php'; </script>";
    exit();
}

// Handle record deletion
if (isset($_GET['delete_id'])) {
    $delete_id = intval($_GET['delete_id']);
    $stmt = $con->prepare("DELETE FROM help_data WHERE id = ?");
    $stmt->bind_param("i", $delete_id);
    
    if ($stmt->execute()) {
        echo "<script>alert('Record deleted successfully');</script>";
    } else {
        echo "Error: " . $stmt->error;
    }
    $stmt->close();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Help - Microgreen Plants</title>
    <style>
/* General Styling */
body {
    font-family: 'Lato', sans-serif;
    background-color: #f4f4f9;
    color: #333;
    margin: 0;
    padding: 0;
}

h1, h2 {
    text-align: center;
    color: #2c3e50;
}

h1 {
    margin-top: 30px;
    font-size: 2.5rem;
}

h2 {
    margin-bottom: 20px;
    font-size: 1.8rem;
}

/* Table Styling */
.table-container {
    margin: 40px auto;
    max-width: 90%;
    background-color: #ffffff;
    border-radius: 10px;
    box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
    padding: 20px;
}

table {
    width: 100%;
    border-collapse: collapse;
    margin: 0 auto;
    font-size: 1rem;
}

th, td {
    padding: 12px 15px;
    text-align: center;
    border-bottom: 1px solid #ddd;
}

th {
    background-color: #2d8f2d;
    color: #ffffff;
    font-weight: bold;
}

td {
    background-color: #f9f9f9;
}

tr:hover td {
    background-color: #e0f7fa;
}

/* Action Button */
a {
    text-decoration: none;
    padding: 8px 12px;
    background-color: #0F2429;
    color: #ffffff;
    border-radius: 5px;
    transition: background-color 0.3s ease;
}



/* Media File Link */
a[target="_blank"] {
    background-color: #3498db;
    color: white;
}

a[target="_blank"]:hover {
    background-color: #2980b9;
}

/* Responsive Design */
@media (max-width: 768px) {
    table, th, td {
        font-size: 0.9rem;
    }

    .table-container {
        padding: 10px;
    }

    h1 {
        font-size: 2rem;
    }

    h2 {
        font-size: 1.5rem;
    }
}

    </style>
</head>
<body>

<?php include('header.php'); ?>

<div class="help-container">
    <h1>Microgreen Plants - Help &amp; Information</h1>

    <!-- Display data in a table format -->
    <div class="table-container">
        <h2>Saved Data</h2>
        <table>
            <tr>
                <th>ID</th>
                <th>Category</th>
                <th>Description</th>
                <th>Microgreen Data</th>
                <th>Weather Data</th>
                <th>Water Date</th>
                <th>Media Type</th>
                <th>Media File</th>
                <th>Actions</th>
            </tr>
            <?php
            $sql = "SELECT * FROM help_data ORDER BY created_at DESC";
            $res = mysqli_query($con, $sql);
            if (mysqli_num_rows($res) > 0) {
                while ($data = mysqli_fetch_array($res)) {
                    ?>
                    <tr>
                        <td><?= htmlspecialchars($data['id']) ?></td>
                        <td><?= htmlspecialchars($data['category']) ?></td>
                        <td><?= nl2br(htmlspecialchars($data['description'])) ?></td>
                        <td><?= htmlspecialchars($data['microgreen_data']) ?></td>
                        <td><?= htmlspecialchars($data['weather_data']) ?></td>
                        <td><?= htmlspecialchars($data['water_date']) ?></td>
                        <td><?= htmlspecialchars($data['media_type']) ?></td>
                        <td>
                            <?php if ($data['media_path']): ?>
                                <a href="<?= htmlspecialchars($data['media_path']) ?>" target="_blank">View</a>
                            <?php else: ?>
                                No media
                            <?php endif; ?>
                        </td>
                        <td>
                            <a href="help.php?delete_id=<?= htmlspecialchars($data['id']) ?>" onclick="return confirm('Are you sure you want to delete this item?');">Delete</a>
                        </td>
                    </tr>
                    <?php
                }
            } else {
                echo "<tr><td colspan='9'>No data found.</td></tr>";
            }
            ?>
        </table>
    </div>

 
</div>


<?php include('footer.php'); ?>
    <?php include('weather.php');?>
    <?php include('social.php') ?>
</body>
</html>
