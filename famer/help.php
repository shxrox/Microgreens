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

// Handle form submission to add or update data
if (isset($_POST['save'])) {
    $category = mysqli_real_escape_string($con, $_POST['category']);
    $description = mysqli_real_escape_string($con, $_POST['description']);
    $microgreen_data = mysqli_real_escape_string($con, $_POST['microgreen_data']);
    $weather_data = mysqli_real_escape_string($con, $_POST['weather_data']);
    $water_date = mysqli_real_escape_string($con, $_POST['water_date']);
    $media_type = mysqli_real_escape_string($con, $_POST['media_type']);
    $media_file = $_FILES['media_file']['name'];
    $media_tmp_name = $_FILES['media_file']['tmp_name'];

    // Handle file upload if a new file is uploaded
    if ($media_file) {
        $target_dir = "../uploads/";
        $target_file = $target_dir . basename($media_file);
        move_uploaded_file($media_tmp_name, $target_file);
    } else {
        $target_file = isset($media_path) ? $media_path : '';
    }

    if (isset($_GET['id'])) {
        // Update existing data
        $stmt = $con->prepare("UPDATE help_data SET category = ?, description = ?, microgreen_data = ?, weather_data = ?, water_date = ?, media_type = ?, media_path = ? WHERE id = ?");
        $stmt->bind_param("sssssssi", $category, $description, $microgreen_data, $weather_data, $water_date, $media_type, $target_file, $_GET['id']);
    } else {
        // Insert new data
        $stmt = $con->prepare("INSERT INTO help_data (category, description, microgreen_data, weather_data, water_date, media_type, media_path) VALUES (?, ?, ?, ?, ?, ?, ?)");
        $stmt->bind_param("sssssss", $category, $description, $microgreen_data, $weather_data, $water_date, $media_type, $target_file);
    }

    // Execute the query and check for success
    if ($stmt->execute()) {
        echo "<script>alert('Data saved successfully'); window.location.href='help.php';</script>";
    } else {
        echo "Error: " . $stmt->error;
    }
    $stmt->close();
}

// Handle the delete functionality
if (isset($_GET['delete_id'])) {
    $delete_id = mysqli_real_escape_string($con, $_GET['delete_id']);
    $query = "DELETE FROM help_data WHERE id = '$delete_id'";
    if (mysqli_query($con, $query)) {
        echo "<script>alert('Data deleted successfully'); window.location.href='help.php';</script>";
    } else {
        echo "<script>alert('Error deleting data');</script>";
    }
}

// If editing, fetch existing data
if (isset($_GET['id'])) {
    $edit_id = mysqli_real_escape_string($con, $_GET['id']);
    $query = "SELECT * FROM help_data WHERE id = '$edit_id'";
    $result = mysqli_query($con, $query);
    $row = mysqli_fetch_assoc($result);

    $category = $row['category'];
    $description = $row['description'];
    $microgreen_data = $row['microgreen_data'];
    $weather_data = $row['weather_data'];
    $water_date = $row['water_date'];
    $media_type = $row['media_type'];
    $media_path = $row['media_path'];
} else {
    // Default values for new data
    $category = '';
    $description = '';
    $microgreen_data = '';
    $weather_data = '';
    $water_date = '';
    $media_type = '';
    $media_path = '';
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
            background-color: #f5f5f5;
            margin: 0;
            padding: 0;
        }

        h1 {
            font-family: 'Lato', sans-serif;
            font-weight: 700;
            letter-spacing: 0.1rem;
            color: #0F2429;
            font-size: 2rem;
            text-align: center;
            margin: 20px 0;
        }

        .help-container {
            width: 90%;
            max-width: 1200px;
            margin: 0 auto;
            padding: 20px;
            background-color: #fff;
            border-radius: 10px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        }

        /* Form Styling */
        form {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 20px;
            margin-bottom: 30px;
        }

        form div {
            display: flex;
            flex-direction: column;
        }

        label {
            font-weight: 600;
            margin-bottom: 5px;
        }

        input[type="text"], input[type="date"], input[type="number"], input[type="file"], textarea, select {
            padding: 10px;
            border: 1px solid #ccc;
            border-radius: 5px;
            font-size: 16px;
            width: 100%;
            box-sizing: border-box;
        }

        textarea {
            resize: vertical;
        }

        /* General Button Styling */
        button, input[type="submit"] {
            padding: 12px 20px;
            font-size: 16px;
            background-color: #0F2429;
            color: #fff;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            transition: background-color 0.3s ease, color 0.3s ease, transform 0.3s ease, box-shadow 0.3s ease;
            text-align: center;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        }

        button:hover, input[type="submit"]:hover {
            background-color: #1A3942;
            color: #32CD32;
            letter-spacing: 1px;
            transform: scale(1.05);
            box-shadow: 0 6px 12px rgba(0, 0, 0, 0.2);
        }

        /* Table Styling */
        table {
            width: 100%;
            border-collapse: collapse;
            background-color: #fff;
            margin-top: 20px;
        }

        th, td {
            padding: 12px;
            text-align: left;
            border-bottom: 1px solid #ddd;
        }

        th {
            background-color: #0f2429;
            color: white;
        }

        tbody tr:nth-child(even) {
            background-color: #f9f9f9;
        }

        tbody tr:hover {
            background-color: #f1f1f1;
        }

        .table-container {
            overflow-x: auto;
        }

        /* Responsive Design */
        @media screen and (max-width: 768px) {
            form {
                grid-template-columns: 1fr;
            }

            h1 {
                font-size: 1.5rem;
            }
        }
    </style>
    <script>
        function updateWeatherData(change) {
            var weatherDataInput = document.getElementById('weather_data');
            var currentValue = parseInt(weatherDataInput.value) || 0;
            weatherDataInput.value = currentValue + change;
        }
    </script>
