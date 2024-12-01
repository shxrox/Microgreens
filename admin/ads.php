<?php
include('connect.php');

// Handle file upload
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_FILES["ad_image"])) {
    $targetDir = "uploads/ads/";
    $fileName = basename($_FILES["ad_image"]["name"]);
    $targetFile = $targetDir . $fileName;
    $uploadOk = 1;
    $imageFileType = strtolower(pathinfo($targetFile, PATHINFO_EXTENSION));

    // Check if image file is an actual image or fake image
    $check = getimagesize($_FILES["ad_image"]["tmp_name"]);
    if ($check !== false) {
        $uploadOk = 1;
    } else {
        echo "File is not an image.";
        $uploadOk = 0;
    }

    // Check if file already exists
    if (file_exists($targetFile)) {
        echo "Sorry, file already exists.";
        $uploadOk = 0;
    }

    // Check file size
    if ($_FILES["ad_image"]["size"] > 5000000) { // 5MB limit
        echo "Sorry, your file is too large.";
        $uploadOk = 0;
    }

    // Allow certain file formats
    if ($imageFileType != "jpg" && $imageFileType != "png" && $imageFileType != "jpeg") {
        echo "Sorry, only JPG, JPEG, & PNG files are allowed.";
        $uploadOk = 0;
    }

    // Check if $uploadOk is set to 0 by an error
    if ($uploadOk == 0) {
        echo "Sorry, your file was not uploaded.";
    } else {
        if (move_uploaded_file($_FILES["ad_image"]["tmp_name"], $targetFile)) {
            // Save the image path to the database
            $stmt = $con->prepare("INSERT INTO ads (image_path) VALUES (?)");
            $stmt->bind_param("s", $fileName);
            $stmt->execute();
            echo "The file " . htmlspecialchars($fileName) . " has been uploaded.";
        } else {
            echo "Sorry, there was an error uploading your file.";
        }
    }
}

// Handle image deletion
if (isset($_GET['delete'])) {
    $id = intval($_GET['delete']);
    $stmt = $con->prepare("SELECT image_path FROM ads WHERE id = ?");
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $result = $stmt->get_result();
    $row = $result->fetch_assoc();
    
    if ($row) {
        $filePath = "uploads/ads/" . $row['image_path'];
        if (file_exists($filePath) && unlink($filePath)) {
            $stmt = $con->prepare("DELETE FROM ads WHERE id = ?");
            $stmt->bind_param("i", $id);
            $stmt->execute();
            echo "Image deleted successfully.";
        } else {
            echo "Error deleting file or file does not exist.";
        }
    } else {
        echo "Image not found.";
    }
}

// Fetch all ads for display
$sql = "SELECT id, image_path FROM ads";
$result = $con->query($sql);
$ads = [];
if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $ads[] = $row;
    }
}
$con->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage Advertisements</title>
    <style>
        body {
            font-family: 'Arial', sans-serif;
            background-color: #f9f9f9;
            color: #333;
            margin: 0;
            padding: 0;
        }

        .ads-container {
            width: 90%;
            max-width: 1200px;
            margin: 40px auto;
            padding: 20px;
            background-color: #fff;
            border-radius: 12px;
            box-shadow: 0 6px 18px rgba(0, 0, 0, 0.1);
        }

        h2 {
            color: #2d8f2d;
            font-size: 2.5em;
            margin-bottom: 20px;
            text-align: center;
        }

        form {
            text-align: center;
            margin-bottom: 40px;
        }

        input[type="file"] {
            display: inline-block;
            padding: 12px;
            border-radius: 8px;
            border: 1px solid #ccc;
            font-size: 1em;
            margin-right: 10px;
            background-color: #f0f0f0;
        }

        input[type="submit"] {
            background-color: #28a745;
            color: white;
            padding: 12px 20px;
            border: none;
            border-radius: 8px;
            cursor: pointer;
            font-size: 1.1em;
            transition: background-color 0.3s ease, transform 0.2s ease;
        }

        input[type="submit"]:hover {
            background-color: #218838;
            transform: scale(1.05);
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }

        th, td {
            padding: 15px;
            text-align: left;
            border-bottom: 1px solid #ddd;
        }

        th {
            background-color: #2d8f2d;
            color: white;
            text-transform: uppercase;
            letter-spacing: 0.05em;
        }

        tr:nth-child(even) {
            background-color: #f2f2f2;
        }

        tr:hover {
            background-color: #e9f5e9;
        }

        img {
            max-width: 100px;
            height: auto;
            border-radius: 8px;
            transition: transform 0.3s ease;
        }

        img:hover {
            transform: scale(1.1);
        }

        .action-links a {
            color: #dc3545;
            text-decoration: none;
            font-size: 1.1em;
            margin-right: 15px;
        }

        .action-links a:hover {
            text-decoration: underline;
        }
    </style>
</head>
<body>
<?php include('header.php'); ?>
    <div class="ads-container">
        <h2>Upload Advertisement Image</h2>
        <form action="ads.php" method="post" enctype="multipart/form-data">
            <input type="file" name="ad_image" id="ad_image" required>
            <input type="submit" value="Upload Image" name="submit">
        </form>

        <h2>Manage Advertisements</h2>
        <table>
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Image</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($ads as $ad): ?>
                <tr>
                    <td><?= htmlspecialchars($ad['id']) ?></td>
                    <td><img src="uploads/ads/<?= htmlspecialchars($ad['image_path']) ?>" alt="Advertisement"></td>
                    <td class="action-links">
                        <a href="ads.php?delete=<?= htmlspecialchars($ad['id']) ?>" onclick="return confirm('Are you sure you want to delete this image?');">Delete</a>
                    </td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</body>
</html>
