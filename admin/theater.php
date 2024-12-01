<?php
include '../connect.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $title = $_POST['title'];
    $content = $_POST['content'];

    // Handle file upload
    $image = $_FILES['image']['name'];
    $target = "uploads/images/" . basename($image);
    if (move_uploaded_file($_FILES['image']['tmp_name'], $target)) {
        $sql = "INSERT INTO blogs (title, content, image) VALUES (?, ?, ?)";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("sss", $title, $content, $image);
        if ($stmt->execute()) {
            echo "New blog added successfully.";
        } else {
            echo "Error: " . $stmt->error;
        }
        $stmt->close();
    } else {
        echo "Failed to upload image.";
    }
}

$conn->close();
?>

<form method="post" enctype="multipart/form-data">
    Title: <input type="text" name="title" required><br>
    Content: <textarea name="content" required></textarea><br>
    Image: <input type="file" name="image"><br>
    <input type="submit" value="Add Blog">
</form>
