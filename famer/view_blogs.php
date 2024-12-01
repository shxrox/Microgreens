<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Blog Posts</title>
    <style>
        html, body {
    height: 100%; /* Ensure the HTML and body cover the full viewport height */
    margin: 0; /* Remove default margin */
    padding: 0; /* Remove default padding */
}

body {
    font-family: 'Arial', sans-serif; /* Use a commonly available font */
    background-color: #f0f2f5; /* Light gray background for a social media feel */
    color: #1c1e21; /* Dark gray text for readability */
    display: flex;
    flex-direction: column; /* Align items in a column */
}

h2 {
    text-align: center;
    color: #1877f2; /* Facebook blue color */
    font-size: 2.5em;
    margin: 30px 0;
    font-weight: 600; /* Bold for emphasis */
}

.container {
    display: flex;
    flex-direction: column;
    align-items: center;
    justify-content: center; /* Center content vertically */
    flex: 1; /* Make the container take up the available space */
    padding: 20px;
    box-sizing: border-box; /* Include padding and border in the element’s total width and height */
}

.blog-card {
    background: #ffffff; /* White background for cards */
    border: 1px solid #ddd; /* Light gray border */
    border-radius: 10px; /* Rounded corners */
    box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1); /* Subtle shadow */
    margin: 15px;
    width: 100%;
    max-width: 600px; /* Max width to keep cards manageable */
    transition: box-shadow 0.3s ease, transform 0.3s ease;
}

.blog-card:hover {
    box-shadow: 0 6px 12px rgba(0, 0, 0, 0.2); /* Darker shadow on hover */
    transform: translateY(-5px); /* Slight lift on hover */
}

.blog-card img {
    width: 100%;
    height: auto; /* Maintain aspect ratio */
    border-bottom: 1px solid #ddd; /* Divider between image and content */
}

.blog-card-content {
    padding: 15px;
    text-align: left; /* Left-align text for a cleaner look */
}

.blog-card h3 {
    color: #333; /* Darker text color for headings */
    font-size: 1.8em; /* Slightly larger font size for headings */
    margin: 0 0 10px 0;
}

.blog-card p {
    font-size: 1em;
    line-height: 1.5;
    color: #555; /* Slightly lighter text color for paragraphs */
    margin: 0;
}
    </style>
</head>
<body>

<?php
    include('connect.php');
    include('header.php');

    // Fetch all blog posts from the database
    $sql_blogs = "SELECT * FROM blogs";
    $res_blogs = mysqli_query($con, $sql_blogs);

    if (!$res_blogs) {
        die('Error: ' . mysqli_error($con));
    }
?>

<h2>Blog Posts</h2>
<div class="container">
    <?php
        if (mysqli_num_rows($res_blogs) > 0) {
            while ($blog = mysqli_fetch_assoc($res_blogs)) {
                $title = htmlspecialchars($blog['title']);
                $content = htmlspecialchars($blog['content']);
                $image = htmlspecialchars($blog['image']);
                $link = htmlspecialchars($blog['Link']);
    ?>
                <div class="blog-card">
                    <?php if (!empty($image)): ?>
                        <img src="../admin/uploads/images/<?= $image ?>" alt="<?= $title ?>">
                    <?php endif; ?>
                    <div class="blog-card-content">
                        <h3><?= $title ?></h3>
                        <p><?= $content ?></p>
                        <p><a href="<?= $link ?>" target="_blank" style="color: #1877f2; text-decoration: none;"><?= $link ?></a></p>

                    </div>
                </div>
    <?php
            }
        } else {
            echo "<p>No blog posts found.</p>";
        }
    ?>
</div>

<?php include('footer.php') ?>
<?php include('chat.php') ?>
<?php include('weather.php') ?>
<?php include('social.php'); ?>


</body>
</html>
