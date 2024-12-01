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
            $target = "../admin/uploads/images/" . basename($image);

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
    <title>Add Blog (Staff)</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f4;
            margin: 0;
            padding: 0;
        }

        .blog-container {
            max-width: 900px;
            margin: 20px auto;
            padding: 20px;
            background-color: white;
            border-radius: 8px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
        }

        h2 {
            color: #333;
            border-bottom: 2px solid #2d8f2d;
            padding-bottom: 10px;
            margin-bottom: 20px;
            font-size: 1.8em;
        }

        form {
            margin-bottom: 30px;
        }

        label {
            display: block;
            margin-bottom: 5px;
            font-weight: bold;
        }

        input[type="text"],
        textarea,
        input[type="file"] {
            width: calc(100% - 22px);
            padding: 10px;
            margin: 5px 0 20px;
            border: 1px solid #ddd;
            border-radius: 5px;
            box-sizing: border-box;
            font-size: 1em;
            transition: border-color 0.3s ease;
        }

        input[type="text"]:focus,
        textarea:focus {
            border-color: #2d8f2d;
        }

        input[type="submit"] {
            background-color: #2d8f2d;
            color: white;
            border: none;
            padding: 10px 20px;
            cursor: pointer;
            border-radius: 5px;
            font-size: 1em;
            transition: background-color 0.3s ease;
        }

        input[type="submit"]:hover {
            background-color: #1b6b1b;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
        }

        table th,
        table td {
            padding: 12px;
            text-align: left;
            border-bottom: 1px solid #ddd;
        }

        table th {
            background-color: #2d8f2d;
            color: white;
            font-size: 1.1em;
        }

        table td img {
            max-width: 100px;
            border-radius: 5px;
        }

        table td form {
            display: inline;
        }

        table td input[type="submit"] {
            background-color: #d9534f;
            color: white;
            border: none;
            padding: 5px 10px;
            cursor: pointer;
            border-radius: 5px;
            font-size: 0.9em;
            transition: background-color 0.3s ease;
        }

        table td input[type="submit"]:hover {
            background-color: #c9302c;
        }

        .no-records {
            text-align: center;
            padding: 20px;
            font-size: 1.2em;
            color: #777;
        }
    </style>
    <script>
        // Function to show alert on successful blog addition or deletion
        function showAlert(message) {
            alert(message);
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
            <script>
                // Show success alert if blog was added or deleted successfully
                showAlert("New blog added successfully.");
            </script>
        <?php endif; ?>

        <?php if (!empty($error)): ?>
            <script>
                // Show error alert if there was an error
                showAlert("<?php echo $error; ?>");
            </script>
        <?php endif; ?>

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
                                    <img src="../admin/uploads/images/<?= htmlspecialchars($blog['image']) ?>" alt="Image">
                                <?php else: ?>
                                    No image
                                <?php endif; ?>
                            </td>
                            <td>
                                <form action="add_blog.php" method="post">
                                    <input type="hidden" name="delete_blog_id" value="<?= htmlspecialchars($blog['blogid']) ?>">
                                    <input type="submit" value="Delete" onclick="return confirm('Are you sure you want to delete this blog?');">
                                </form>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        <?php else: ?>
            <p class="no-records">No blogs available.</p>
        <?php endif; ?>
    </div>
</body>
</html>
