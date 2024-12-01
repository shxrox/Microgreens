<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Blog Posts</title>
    <style>
 html, body {
    height: 100%;
    margin: 0;
    padding: 0;
}

body {
    font-family: 'Arial', sans-serif;
    background-color: #f0f2f5;
    color: #1c1e21;
    display: flex;
    flex-direction: column;
}

h2 {
    text-align: center;
    color: #1877f2;
    font-size: 2.5em;
    margin: 30px 0;
    font-weight: 600;
}

.comment-section {
    margin-top: 20px;
    padding: 20px;
    background: #f9f9f9;
    border: 1px solid #ddd;
    border-radius: 10px;
    width: 15cm;
    box-sizing: border-box;
}

.comment {
    padding: 15px;
    border-bottom: 1px solid #e0e0e0;
    transition: background 0.3s;
}

.comment:hover {
    background: #f1f1f1;
}

.comment:last-child {
    border-bottom: none;
}

.comment strong {
    color: #1877f2;
    font-weight: bold;
}

.comment-form {
    display: flex;
    flex-direction: column;
    margin-top: 20px;
}

.comment-form input[type="text"],
.comment-form textarea {
    padding: 15px;
    margin-bottom: 15px;
    border: 1px solid #ccc;
    border-radius: 5px;
    font-size: 1.1em;
    width: 15cm;
    box-sizing: border-box;
}

.comment-form textarea {
    height: 100px;
}

.comment-form button {
    background-color: #4caf50;
    color: white;
    padding: 15px;
    border: none;
    border-radius: 5px;
    cursor: pointer;
    font-size: 1.1em;
    transition: background 0.3s;
    margin-bottom: 30px;
}

.comment-form button:hover {
    background-color: #388e3c;
}

.no-comments {
    width: 15cm;
    padding: 15px;
    background: #f0f0f0;
    border: 1px solid #ccc;
    border-radius: 5px;
    text-align: center;
    font-size: 1.1em;
    color: #555;
}

.container {
    display: flex;
    flex-direction: column;
    align-items: center;
    justify-content: center;
    flex: 1;
    padding: 20px;
    box-sizing: border-box;
}

.blog-card {
    background: #ffffff;
    border: 1px solid #ddd;
    border-radius: 10px;
    box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
    margin: 15px;
    width: 100%;
    max-width: 800px;
    transition: box-shadow 0.3s ease, transform 0.3s ease;
}

.blog-card:hover {
    box-shadow: 0 6px 12px rgba(0, 0, 0, 0.2);
    transform: translateY(-5px);
}

.blog-card img {
    width: 100%;
    height: auto;
    border-bottom: 1px solid #ddd;
}

.blog-card-content {
    padding: 15px;
    text-align: left;
}

.blog-card h3 {
    color: #333;
    font-size: 1.8em;
    margin: 0 0 10px 0;
}

.blog-card p {
    font-size: 1em;
    line-height: 1.5;
    color: #555;
    margin: 0;
}


    </style>
    </style>
</head>
<body>

<?php
include('connect.php');
include('header.php');


$sql_blogs = "SELECT * FROM blogs";
$res_blogs = mysqli_query($con, $sql_blogs);

if (!$res_blogs) {
    die('Error: ' . mysqli_error($con));
}


if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['comment'])) {
    $blog_id = $_POST['blog_id'];
    $username = htmlspecialchars($_POST['username']);
    $comment = htmlspecialchars($_POST['comment']);

    $sql_insert_comment = "INSERT INTO comments (blog_id, username, comment) VALUES ('$blog_id', '$username', '$comment')";
    mysqli_query($con, $sql_insert_comment);
}
?>

<h2>Blog Posts</h2>
<div class="container">
    <?php
    if (mysqli_num_rows($res_blogs) > 0) {
        while ($blog = mysqli_fetch_assoc($res_blogs)) {
            $blog_id = $blog['blogid']; // Use the correct column name
            $title = htmlspecialchars($blog['title']);
            $content = htmlspecialchars($blog['content']);
            $image = htmlspecialchars($blog['image']);
            $link = htmlspecialchars($blog['Link']);
    ?>
            <div class="blog-card">
                <?php if (!empty($image)): ?>
                    <img src="./admin/uploads/images/<?= $image ?>" alt="<?= $title ?>">
                <?php endif; ?>
                <div class="blog-card-content">
                    <h3><?= $title ?></h3>
                    <p><?= $content ?></p>
                    <p><a href="<?= $link ?>" target="_blank" style="color: #1877f2; text-decoration: none;"><?= $link ?></a></p>
                </div>
            </div>

            <h4>Comments:</h4>
<div class="comment-section">
    <?php
    $sql_comments = "SELECT * FROM comments WHERE blog_id = '$blog_id' ORDER BY created_at DESC";
    $res_comments = mysqli_query($con, $sql_comments);
    if (mysqli_num_rows($res_comments) > 0) {
        while ($comment = mysqli_fetch_assoc($res_comments)) {
            $username = htmlspecialchars($comment['username']);
            $comment_text = htmlspecialchars($comment['comment']);
            echo "<div class='comment'><strong>$username:</strong> $comment_text</div>";
        }
    } else {
        echo "<p>No comments yet.</p>";
    }
    ?>
</div>

<form method="POST" class="comment-form">
    <input type="hidden" name="blog_id" value="<?= $blog_id ?>">
    <input type="text" name="username" placeholder="Your Name" required>
    <textarea name="comment" placeholder="Your Comment" required></textarea>
    <button type="submit">Submit Comment</button>
</form>


    <?php
        }
    } else {
        echo "<p>No blog posts found.</p>";
    }
    ?>
</div>

<?php include('chat.php'); ?>
<?php include('social.php'); ?>
<?php include('footer.php'); ?>

</body>
</html>
