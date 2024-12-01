<?php
// Define the directory where images are stored
$directory = '../admin/uploads/ads/';
$images = [];

// Get all image files from the directory
if (is_dir($directory)) {
    $files = scandir($directory);
    foreach ($files as $file) {
        // Check if the file is not a directory and has a valid image extension
        if ($file !== '.' && $file !== '..' && is_file($directory . $file)) {
            $images[] = $directory . $file;
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Advertisement Slider</title>
    <style>
        .slider {
            max-width: 800px; /* Adjust the max-width as needed */
            margin: auto;
            overflow: hidden;
            position: relative;
        }

        .slides {
            display: flex;
            width: 100%;
            transition: transform 0.5s ease-in-out;
        }

        .slides img {
            width: 100%; /* Ensures images fill the slider container */
            height: auto; /* Maintain aspect ratio */
            max-height: 300px; /* Adjust max height as needed */
        }

        .slider-nav {
            position: absolute;
            top: 50%;
            width: 100%;
            display: flex;
            justify-content: space-between;
            transform: translateY(-50%);
        }

        .slider-nav button {
            background-color: rgba(0, 0, 0, 0.5);
            border: none;
            color: white;
            padding: 10px;
            cursor: pointer;
        }
    </style>
</head>
<body>
<div class="slider">
    <div class="slides">
        <?php foreach ($images as $image): ?>
            <img src="<?= htmlspecialchars($image) ?>" alt="Advertisement">
        <?php endforeach; ?>
    </div>
    <div class="slider-nav">
        <button onclick="prevSlide()">❮</button>
        <button onclick="nextSlide()">❯</button>
    </div>
</div>

<script>
    let index = 0;
    const slides = document.querySelector('.slides');
    const totalSlides = <?= count($images) ?>;

    function showSlide() {
        index++;
        if (index >= totalSlides) index = 0;
        slides.style.transform = `translateX(${-index * 100}%)`;
    }

    function nextSlide() {
        showSlide();
    }

    function prevSlide() {
        index--;
        if (index < 0) index = totalSlides - 1;
        slides.style.transform = `translateX(${-index * 100}%)`;
    }

    setInterval(showSlide, 3000); // Change image every 3 seconds
</script>
</body>
</html>
