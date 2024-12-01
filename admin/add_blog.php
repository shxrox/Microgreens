<?php
session_start();

// Database connection
$con = mysqli_connect('localhost', 'root', '', 'microgreen');
if (!$con) {
    die('Cannot establish DB connection: ' . mysqli_connect_error());
}

$success = false;
$error = '';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (isset($_POST['delete_blog_id'])) {
        // Handle blog deletion
        $blog_id = $_POST['delete_blog_id'];
        $sql = "DELETE FROM blogs WHERE blogid = ?";
        if ($stmt = $con->prepare($sql)) {
            $stmt->bind_param("i", $blog_id);
            if ($stmt->execute()) {
                $success = true; // Set success flag
            } else {
                $error = "Error: " . $stmt->error;
            }
            $stmt->close();
        } else {
            $error = "Error preparing statement: " . $con->error;
        }
    } else {
        // Handle blog addition
        $title = $_POST['title'];
        $content = $_POST['content'];
        $link = $_POST['Link'];

        // Handle file upload
        $image = "";
        if (!empty($_FILES['image']['name'])) {
            $image = $_FILES['image']['name'];
            $target = "uploads/images/" . basename($image);

            if (!move_uploaded_file($_FILES['image']['tmp_name'], $target)) {
                $error = "Failed to upload image.";
            }
        }

        $sql = "INSERT INTO blogs (title, content, image, Link) VALUES (?, ?, ?, ?)";
        if ($stmt = $con->prepare($sql)) {
            $stmt->bind_param("ssss", $title, $content, $image, $link); // Correct the bind_param
            if ($stmt->execute()) {
                $success = true; // Set success flag
            } else {
                $error = "Error: " . $stmt->error; // Error handling
            }
            $stmt->close();
        } else {
            $error = "Error preparing statement: " . $con->error; // Error handling
        }
        
    }
}

// Fetch existing blogs
$sql = "SELECT * FROM blogs";
$result = $con->query($sql);
$blogs = [];
while ($row = $result->fetch_assoc()) {
    $blogs[] = $row;
}

// Close the database connection
$con->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage Blogs</title>
    <style>
        body {
            font-family: 'Arial', sans-serif;
            background-color: #f4f7f6;
            color: #333;
            margin: 0;
            padding: 20px;
        }

        .blog-container {
            max-width: 1200px;
            margin: 0 auto;
            padding: 20px;
            background-color: #fff;
            border-radius: 8px;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);
        }

        h2 {
            color: #2d8f2d;
            font-size: 2.2em;
            margin-bottom: 20px;
            text-align: center;
        }

        form {
            display: flex;
            flex-direction: column;
            gap: 15px;
        }

        label {
            font-weight: bold;
            font-size: 1.1em;
        }

        input[type="text"],
        textarea,
        input[type="file"] {
            width: 90%;
            padding: 10px;
            border-radius: 8px;
            border: 1px solid #ccc;
            font-size: 1em;
        }

        input[type="submit"] {
            align-self: flex-start;
            background-color: #28a745;
            color: white;
            padding: 12px 25px;
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

        .table-container {
            margin-top: 40px;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
            font-size: 1em;
        }

        th, td {
            padding: 15px;
            border-bottom: 1px solid #ddd;
            text-align: left;
        }

        th {
            background-color: #2d8f2d;
            color: white;
            text-transform: uppercase;
            letter-spacing: 0.05em;
        }

        tr:nth-child(even) {
            background-color: #f9f9f9;
        }

        tr:hover {
            background-color: #e9f5e9;
        }

        img {
            border-radius: 8px;
            width: 100px;
            height: auto;
        }

        .action-links {
            display: flex;
            gap: 10px;
        }

        .action-links input[type="submit"] {
            background-color: #dc3545;
            padding: 8px 15px;
            font-size: 1em;
        }

        .action-links input[type="submit"]:hover {
            background-color: #c82333;
        }

        .alert {
            padding: 20px;
            background-color: #4caf50;
            color: white;
            margin-bottom: 15px;
            border-radius: 8px;
            text-align: center;
        }

        .alert.error {
            background-color: #f44336;
        }

    </style>
    <script>
        function showAlert(message, isError = false) {
            const alertBox = document.createElement('div');
            alertBox.className = 'alert' + (isError ? ' error' : '');
            alertBox.textContent = message;
            document.body.insertBefore(alertBox, document.body.firstChild);
            setTimeout(() => alertBox.remove(), 3000);
        }
    </script>
</head>
<body>
<?php include('header.php'); ?>
    <div class="blog-container">
        <h2>Add New Blog</h2>
        <form action="add_blog.php" method="post" enctype="multipart/form-data">
            <label for="title">Title:</label>
            <input type="text" id="title" name="title" required>

            <label for="content">Content:</label>
            <textarea id="content" name="content" rows="5" required></textarea>

            <label for="image">Image (optional):</label>
            <input type="file" id="image" name="image">

            <label for="Link">Link:</label>
            <input type="url" id="Link" name="Link" placeholder="Enter  link" required><br><br>

            <input type="submit" value="Add Blog">
        </form>

        <?php if ($success): ?>
            <script>showAlert("Operation successful.");</script>
        <?php endif; ?>

        <?php if (!empty($error)): ?>
            <script>showAlert("<?php echo $error; ?>", true);</script>
        <?php endif; ?>

        <div class="table-container">
            <h2>Existing Blogs</h2>
            <?php if (count($blogs) > 0): ?>
                <table>
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Title</th>
                            <th>Content</th>
                            <th>Image</th>
                            <th>Link</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($blogs as $blog): ?>
                            <tr>
                                <td><?= htmlspecialchars($blog['blogid']) ?></td>
                                <td><?= htmlspecialchars($blog['title']) ?></td>
                                <td><?= htmlspecialchars($blog['content']) ?></td>
                                <td><?= htmlspecialchars($blog['Link']) ?></td>
                                <td>
                                    <?php if (!empty($blog['image'])): ?>
                                        <img src="uploads/images/<?= htmlspecialchars($blog['image']) ?>" alt="Blog Image">
                                    <?php else: ?>
                                        No image
                                    <?php endif; ?>
                                </td>
                                <td class="action-links">
                                    <form action="add_blog.php" method="post" style="display:inline;">
                                        <input type="hidden" name="delete_blog_id" value="<?= htmlspecialchars($blog['blogid']) ?>">
                                        <input type="submit" value="Delete" onclick="return confirm('Are you sure you want to delete this blog?');">
                                    </form>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            <?php else: ?>
                <p>No blogs available.</p>
            <?php endif; ?>
        </div>
    </div>
</body>
</html>