</head>
<body>

<?php include('header.php'); ?>

<div class="help-container">
    <h1>Microgreen Plants - Help &amp; Information</h1>

    <!-- Form to input new or edit data -->
    <form action="help.php<?= isset($edit_id) ? '?id='.$edit_id : '' ?>" method="post" enctype="multipart/form-data">
        <div>
            <label for="category">Category:</label>
            <input type="text" name="category" id="category" value="<?= htmlspecialchars($category) ?>" required>
        </div>
        <div>
            <label for="description">Name, Information</label>
            <textarea name="description" id="description" required><?= htmlspecialchars($description) ?></textarea>
        </div>
        <div>
            <label for="microgreen_data">Planting Date</label>
            <input type="date" name="microgreen_data" id="microgreen_data" value="<?= htmlspecialchars($microgreen_data) ?>">
        </div>
        <div>
            <label for="weather_data">Weather Data:</label>
            <input type="number" name="weather_data" id="weather_data" min="0" step="1" value="<?= htmlspecialchars($weather_data) ?>">
            <button type="button" onclick="updateWeatherData(-1)">Decrease</button>
            <button
            type="button" onclick="updateWeatherData(1)">Increase</button>
        </div>
        <div>
            <label for="water_date">Watering Date:</label>
            <input type="date" name="water_date" id="water_date" value="<?= htmlspecialchars($water_date) ?>">
        </div>
        <div>
            <label for="media_type">Media Type:</label>
            <select name="media_type" id="media_type">
                <option value="image" <?= ($media_type == 'image') ? 'selected' : '' ?>>Image</option>
                <option value="video" <?= ($media_type == 'video') ? 'selected' : '' ?>>Video</option>
                <option value="document" <?= ($media_type == 'document') ? 'selected' : '' ?>>Document</option>
            </select>
        </div>
        <div>
            <label for="media_file">Upload Media File:</label>
            <input type="file" name="media_file" id="media_file">
            <?php if ($media_path): ?>
                <p>Current file: <a href="<?= htmlspecialchars($media_path) ?>" target="_blank">View</a></p>
            <?php endif; ?>
        </div>
        <div>
            <input type="submit" name="save" value="<?= isset($edit_id) ? 'Update' : 'Add' ?>">
        </div>
    </form>

    <!-- Displaying existing records in a table -->
    <div class="table-container">
        <table>
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Category</th>
                    <th>Description</th>
                    <th>Planting Date</th>
                    <th>Weather Data</th>
                    <th>Watering Date</th>
                    <th>Media Type</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php
                $query = "SELECT * FROM help_data";
                $result = mysqli_query($con, $query);
                while ($row = mysqli_fetch_assoc($result)) {
                    echo "<tr>
                        <td>{$row['id']}</td>
                        <td>{$row['category']}</td>
                        <td>{$row['description']}</td>
                        <td>{$row['microgreen_data']}</td>
                        <td>{$row['weather_data']}</td>
                        <td>{$row['water_date']}</td>
                        <td>{$row['media_type']}</td>
                        <td>
                            <a href='help.php?id={$row['id']}'>Edit</a> |
                            <a href='help.php?delete_id={$row['id']}' onclick='return confirm(\"Are you sure you want to delete this item?\");'>Delete</a>
                        </td>
                    </tr>";
                }
                ?>
            </tbody>
        </table>
    </div>
</div>

<?php include('footer.php'); ?>
</body>
</html>
