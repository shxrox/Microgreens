<?php
session_start(); // Start the session if it hasn't been started already

// Include the header
include('header.php');

// Check if user is logged in and get their type
$userType = isset($_SESSION['type']) ? $_SESSION['type'] : null;
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>About Us - Microgreens</title>
</head>
<style>
    /* General Styles */
body {
    font-family: 'Lato', sans-serif; /* Ensure the font family is applied */
    font-weight: 100; /* Regular weight */
    font-size: 1rem; /* Adjust the size to your preference */
    letter-spacing: 0.1rem; /* Slight letter spacing for clarity */
    text-transform: uppercase; /* Uppercase text for emphasis */
    text-align: center; /* Center the text */
    margin: 0; /* Remove default margin */
    color: #333;
    padding: 0;
    background-color: #f4f4f4;
}


/* About Us Section */
.about-us {
    max-width: 1200px;
    margin: 0 auto;
    padding: 20px;
    background-color: #ffffff;
    border-radius: 8px;
    box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
}

.about-us h1 {
    text-align: center;
    color: #28a745;
    margin-bottom: 20px;
    font-size: 36px;
}

section {
    margin-bottom: 40px;
}

section h2 {
    color: #333;
    border-bottom: 2px solid #28a745;
    padding-bottom: 10px;
    margin-bottom: 20px;
    font-size: 28px;
}

section p, section ul {
    font-size: 16px;
    line-height: 1.6;
}

section ul {
    list-style-type: disc;
    margin-left: 20px;
}

.team-members {
    display: flex;
    flex-wrap: wrap;
    gap: 20px;
}

.team-member {
    background-color: #f9f9f9;
    padding: 20px;
    border-radius: 8px;
    box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
    flex: 1 1 calc(33.333% - 20px);
    box-sizing: border-box;
}

.team-member h3 {
    color: #28a745;
    margin-bottom: 10px;
}

.team-member p {
    margin: 0;
}

blockquote {
    border-left: 4px solid #28a745;
    padding: 10px 20px;
    margin: 20px 0;
    background-color: #f9f9f9;
    border-radius: 5px;
    font-style: italic;
}

blockquote footer {
    text-align: right;
    color: #555;
}

iframe {
    width: 100%;
    border-radius: 8px;
}

</style>
<body>
    <div class="about-us">
        <h1>About Us</h1>

        <section class="our-story">
            <h2>Our Story</h2>
            <p>Microgreens was founded with a passion for growing and providing fresh, nutritious, and flavorful microgreens. From our humble beginnings, we have expanded to become a leading name in the industry, committed to quality and sustainability.</p>
        </section>

        <section class="mission-vision">
            <h2>Mission & Vision</h2>
            <p><strong>Mission:</strong> To grow and supply high-quality microgreens that enhance the health and flavor of every meal.</p>
            <p><strong>Vision:</strong> To be a leading provider of microgreens, known for our innovation, sustainability, and dedication to customer satisfaction.</p>
        </section>

        <section class="values">
            <h2>Our Values</h2>
            <ul>
                <li><strong>Sustainability:</strong> We are committed to environmentally-friendly practices and reducing our carbon footprint.</li>
                <li><strong>Quality:</strong> We ensure that every microgreen is grown to the highest standards.</li>
                <li><strong>Customer Focus:</strong> We strive to meet the needs of our customers with exceptional service and products.</li>
                <li><strong>Innovation:</strong> We continuously explore new methods and varieties to improve our offerings.</li>
            </ul>
        </section>

        <section class="team">
            <h2>Meet the Team</h2>
            <div class="team-members">
                <div class="team-member">
                    <h3>John Doe - Founder & CEO</h3>
                    <p>John has over 20 years of experience in sustainable agriculture and a passion for growing healthy microgreens.</p>
                </div>
                <div class="team-member">
                    <h3>Jane Smith - Chief Operating Officer</h3>
                    <p>Jane oversees our daily operations, ensuring that every batch of microgreens meets our high standards.</p>
                </div>
                <div class="team-member">
                    <h3>Michael Brown - Head of Research & Development</h3>
                    <p>Michael leads our R&D team, focusing on new varieties and innovative growing techniques.</p>
                </div>
            </div>
        </section>

        <section class="services">
            <h2>What We Offer</h2>
            <ul>
                <li><strong>Microgreens Growing:</strong> We cultivate a wide range of microgreens, from common to exotic varieties.</li>
                <li><strong>Subscription Services:</strong> Enjoy regular deliveries of fresh microgreens with our subscription plans.</li>
                <li><strong>Consulting:</strong> We offer consulting services for businesses and individuals interested in growing their own microgreens.</li>
            </ul>
        </section>

        <section class="milestones">
            <h2>Milestones and Achievements</h2>
            <ul>
                <li><strong>2023:</strong> Expanded our production facilities to increase capacity.</li>
                <li><strong>2022:</strong> Introduced new varieties of microgreens to our product line.</li>
                <li><strong>2021:</strong> Achieved organic certification for all our products.</li>
            </ul>
        </section>

        <section class="culture">
            <h2>Company Culture</h2>
            <p>We foster a collaborative and innovative environment where our team can thrive. From sustainability initiatives to community involvement, we are dedicated to creating a positive and inclusive workplace.</p>
        </section>

        <section class="testimonials">
            <h2>Customer Testimonials</h2>
            <blockquote>
                <p>"The microgreens from this company have transformed the way I cook. Fresh, flavorful, and nutritious!"</p>
                <footer>— Sarah Lee, Chef</footer>
            </blockquote>
            <blockquote>
                <p>"I love the variety and quality of the microgreens. Their subscription service is a game-changer for my kitchen."</p>
                <footer>— David Kim, Restaurant Owner</footer>
            </blockquote>
        </section>

        <section class="map">
            <h2>Our Location</h2>
            <iframe src="https://www.google.com/maps/embed?pb=YOUR_MAP_EMBED_LINK" width="600" height="450" style="border:0;" allowfullscreen="" loading="lazy"></iframe>
        </section>

    </div>  
    <?php include('footer.php') ?>
<?php include('chat.php') ?>
<?php include('social.php') ?>
</body>
</html>
