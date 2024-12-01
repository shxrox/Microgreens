<?php
// Define the directory where images are stored
$directory = 'admin/uploads/ads/';
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
        /* Slider Container */
        .slider {
            max-width: 1000px; /* Increased width */
            height: 500px; /* Increased height */
            margin: auto;
            overflow: hidden;
            position: relative;
            border-radius: 10px; /* Rounded corners */
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1); /* Subtle shadow */
        }

        /* Slide Images */
        .slides {
            display: flex;
            width: 100%;
            transition: transform 0.7s ease-in-out; /* Smooth transition */
        }

        .slides img {
            width: 100%; /* Ensures images fill the slider container */
            height: 100%; /* Ensure images cover the height */
            object-fit: cover; /* Cover the container while maintaining aspect ratio */
        }

        /* Remove the navigation buttons */
        .slider-nav {
            display: none;
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
